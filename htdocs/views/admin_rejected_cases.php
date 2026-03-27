<?php
// views/admin_rejected_cases.php
// يعرض هذا الملف الطلبات المرفوضة - للموظف: طلباته فقط، للمدير: جميع الطلبات
require_once __DIR__ . '/../includes/database.php';

$rejected_requests = [];

// تعريف جميع جداول الخدمات
$service_tables = [
    'marriage_permits' => 'تصريح زواج',
    'family_visits' => 'زيارة عائلية',
    'tourism_visits' => 'زيارة سياحية',
    'business_visits' => 'زيارة تجارية',
    'recruitment_requests' => 'استقدام',
    'labor_requests' => 'مكتب العمل',
    'civil_affairs_requests' => 'أحوال مدنية',
    'profession_changes' => 'تغيير مهنة',
    'runaway_cancellations' => 'إلغاء بلاغ هروب'
];

if ($pdo) {
    $current_user_id = $_SESSION['user_id'] ?? null;
    $user_type = $_SESSION['user_type'] ?? '';

    // جلب الطلبات المرفوضة من جميع الجداول
    foreach ($service_tables as $table => $service_name) {
        try {
            // المدير يرى جميع الطلبات المرفوضة، الموظف يرى طلباته فقط
            if ($user_type === 'مدير') {
                $stmt = $pdo->prepare("
                    SELECT 
                        id,
                        export_number,
                        applicant_name,
                        national_id,
                        created_at,
                        status,
                        rejection_reason,
                        created_by_user_id,
                        '$table' as service_table,
                        '$service_name' as service_type
                    FROM `$table` 
                    WHERE status = 'مرفوض' 
                    ORDER BY created_at DESC
                ");
                $stmt->execute();
            } else {
                $stmt = $pdo->prepare("
                    SELECT 
                        id,
                        export_number,
                        applicant_name,
                        national_id,
                        created_at,
                        status,
                        rejection_reason,
                        created_by_user_id,
                        '$table' as service_table,
                        '$service_name' as service_type
                    FROM `$table` 
                    WHERE status = 'مرفوض' AND created_by_user_id = ?
                    ORDER BY created_at DESC
                ");
                $stmt->execute([$current_user_id]);
            }
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rejected_requests = array_merge($rejected_requests, $results);
        } catch (PDOException $e) {
            // تجاهل الأخطاء للجداول غير الموجودة
            error_log("Error fetching from $table: " . $e->getMessage());
        }
    }
    
    // ترتيب النتائج حسب التاريخ
    usort($rejected_requests, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
} else {
    echo "<div class='alert alert-danger'>خطأ في الاتصال بقاعدة البيانات.</div>";
}

?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-times-circle text-danger me-2"></i>
            الحالات المرفوضة
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($rejected_requests)): ?>
            <div class="alert alert-info text-center">
                <?php if ($user_type === 'موظف'): ?>
                    لا توجد طلبات مرفوضة لك حاليًا.
                <?php else: ?>
                    لا توجد حالات مرفوضة حاليًا.
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>رقم الصادر</th>
                            <th>اسم مقدم الطلب</th>
                            <th>رقم الهوية</th>
                            <th>نوع الخدمة</th>
                            <th>تاريخ التقديم</th>
                            <th>سبب الرفض</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rejected_requests as $request): ?>
                        <tr data-id="<?php echo $request['id']; ?>">
                            <td data-label="رقم الصادر"><?php echo htmlspecialchars($request['export_number']); ?></td>
                            <td data-label="اسم مقدم الطلب"><?php echo htmlspecialchars($request['applicant_name']); ?></td>
                            <td data-label="رقم الهوية"><?php echo htmlspecialchars($request['national_id']); ?></td>
                            <td data-label="نوع الخدمة">
                                <span class="badge bg-secondary">
                                    <?php echo htmlspecialchars($request['service_type']); ?>
                                </span>
                            </td>
                            <td data-label="تاريخ التقديم"><?php echo htmlspecialchars(convertToHijri($request['created_at'])); ?></td>
                            <td data-label="سبب الرفض" class="text-danger">
                                <strong><?php echo htmlspecialchars($request['rejection_reason'] ?? 'لا يوجد سبب مسجل.'); ?></strong>
                            </td>
                            <td data-label="الإجراءات">
                                <?php 
                                $is_owner = ($request['created_by_user_id'] == $current_user_id);
                                ?>
                                
                                <a href="index.php?admin=1&section=view_request&id=<?php echo $request['id']; ?>&table=<?php echo $request['service_table']; ?>" 
                                   class="btn btn-sm btn-outline-secondary" 
                                   title="عرض التفاصيل الكاملة">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                
                                <?php if ($is_owner || $user_type === 'مدير'): ?>
                                <a href="index.php?admin=1&section=view_request&id=<?php echo $request['id']; ?>&table=<?php echo $request['service_table']; ?>" 
                                   class="btn btn-sm btn-warning" 
                                   title="تعديل وإعادة إرسال">
                                    <i class="fas fa-edit"></i> تعديل وإعادة إرسال
                                </a>
                                <?php endif; ?>
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
.badge {
    font-weight: 500;
    padding: 0.35rem 0.65rem;
}

.table td {
    vertical-align: middle;
}

.text-danger strong {
    font-size: 0.95rem;
}
</style>