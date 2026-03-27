<?php
// views/admin_status_list.php
// ملف لعرض قائمة ديناميكية بحالات الطلبات

// 1. الإعداد المركزي: مصفوفة لتعريف خصائص كل حالة
$status_config = [
    'قيد المراجعة' => [
        'icon' => 'fas fa-search',
        'color' => 'text-warning',
        'description' => 'طلبات جديدة قيد المراجعة والتدقيق.'
    ],
    'تمت الموافقة' => [
        'icon' => 'fas fa-check-circle',
        'color' => 'text-success',
        'description' => 'طلبات تمت الموافقة عليها.'
    ],
    'تم تعليق المعاملة' => [
        'icon' => 'fas fa-pause-circle',
        'color' => 'text-purple',
        'description' => 'معاملات تم تعليقها مؤقتاً.'
    ],
    'مرفوض' => [
        'icon' => 'fas fa-times-circle',
        'color' => 'text-danger',
        'description' => 'طلبات تم رفضها من قبل الإدارة.'
    ]
];
?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">حالات الطلبات في النظام</h5>
    </div>
    <div class="list-group list-group-flush">
        <?php
        // 2. الإنشاء الديناميكي: المرور على المصفوفة وإنشاء عناصر القائمة
        foreach ($status_config as $status_name => $config) {
            $icon_html = "<i class='{$config['icon']} {$config['color']} me-3'></i>";
            echo "<div class='list-group-item d-flex justify-content-between align-items-center'>";
            echo "<div>{$icon_html}<strong>" . htmlspecialchars($status_name) . "</strong><small class='d-block text-muted ms-5'>" . htmlspecialchars($config['description']) . "</small></div>";
            echo "<a href='index.php?admin=1&section=requests&status=" . urlencode($status_name) . "' class='btn btn-sm btn-outline-secondary'>عرض</a>";
            echo "</div>";
        }
        ?>
    </div>
</div>