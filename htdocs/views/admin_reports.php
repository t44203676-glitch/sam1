<?php
// views/admin_reports.php

// التأكد من أن المتغيرات موجودة لتجنب الأخطاء
if (!isset($requests_by_status)) $requests_by_status = [];
if (!isset($all_requests)) $all_requests = [];

// حساب عدد الطلبات لكل خدمة
$requests_by_service = [];
if (!empty($all_requests)) {
    foreach ($all_requests as $request) {
        $service_name = $request['service_name'] ?? 'غير معروف';
        if (!isset($requests_by_service[$service_name])) {
            $requests_by_service[$service_name] = [
                'total' => 0,
                'statuses' => []
            ];
        }
        $requests_by_service[$service_name]['total']++;
        
        $status = $request['status'] ?? 'غير معروف';
        if (!isset($requests_by_service[$service_name]['statuses'][$status])) {
            $requests_by_service[$service_name]['statuses'][$status] = 0;
        }
        $requests_by_service[$service_name]['statuses'][$status]++;
    }
}
?>

<div class="row">
    <!-- ملخص الطلبات حسب الحالة -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-layer-group me-2"></i>ملخص الطلبات حسب الحالة</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($requests_by_status)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>الحالة</th>
                                    <th class="text-center">عدد الطلبات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_sum = 0;
                                foreach($requests_by_status as $status_report): 
                                    $total_sum += $status_report['count'];
                                ?>
                                <tr>
                                    <td>
                                        <span class="request-status status-<?php echo str_replace(' ', '-', strtolower($status_report['status'])); ?>">
                                            <?php echo htmlspecialchars($status_report['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold"><?php echo $status_report['count']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th class="text-end">الإجمالي</th>
                                    <th class="text-center"><?php echo $total_sum; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">لا توجد بيانات لعرضها.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ملخص الطلبات حسب الخدمة -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-cogs me-2"></i>ملخص الطلبات حسب الخدمة</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($requests_by_service)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم الخدمة</th>
                                    <th class="text-center">إجمالي الطلبات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($requests_by_service as $service_name => $data): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($service_name); ?></td>
                                    <td class="text-center fw-bold"><?php echo $data['total']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">لا توجد بيانات لعرضها.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- تقرير تفصيلي لكل خدمة -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>تقرير تفصيلي لكل خدمة</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($requests_by_service)): ?>
            <div class="accordion" id="servicesAccordion">
                <?php 
                $i = 0;
                foreach($requests_by_service as $service_name => $data): 
                    $accordion_id = 'service-'.preg_replace('/[^a-zA-Z0-9]/', '-', $service_name);
                ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?php echo $accordion_id; ?>">
                            <button class="accordion-button <?php if($i > 0) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $accordion_id; ?>" aria-expanded="<?php echo ($i == 0) ? 'true' : 'false'; ?>" aria-controls="collapse-<?php echo $accordion_id; ?>">
                                <?php echo htmlspecialchars($service_name); ?>
                                <span class="badge bg-primary rounded-pill ms-auto me-3"><?php echo $data['total']; ?> طلب</span>
                            </button>
                        </h2>
                        <div id="collapse-<?php echo $accordion_id; ?>" class="accordion-collapse collapse <?php if($i == 0) echo 'show'; ?>" aria-labelledby="heading-<?php echo $accordion_id; ?>" data-bs-parent="#servicesAccordion">
                            <div class="accordion-body">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>الحالة</th>
                                            <th class="text-center">العدد</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($data['statuses'] as $status => $count): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($status); ?></td>
                                            <td class="text-center"><?php echo $count; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php 
                $i++;
                endforeach; 
                ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">لا توجد بيانات تفصيلية لعرضها.</div>
        <?php endif; ?>
    </div>
</div>

<style>
    .accordion-button:not(.collapsed) {
        background-color: var(--sidebar-link-hover-bg);
        color: var(--primary-accent);
    }
    .fw-bold {
        font-weight: 600 !important;
    }
</style>