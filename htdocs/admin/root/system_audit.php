<?php
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Root') {
    die('غير مصرح لك بالوصول.');
}

// 1. Fetch all audit logs
$logQuery = "
    SELECT l.*, u.username, u.user_type 
    FROM request_edits_log l
    LEFT JOIN system_users u ON l.user_id = u.user_id
    ORDER BY l.created_at DESC
    LIMIT 1000
";
$logStmt = $pdo->query($logQuery);
$auditLogs = $logStmt->fetchAll(PDO::FETCH_ASSOC);

// --- NEW GROUPING LOGIC ---
$groupedLogs = [];
foreach ($auditLogs as $log) {
    // Group by Date + Request + User so all edits made on the same day by the same user on a request are batched together
    $dateKey = date('Y-m-d', strtotime($log['created_at']));
    $groupKey = 'req_' . $log['request_id'] . '_usr_' . $log['user_id'] . '_' . $dateKey;

    if (!isset($groupedLogs[$groupKey])) {
        $groupedLogs[$groupKey] = [
            'request_id' => $log['request_id'],
            'source_table' => $log['source_table'],
            'user_id' => $log['user_id'],
            'username' => $log['username'] ?? 'غير معروف',
            'user_type' => $log['user_type'],
            'latest_time' => $log['created_at'],
            'edits' => []
        ];
    }
    $groupedLogs[$groupKey]['edits'][] = $log;
}

// 2. Fetch all users for the tree
$stmtUsers = $pdo->query("SELECT user_id, username, user_type, manager_id FROM system_users");
$allUsersRaw = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

// 3. Count edits per user
$editCounts = [];
$stmtCounts = $pdo->query("SELECT user_id, COUNT(*) as edit_count FROM request_edits_log GROUP BY user_id");
while ($row = $stmtCounts->fetch(PDO::FETCH_ASSOC)) {
    $editCounts[$row['user_id']] = (int)$row['edit_count'];
}

// 4. Build Tree
function buildTree(array $elements, $parentId = 0)
{
    $branch = array();
    foreach ($elements as $element) {
        $mId = $element['manager_id'] ? (int)$element['manager_id'] : 0;
        if ($mId === $parentId || ($parentId === 0 && $element['user_type'] === 'Root' && !$mId)) {
            $children = buildTree($elements, (int)$element['user_id']);
            if ($children) {
                $element['children'] = $children;
            }
            else {
                $element['children'] = [];
            }
            $branch[$element['user_id']] = $element;
        }
    }
    return $branch;
}

$topLevelUsers = [];
foreach ($allUsersRaw as $u) {
    if (empty($u['manager_id'])) {
        $topLevelUsers[] = $u;
    }
}
$userTree = buildTree($allUsersRaw, 0);
if (empty($userTree)) {
    foreach ($topLevelUsers as $top) {
        $top['children'] = buildTree($allUsersRaw, $top['user_id']);
        $userTree[$top['user_id']] = $top;
    }
}

// 5. Calculate hierarchy edits
function calculateEdits(&$node, $editCounts)
{
    $direct = $editCounts[$node['user_id']] ?? 0;
    $children_edits = 0;
    foreach ($node['children'] as &$child) {
        $children_edits += calculateEdits($child, $editCounts);
    }
    $node['direct_edits'] = $direct;
    $node['children_edits'] = $children_edits;
    $node['total_edits'] = $direct + $children_edits;
    return $node['total_edits'];
}

foreach ($userTree as &$rootNode) {
    calculateEdits($rootNode, $editCounts);
}

// Map descendants for JS
$descendantsMap = [];
function buildDescendants(&$map, $node)
{
    if (!isset($map[$node['user_id']])) {
        $map[$node['user_id']] = [(int)$node['user_id']];
    }
    if (isset($node['children']) && is_array($node['children'])) {
        foreach ($node['children'] as $child) {
            $childs = buildDescendants($map, $child);
            $map[$node['user_id']] = array_merge($map[$node['user_id']], $childs);
        }
    }
    return $map[$node['user_id']];
}
foreach ($userTree as $n) {
    buildDescendants($descendantsMap, $n);
}

$fieldMapping = [
    'national_id' => 'رقم الهوية', 'export_number' => 'رقم الصادر', 'applicant_name' => 'اسم مقدم الطلب',
    'status' => 'الحالة', 'rejection_reason' => 'سبب الرفض', 'profile_photo_path' => 'الصورة الشخصية',
    'service_number' => 'رقم الخدمة', 'passport_number' => 'رقم الجواز', 'full_name' => 'الاسم الكامل',
    'visa_no' => 'رقم التأشيرة', 'phone' => 'رقم الهاتف', 'approval_date' => 'تاريخ الموافقة',
    'permit_type' => 'نوع التصريح', 'serial_number' => 'الرقم التسلسلي',
];

