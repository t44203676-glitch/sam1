<?php
$reports = [
    ['entity' => 'مركز العمليات الأمنية الموحدة 911، ويشمل: المديرية العامة للأمن العام (المرور، وأمن الطرق، والدوريات الأمنية)، والقوات الخاصة للأمن البيئي، والمديرية العامة للدفاع المدني وأمن المنشآت وحرس الحدود', 'region' => 'منطقة مكة المكرمة والرياض والشرقية', 'number' => '911'],
    ['entity' => 'المديرية العامة لحرس الحدود', 'region' => 'بقية المناطق', 'number' => '994'],
    ['entity' => 'المديرية العامة لمكافحة المخدرات', 'region' => 'بقية المناطق', 'number' => '995'],
    ['entity' => 'المديرية العامة للجوازات', 'region' => 'بقية المناطق', 'number' => '992'],
    ['entity' => 'المديرية العامة للدفاع المدني', 'region' => 'بقية المناطق', 'number' => '998'],
    ['entity' => 'الإدارة العامة للمرور', 'region' => 'بقية المناطق', 'number' => '993'],
    ['entity' => 'الدوريات الأمنية', 'region' => 'بقية المناطق', 'number' => '999'],
    ['entity' => 'القوات الخاصة لأمن الطرق', 'region' => 'بقية المناطق', 'number' => '996'],
];
?>
<td valign="top" style="padding-right: 10px;">
<div class="mid_content">
    <div style="direction: rtl; text-align: right; padding: 10px 0;">
        <h2 style="color: #4C4C4C; font-size: 24px; font-weight: bold; margin-bottom: 20px;">البلاغات الأمنية</h2>

        <!-- Action Buttons -->
        <div class="action-buttons mb-3" style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 10px 0; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; justify-content: flex-start; margin-bottom: 25px;">
            <a href="javascript:window.print()">طباعة <i class="fas fa-print"></i></a>
            <a href="#">إرسال <i class="fas fa-envelope"></i></a>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; border: 1px solid #eee;">
            <thead>
                <tr>
                    <th style="background: #9fa3a7; color: #fff; padding: 12px 15px; text-align: right; border-bottom: 2px solid #ddd;">الجهة</th>
                    <th style="background: #9fa3a7; color: #fff; padding: 12px 15px; text-align: center; border-bottom: 2px solid #ddd; width: 25%;">المنطقة</th>
                    <th style="background: #9fa3a7; color: #fff; padding: 12px 15px; text-align: center; border-bottom: 2px solid #ddd; width: 15%;">الرقم</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $i => $row): ?>
                <tr style="background: <?php echo $i % 2 == 0 ? '#fff' : '#fcfcfc'; ?>; border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 12px 15px; text-align: right; line-height: 1.8; color: #444;"><?php echo $row['entity']; ?></td>
                    <td style="padding: 12px 15px; text-align: center; color: #666;"><?php echo $row['region']; ?></td>
                    <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #00ab67;"><?php echo $row['number']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</td>
