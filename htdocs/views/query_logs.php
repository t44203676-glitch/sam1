<?php
// admin/views/query_logs.php
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Root') {
    die('غير مصرح لك بالوصول.');
}
require_once 'includes/database.php';

// Fetch logs
try {
    $stmt = $pdo->query("SELECT * FROM query_logs ORDER BY created_at DESC LIMIT 1000");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>خطأ في قاعدة البيانات: " . $e->getMessage() . "</div>";
    $logs = [];
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-history me-2 text-warning"></i> سجل الاستعلامات والطباعة
                    </h3>
                    <div class="badge bg-soft-info text-info p-2 rounded-3">
                        إجمالي السجلات: <?php echo count($logs); ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="logsTable" class="table table-hover align-middle" style="width:100%">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>رقم الهوية</th>
                                    <th>رقم الصادر</th>
                                    <th>نوع الخدمة</th>
                                    <th>الإجراء</th>
                                    <th>عنوان IP</th>
                                    <th>الوقت والتاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?php echo $log['id']; ?></td>
                                        <td><span class="badge bg-light text-dark font-monospace"><?php echo htmlspecialchars($log['national_id']); ?></span></td>
                                        <td><span class="badge bg-light text-secondary font-monospace"><?php echo htmlspecialchars($log['export_number']); ?></span></td>
                                        <td><?php echo htmlspecialchars($log['service_type']); ?></td>
                                        <td>
                                            <?php if ($log['action'] === 'query'): ?>
                                                <span class="badge bg-soft-primary text-primary">
                                                    <i class="fas fa-search me-1"></i> استعلام
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-soft-success text-success">
                                                    <i class="fas fa-print me-1"></i> طباعة
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($log['user_ip']); ?></small></td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold"><?php echo date('Y-m-d', strtotime($log['created_at'])); ?></span>
                                                <small class="text-muted"><?php echo date('H:i:s', strtotime($log['created_at'])); ?></small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .font-monospace { font-family: 'Courier New', Courier, monospace; letter-spacing: 1px; }
    
    #logsTable thead th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    #logsTable tbody td {
        padding: 1rem 0.75rem;
    }
</style>

<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#logsTable')) {
        $('#logsTable').DataTable().destroy();
    }
    $('#logsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
        },
        order: [[0, 'desc']],
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
