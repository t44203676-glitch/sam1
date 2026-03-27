<?php
$statements = [
    [
        'img'   => 'images/22.jpg',
        'title' => 'المملكة ممثلة بوزارة الداخلية تُسهم في إحباط محاولة تهريب (25) كيلوجرامًا من مادة الكوكايين المخدر بمملكة البلين',
        'date'  => 'الأحد  الأول حمادى 4 1447',
        'desc'  => 'صرح المتحدث الأمني لوزارة الداخلية العميد طلال بن عبدالمحسن بن شلهوب، بأن المتابعة الأمنية الاستباقية، التي تقوم بها الوزارة ممثلة بالمديرية العامة لمكافحة المخدرات...',
        'link'  => '#',
    ],
    [
        'img'   => 'images/22.jpg',
        'title' => 'المتحدث الأمني لوزارة الداخلية: ضبط (6,875,000) فرص من مادة الإمفيتامين المخدر منقول من الجمهورية اللبنانية في محاولة لتهريبها إلى إحدى الدول عبر ميناء جدة الإسلامي',
        'date'  => 'الأربعاء 18 ربيع الأول 1447',
        'desc'  => 'صرح المتحدث الأمني لوزارة الداخلية العميد طلال بن عبدالمحسن بن شلهوب، بأن المتابعة الأمنية الاستباقية التي تقوم بها الوزارة ممثلة بالمديرية العامة لمكافحة المخدرات...',
        'link'  => '#',
    ],
    [
        'img'   => 'images/22.jpg',
        'title' => 'المملكة ممثلة بوزارة الداخلية تُسهم في إحباط محاولة تهريب 125 كيلوجرامًا من الكوكايين المخدر بجمهورية لبنان',
        'date'  => 'الثلاثاء 10 ربيع الأول 1447',
        'desc'  => 'صرح المتحدث الأمني لوزارة الداخلية العميد طلال بن عبدالمحسن بن شلهوب، بأن المتابعة الأمنية الاستباقية، التي تقوم بها الوزارة الممثلة بالجهات الأمنية الاستباقية لمكافحة الشبكات الإجرامية التي تمتهن تهريب المخدرات...',
        'link'  => '#',
    ],
    [
        'img'   => 'images/22.jpg',
        'title' => 'المملكة ممثلة بوزارة الداخلية تُسهم في إحباط تقريب أكثر من (5,000,000) فرص من مادة الإمفيتامين المخدر بالتنسيق مع "الجمارك اللبنانية"',
        'date'  => 'الثلاثاء 6 محرم 1447',
        'desc'  => 'صرح المتحدث الأمني لوزارة الداخلية العقيد طلال عبدالمحسن بن شلهوب، بأنه في ضوء المتابعة الأمنية الاستباقية لنشاطات الشبكات الإجرامية التي تمتهن تهريب المخدرات...',
        'link'  => '#',
    ],
    [
        'img'   => 'images/22.jpg',
        'title' => 'مركز العمليات الإعلامي الموحد يعقد "الإحاطة الإعلامية لحج 1446– 2025" بمشاركة المتحدث الأمني لوزارة الداخلية',
        'date'  => 'الجمعة 10 ذو الحجة 1446',
        'desc'  => 'عقد مركز العمليات الإعلامي الموحد للحج بمقر وزارة الإعلام مساء اليوم "الإحاطة الإعلامية لحج 1446 – 2025"، بمشاركة المتحدث الأمني لوزارة الداخلية العقيد طلال بن عبدالمحسن...',
        'link'  => '#',
    ],
];
?>
<td valign="top" style="padding-right: 10px;">
<div class="mid_content">

    <div style="direction: rtl; text-align: right; padding: 10px 0;">
        <h2 style="color: #4C4C4C; font-size: 24px; font-weight: bold; margin-bottom: 20px;">بيانات المتحدث الأمني</h2>
        
        <!-- Action Buttons -->
        <div class="action-buttons mb-3" style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 10px 0; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; justify-content: flex-start; margin-bottom: 25px;">
            <a href="javascript:window.print()">طباعة <i class="fas fa-print"></i></a>
            <a href="#">إرسال <i class="fas fa-envelope"></i></a>
        </div>

        <!-- News List -->
        <div style="margin-top: 20px;">
            <?php foreach ($statements as $item): ?>
            <div style="display: flex; gap: 20px; border-bottom: 1px solid #f5f5f5; padding: 20px 0; align-items: flex-start;">
                <div style="flex-shrink: 0;">
                    <a href="<?php echo $item['link']; ?>">
                        <img src="<?php echo BASE_URL . $item['img']; ?>" alt="" style="width: 120px; height: 75px; object-fit: cover; border: 1px solid #eee; padding: 2px;">
                    </a>
                </div>
                <div style="flex: 1;">
                    <a href="<?php echo $item['link']; ?>" style="color: #4C4C4C; font-size: 16px; font-weight: bold; text-decoration: none; display: block; margin-bottom: 8px; line-height: 1.5;">
                        <?php echo $item['title']; ?>
                    </a>
                    <div style="font-size: 13px; color: #999; margin-bottom: 10px;"><?php echo $item['date']; ?></div>
                    <p style="font-size: 14px; color: #666; line-height: 1.8; margin: 0;">
                        <?php echo $item['desc']; ?> 
                        <a href="<?php echo $item['link']; ?>" style="color:#00ab67; font-weight: bold; text-decoration: none; margin-right: 5px;">المزيد...</a>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div style="text-align: center; margin-top: 30px; direction: ltr; display: flex; justify-content: center; gap: 5px; align-items: center;">
            <span style="color: #999; cursor: pointer; padding: 5px;">|&lt;</span>
            <span style="color: #999; cursor: pointer; padding: 5px;">&lt;</span>
            
            <?php for ($p = 1; $p <= 5; $p++): ?>
            <a href="#" style="color: <?php echo $p == 1 ? '#fff' : '#666'; ?>; background: <?php echo $p == 1 ? '#9fa3a7' : 'none'; ?>; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 4px; text-decoration: none; font-size: 14px; border: 1px solid <?php echo $p == 1 ? '#9fa3a7' : '#eee'; ?>;">
                <?php echo $p; ?>
            </a>
            <?php endfor; ?>
            <span style="color: #999; font-size: 14px; padding: 0 5px;">...</span>
            <a href="#" style="color: #666; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 4px; text-decoration: none; font-size: 14px; border: 1px solid #eee;">10</a>

            <span style="color: #00ab67; cursor: pointer; padding: 5px;">&gt;</span>
            <span style="color: #00ab67; cursor: pointer; padding: 5px;">&gt;|</span>
        </div>
    </div>

</div>
</td>
