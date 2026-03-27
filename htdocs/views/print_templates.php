<?php
// views/print_templates.php
// This view provides a selection of print templates for the employee.

if (!isset($request)) {
    // Fallback or error handling if request data is missing
    echo "<div class='alert alert-danger'>خطأ: لا توجد بيانات للطباعة.</div>";
    exit;
}

// Data is available in $request
?>
<main class="container my-4">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">اختيار نموذج الطباعة</h5>
        </div>
        <div class="card-body">
            <p>يرجى اختيار أحد نماذج الطباعة الثلاثة للمعلومات الخاصة بالمعاملة رقم: <strong><?php echo htmlspecialchars($request['issuance_number'] ?? $request['serial_number'] ?? $request['id'] ?? 'غير متوفر'); ?></strong></p>
            
            <div class="row row-cols-1 row-cols-md-3 g-4 text-center">
                
                <!-- Template 1: Official Ministry of Justice Format (Based on N.jpg) -->
                <div class="col">
                    <div class="card h-100 print-option" onclick="window.open('index.php?admin=1&section=print_view&template=1&id=<?php echo htmlspecialchars($request['id'] ?? ''); ?>&table=<?php echo htmlspecialchars($_GET['table'] ?? 'marriage_permits'); ?>', '_blank')">
                        <div class="card-body">
                            <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                            <h6 class="card-title">النموذج الرسمي (وزارة العدل)</h6>
                            <p class="card-text text-muted">طباعة البيانات في شكل خطاب رسمي بناءً على النموذج (N.jpg).</p>
                        </div>
                    </div>
                </div>

                <!-- Template 2: Detailed Report Format -->
                <div class="col">
                    <div class="card h-100 print-option" onclick="window.open('index.php?admin=1&section=print_view&template=2&id=<?php echo htmlspecialchars($request['id'] ?? ''); ?>&table=<?php echo htmlspecialchars($_GET['table'] ?? 'marriage_permits'); ?>', '_blank')">
                        <div class="card-body">
                            <i class="fas fa-print fa-3x text-success mb-3"></i>
                            <h6 class="card-title">نموذج تقرير مفصل</h6>
                            <p class="card-text text-muted">تقرير شامل يتضمن جميع التفاصيل والحالات المشروحة.</p>
                        </div>
                    </div>
                </div>

                <!-- Template 3: Summary/Receipt Format -->
                <div class="col">
                    <div class="card h-100 print-option" onclick="window.open('index.php?admin=1&section=print_view&template=3&id=<?php echo htmlspecialchars($request['id'] ?? ''); ?>&table=<?php echo htmlspecialchars($_GET['table'] ?? 'marriage_permits'); ?>', '_blank')">
                        <div class="card-body">
                            <i class="fas fa-receipt fa-3x text-warning mb-3"></i>
                            <h6 class="card-title">نموذج ملخص/إيصال</h6>
                            <p class="card-text text-muted">ملخص سريع للبيانات الرئيسية وحالة المعاملة.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .print-option {
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #ccc;
    }
    .print-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
</style>