function translateField($fieldName)
{
    global $fieldMapping;
    return $fieldMapping[$fieldName] ?? $fieldName;
}
?>

<style>
/* Reset container direction to force RTL and avoid jumping */
.audit-split-container {
    direction: rtl; 
    text-align: right;
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

/* Tree Panel Styles */
.tree-panel {
    flex: 0 0 320px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    padding: 20px;
    max-height: calc(100vh - 100px);
    overflow-y: auto;
    position: sticky;
    top: 20px;
}

.tree-header h5 {
    font-weight: 700;
    color: #1e3c72;
    margin-bottom: 20px;
    border-bottom: 2px solid #e0e6ed;
    padding-bottom: 10px;
}

.tree-list, .tree-list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.tree-list ul {
    margin-right: 25px;
    border-right: 1px dashed #ced4da;
    padding-right: 15px;
}

.tree-node {
    margin: 8px 0;
    cursor: pointer;
    border-radius: 6px;
    padding: 8px 12px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.tree-node:hover {
    background: #f8f9fe;
}
.tree-node.active {
    background: #eef2fa;
    border-right: 4px solid #2a5298;
}

.node-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.node-icon {
    color: #8898aa;
    font-size: 1.1rem;
}
.node-name {
    font-weight: 600;
    color: #32325d;
    font-size: 0.95rem;
}
.node-role {
    font-size: 0.75rem;
    color: #adb5bd;
    display: block;
}

.edit-badgets {
    display: flex;
    align-items: center;
    gap: 5px;
}

.badge-direct {
    background: #f5365c;
    color: #ffffff;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(245, 54, 92, 0.4);
}

.badge-children {
    display: flex;
    align-items: center;
    color: #f5365c;
    font-size: 0.8rem;
    font-weight: bold;
    background: rgba(245, 54, 92, 0.1);
    padding: 2px 6px;
    border-radius: 10px;
}
.badge-children i {
    font-size: 0.7rem;
    margin-left: 2px;
}

/* Accordion Custom Styles */
.table-panel {
    flex: 1;
    min-width: 0; /* Prevent flex overflow */
}

/* Specialized Scroll Container for Audit Diff */
.audit-scroll-wrapper {
    width: 100%;
    display: block;
    overflow-x: auto !important;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 20px;
    background: #fff;
}
.audit-diff-table {
    margin: 0;
    width: 1000px !important; /* Extremely wide to force scroll */
    min-width: 1000px !important;
    max-width: 1000px !important;
    table-layout: fixed;
    border-collapse: collapse;
}
.audit-diff-table th, .audit-diff-table td {
    white-space: normal !important;
    word-wrap: break-word !important;
    padding: 15px !important;
    text-align: right;
}
.audit-accordion-item {
    background: #fff;
    border: none;
    border-radius: 12px !important;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    /* overflow: hidden; */ /* Removed to ensure inner scroll area is not clipped */
    transition: all 0.2s ease;
}
.audit-accordion-item:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.audit-accordion-header {
    margin: 0;
}

.audit-accordion-btn {
    padding: 20px;
    background: #fff;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    text-align: right;
    font-size: 1rem;
    color: #32325d;
    cursor: pointer;
    box-shadow: none !important;
}

.audit-accordion-btn:not(.collapsed) {
    background: #f8f9fe;
    color: #2a5298;
    border-bottom: 1px solid #e0e6ed;
}

.audit-accordion-btn::after {
    display: none; /* Hide default bootstrap arrow */
}
.icon-toggle {
    transition: transform 0.3s ease;
    color: #adb5bd;
}
.audit-accordion-btn:not(.collapsed) .icon-toggle {
    transform: rotate(180deg);
    color: #2a5298;
}

.acc-title-wrap {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.acc-request-id {
    font-weight: 800;
    font-size: 1.1rem;
    padding: 5px 12px;
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
    border-radius: 8px;
}

.acc-meta {
    display: flex;
    flex-direction: column;
}
.acc-meta-top {
    font-weight: 600;
}
.acc-meta-bottom {
    font-size: 0.85rem;
    color: #8898aa;
    margin-top: 3px;
    display: flex;
    gap: 15px;
}

.acc-badge-count {
    background: #f5365c;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Inside Accordion Table */
.audit-diff-table {
    margin: 0;
    width: 100%;
}
.audit-diff-table th {
    background: transparent;
    color: #8898aa;
    font-size: 0.85rem;
    text-transform: uppercase;
    border-bottom: 2px solid #e0e6ed;
    padding: 15px;
}
.audit-diff-table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #e0e6ed;
}
.audit-diff-table tr:last-child td {
    border-bottom: none;
}

.diff-old {
    color: #f5365c;
    text-decoration: line-through;
    background: #fdf5f6;
    padding: 4px 8px;
    border-radius: 6px;
    display: inline-block;
}
.diff-new {
    color: #2dce89;
    background: #ecfcf5;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: bold;
    display: inline-block;
}
.diff-arrow {
    margin: 0 10px;
    color: #ced4da;
    font-size: 0.9rem;
}

@media (max-width: 992px) {
    .audit-split-container {
        flex-direction: column;
        width: 100% !important;
        overflow: hidden !important;
    }
    .tree-panel {
        width: 100% !important;
        position: relative;
        top: 0;
        max-height: 350px;
        margin-bottom: 20px;
    }
    .table-panel {
        width: 100% !important;
        max-width: 100vw !important;
        overflow: hidden !important;
    }
    .audit-scroll-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: scroll !important;
        -webkit-overflow-scrolling: touch !important;
        padding-bottom: 30px !important;
        display: block !important;
    }
    .audit-diff-table {
        width: 1000px !important;
        min-width: 1000px !important;
        display: table !important;
    }
}
</style>

<div class="audit-split-container fade-in">
    <!-- Tree View Sidebar -->
    <div class="tree-panel">
        <div class="tree-header">
            <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>هيكلية المستخدمين</h5>
            <small class="text-muted">اختر مستخدماً لرؤية تعديلاته</small>
        </div>
        
        <ul class="tree-list mt-3">
            <li>
                <div class="tree-node active" onclick="filterAudit('all', this)">
                    <div class="node-info">
                        <i class="fas fa-users node-icon text-primary"></i>
                        <div>
                            <span class="node-name">جميع التعديلات</span>
                        </div>
                    </div>
                </div>
            </li>
            
            <?php
function renderTree($nodes)
{
    echo '<ul class="mt-2" style="display:block;">';
    foreach ($nodes as $node) {
        $hasChildren = !empty($node['children']);
        $icon = $node['user_type'] === 'Root' ? 'fa-user-shield text-danger' :
            ($node['user_type'] === 'مدير' || $node['user_type'] === 'Manager' ? 'fa-user-tie text-info' : 'fa-user text-secondary');

        echo '<li>';
        echo '<div class="tree-node" onclick="filterAudit(' . $node['user_id'] . ', this)">';
        echo '<div class="node-info">';
        echo '<i class="fas ' . $icon . ' node-icon"></i>';
        echo '<div>';
        echo '<span class="node-name">' . htmlspecialchars($node['username']) . '</span>';
        echo '<span class="node-role">' . $node['user_type'] . '</span>';
        echo '</div>';
        echo '</div>';

        echo '<div class="edit-badgets">';
        if ($node['direct_edits'] > 0) {
            echo '<div class="badge-direct" title="عدد الطلبات التي عدلها بنفسه"><i class="fas fa-pen" style="font-size:0.55rem;"></i> ' . $node['direct_edits'] . '</div>';
        }
        if ($node['children_edits'] > 0) {
            echo '<div class="badge-children" title="عدد التعديلات التابعة لمن تحته"><i class="fas fa-caret-down"></i> ' . $node['children_edits'] . '</div>';
        }
        echo '</div>';
        echo '</div>';

        if ($hasChildren) {
            renderTree($node['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
}

renderTree($userTree);
?>
        </ul>
    </div>

    <!-- Main Accordion Area -->
    <div class="table-panel">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
            <h4 class="mb-0 text-primary fw-bold"><i class="fas fa-stream me-2"></i>التعديلات المجمعة</h4>
            <div id="filterStatus" class="badge bg-light text-dark px-3 py-2 rounded-pill border">يعرض: الكل</div>
        </div>

        <?php if (empty($groupedLogs)): ?>
            <div id="noRecordsRow" class="text-center p-5 bg-white rounded-3 shadow-sm text-muted">
                <i class="fas fa-clipboard-check mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                <h5>لا توجد حركات تعديل مسجلة حالياً</h5>
            </div>
        <?php
else: ?>
            <div class="accordion" id="auditAccordion">
                <?php foreach ($groupedLogs as $key => $group):
        $ts = strtotime($group['latest_time']);
        $dateObj = date('Y-m-d', $ts);
        $timeObj = date('h:i A', $ts);

        $serviceName = explode('_', $group['source_table'])[0];
        $arService = [
            'marriage' => 'زواج', 'family' => 'عائلية', 'tourism' => 'سياحية', 'business' => 'تجارية',
            'labor' => 'عامل', 'civil' => 'أحوال', 'profession' => 'مهنة', 'recruitment' => 'استقدام'
        ];
        $serviceText = $arService[$serviceName] ?? $group['source_table'];

        $editsCount = count($group['edits']);
?>
                <div class="audit-accordion-item audit-row" data-user-id="<?php echo $group['user_id']; ?>">
                    <h2 class="audit-accordion-header" id="heading_<?php echo $key; ?>">
                        <button class="audit-accordion-btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $key; ?>" aria-expanded="false" aria-controls="collapse_<?php echo $key; ?>">
                            <div class="acc-title-wrap">
                                <div class="acc-request-id">#<?php echo $group['request_id']; ?></div>
                                <div class="acc-meta">
                                    <div class="acc-meta-top">
                                        تعديل طلب <span class="text-primary"><?php echo $serviceText; ?></span>
                                    </div>
                                    <div class="acc-meta-bottom">
                                        <span><i class="fas fa-user-edit me-1"></i> <?php echo htmlspecialchars($group['username']); ?> (<?php echo htmlspecialchars($group['user_type']); ?>)</span>
                                        <span><i class="fas fa-calendar-alt me-1"></i> <?php echo $dateObj; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="acc-badge-count"><i class="fas fa-layer-group"></i> <?php echo $editsCount; ?> حقول مصابة</span>
                                <i class="fas fa-chevron-down icon-toggle"></i>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse_<?php echo $key; ?>" class="accordion-collapse collapse" aria-labelledby="heading_<?php echo $key; ?>" data-bs-parent="#auditAccordion">
                        <div class="accordion-body p-0">
                            <div class="audit-scroll-wrapper">
                                <table class="table table-bordered audit-diff-table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:20%">الحقل المُعدَّل</th>
                                            <th style="width:30%">القيمة القديمة (كان)</th>
                                            <th style="width:10%"></th>
                                            <th style="width:30%">القيمة الجديدة (أصبح)</th>
                                            <th style="width:10%">وقت التعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($group['edits'] as $edit): ?>
                                        <tr>
                                            <td class="fw-bold text-dark"><?php echo translateField($edit['field_name']); ?></td>
                                            <td><span class="diff-old"><?php echo htmlspecialchars($edit['old_value'] ?: '(فارغ)'); ?></span></td>
                                            <td class="text-center"><i class="fas fa-arrow-left diff-arrow"></i></td>
                                            <td><span class="diff-new"><?php echo htmlspecialchars($edit['new_value'] ?: '(فارغ)'); ?></span></td>
                                            <td class="text-muted small" dir="ltr"><?php echo date('h:i A', strtotime($edit['created_at'])); ?></td>
                                        </tr>
                                        <?php
        endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 bg-light border-top text-start">
                                <a href="?admin=1&section=view_request&id=<?php echo $group['request_id']; ?>&table=<?php echo $group['source_table']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i> عرض الطلب بالكامل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
    endforeach; ?>
            </div>
            
            <div id="noFilterResults" class="text-center p-5 bg-white rounded-3 shadow-sm" style="display:none;">
                <i class="fas fa-exclamation-circle mb-3 text-danger" style="font-size: 3rem; opacity: 0.5;"></i>
                <h5 class="text-danger fw-bold">لا يوجد تعديلات مطابقة لهذا المستخدم بالذات.</h5>
            </div>
        <?php
endif; ?>
    </div>
</div>

<script>
// Pass PHP array to JS
const userDescendants = <?php echo json_encode($descendantsMap); ?>;

function filterAudit(userId, element) {
    // UI active state change
    document.querySelectorAll('.tree-node').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
    
    // Change Badge text
    const userName = element.querySelector('.node-name').innerText;
    document.getElementById('filterStatus').innerText = "يعرض: " + userName;

    // Filter Accordion Items
    const rows = document.querySelectorAll('.audit-row');
    let visibleCount = 0;

    if (userId === 'all') {
        rows.forEach(row => {
            row.style.display = '';
            visibleCount++;
        });
    } else {
        const allowedIds = userDescendants[userId] || [String(userId)];
        
        rows.forEach(row => {
            const rowUserId = row.getAttribute('data-user-id');
            // Check if row user is in the allowed descendants
            if (allowedIds.includes(parseInt(rowUserId)) || allowedIds.includes(String(rowUserId))) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Toggle "No results" message
    const noResults = document.getElementById('noFilterResults');
    if (noResults) {
        noResults.style.display = visibleCount === 0 ? '' : 'none';
    }
}
</script>
