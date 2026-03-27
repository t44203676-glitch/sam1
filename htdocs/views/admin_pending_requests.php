<?php
// views/admin_pending_requests.php
// يعرض هذا الملف الطلبات التي تنتظر موافقة المدير من جميع أنواع الخدمات
require_once __DIR__ . '/../includes/database.php';

$pending_requests = [];
if ($pdo) {
    $user_type = $_SESSION['user_type'] ?? '';
    $user_id = $_SESSION['user_id'] ?? 0;

    // تعريف قائمة الخدمات للتوافق مع قاعدة البيانات
    $service_tables = [
        'marriage_permits' => 'تصريح زواج',
        'family_visits' => 'زيارة عائلية',
        'tourism_visits' => 'زيارة سياحية',
        'business_visits' => 'زيارة تجارية',
        'recruitment_requests' => 'استقدام',
        'labor_requests' => 'مكتب العمل',
        'civil_affairs_requests' => 'أحوال مدنية',
        'profession_changes' => 'تغيير مهنة',
        'followup_requests' => 'التعقيب',
    ];

    // جلب قائمة الموظفين التابعين للمدير
    $team_ids = [$user_id];
    if ($user_type === 'مدير' || $user_type === 'Manager') {
        $stmt_emps = $pdo->prepare("SELECT user_id FROM system_users WHERE manager_id = ?");
        $stmt_emps->execute([$user_id]);
        $team_ids = array_merge($team_ids, $stmt_emps->fetchAll(PDO::FETCH_COLUMN));
    }

    // Fetch all users for "By" column mapping
    $system_users_map = [];
    $stmt_u = $pdo->query("SELECT user_id, username FROM system_users");
    while ($su = $stmt_u->fetch(PDO::FETCH_ASSOC)) {
        $system_users_map[$su['user_id']] = $su['username'];
    }

    $all_access = ($user_type === 'Root' || $user_type === 'Admin');

    foreach ($service_tables as $table => $service_name) {
        try {
            $sql = "SELECT id, export_number, applicant_name, national_id, created_at, status, 
                           '$table' as service_table, '$service_name' as service_type 
                    FROM `$table` 
                    WHERE status = 'بانتظار موافقة المدير'";
            
            if (!$all_access) {
                $in_placeholders = str_repeat('?,', count($team_ids) - 1) . '?';
                $sql .= " AND created_by_user_id IN ($in_placeholders)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($team_ids);
            } else {
                $stmt = $pdo->query($sql);
            }

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $pending_requests = array_merge($pending_requests, $results);
        } catch (PDOException $e) {
            error_log("Error fetching from $table: " . $e->getMessage());
        }
    }
    
    usort($pending_requests, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
} else {
    echo "<div class='alert alert-danger'>خطأ في الاتصال بقاعدة البيانات.</div>";
}

?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">الطلبات قيد المراجعة</h5>
    </div>
    <div class="card-body">
        <?php if (empty($pending_requests)): ?>
            <div class="alert alert-info text-center">لا توجد طلبات تنتظر المراجعة حاليًا.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width:45px;">#</th>
                            <th>رقم الصادر</th>
                            <th>اسم مقدم الطلب</th>
                            <th>رقم الهوية</th>
                            <th>نوع الخدمة</th>
                            <th>بواسطة</th>
                            <th>تاريخ التقديم</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $form_name_map = [
                            'marriage_permits' => 'marriage',
                            'family_visits' => 'family_visit',
                            'tourism_visits' => 'tourism',
                            'business_visits' => 'business',
                            'recruitment_requests' => 'recruitment',
                            'labor_requests' => 'labor',
                            'civil_affairs_requests' => 'civil_affairs',
                            'profession_changes' => 'profession_change',
                            'followup_requests' => 'followup',
                        ];
                        
                        $total_pending = count($pending_requests);
                        $last3_colors  = [
                            0 => '#fffacc',  // الأحدث   - أصفر فاتح
                            1 => '#d4f8d4',  // ثاني أحدث - أخضر فاتح
                            2 => '#d0f0ff',  // ثالث أحدث - أزرق فاتح
                        ];
                        
                        foreach($pending_requests as $row_idx => $request): 
                            $form_name    = $form_name_map[$request['service_table']] ?? '';
                            $view_url     = "index.php?admin=1&section=view_request&id={$request['id']}&table={$request['service_table']}";
                            $row_bg       = isset($last3_colors[$row_idx]) ? '--bs-table-bg:' . $last3_colors[$row_idx] . '; background-color:' . $last3_colors[$row_idx] . ' !important;' : '';
                            $row_num      = $total_pending - $row_idx;
                            $ts           = strtotime($request['created_at']);
                            $ampm_ar      = date('A', $ts) === 'AM' ? 'ص' : 'م';
                            $date_str     = date('Y-m-d', $ts) . ' ' . date('h:i', $ts) . ' ' . $ampm_ar;
                        ?>
                        <tr data-id="<?php echo $request['id']; ?>" data-source-table="<?php echo $request['service_table']; ?>" style="<?php echo $row_bg; ?>">
                            <td class="fw-bold text-muted"><?php echo $row_num; ?></td>
                            <td data-field="export_number" data-label="رقم الصادر"><?php echo htmlspecialchars(toWesternDigits($request['export_number'])); ?></td>
                            <td data-field="applicant_name" data-label="اسم مقدم الطلب"><?php echo htmlspecialchars($request['applicant_name']); ?></td>
                            <td data-field="national_id" data-label="رقم الهوية"><?php echo htmlspecialchars(toWesternDigits($request['national_id'])); ?></td>
                            <td data-label="نوع الخدمة"><?php echo htmlspecialchars($request['service_type']); ?></td>
                            <td data-label="بواسطة" class="text-muted small"><?php echo htmlspecialchars($system_users_map[$request['created_by_user_id'] ?? 0] ?? 'Root'); ?></td>
                            <td data-field="created_at" data-label="تاريخ التقديم"><?php echo htmlspecialchars($date_str); ?></td>
                            <td data-field="status" data-label="الحالة">
                                <span class="badge bg-primary status-display">
                                    <?php echo htmlspecialchars($request['status']); ?>
                                </span>
                            </td>
                            <td data-label="الإجراءات">
                                <div class="btn-group">
                                    <a href="<?php echo $view_url; ?>" class="btn btn-sm btn-outline-primary" title="المراجعة">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-warning btn-edit" title="تعديل سريع">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success btn-save" title="حفظ" style="display: none;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-secondary btn-cancel" title="إلغاء" style="display: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-delete-request" data-id="<?php echo $request['id']; ?>" data-table="<?php echo $request['service_table']; ?>" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.request-status {
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.badge {
    font-weight: 500;
}
</style>
