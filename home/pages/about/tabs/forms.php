<div class="mid_content">
    <div style="direction: rtl; text-align: right; padding: 10px 0;">
        <h2 style="color: #4C4C4C; font-size: 24px; font-weight: bold; margin-bottom: 20px;">دليل النماذج الإلكترونية</h2>
        
        <!-- Action Buttons -->
        <div class="action-buttons mb-3" style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 10px 0; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; justify-content: flex-start; margin-bottom: 25px;">
            <a href="javascript:window.print()">طباعة <i class="fas fa-print"></i></a>
            <a href="#">إرسال <i class="fas fa-envelope"></i></a>
        </div>

        <p style="font-size: 13px; color: #555; line-height: 1.8; margin: 12px 0;">
            توفر البوابة الإلكترونية للمواطنين والمقيمين مجموعة من النماذج الإلكترونية المعتمدة لدى مختلف قطاعات وزارة الداخلية، ويتطلب عرض، وطباعة النماذج تحميل برنامج أدوبي أكروبات ريدر (Adobe Acrobat reader)، لتثبيت برنامج أدوبي أكروبات ريدر، إضافة إلى برنامج دعم اللغة العربية، اضغط على الرابط (أسفل الشاشة).
        </p>

        <?php
        // Image icons from the images folder
        $icon_pdf  = '<img src="' . BASE_URL . 'images/PDF_ICON.png"  alt="PDF"  title="تحميل PDF"  style="width:30px;height:30px;margin-left:6px;cursor:pointer;vertical-align:middle;">';
        $icon_word = '<img src="' . BASE_URL . 'images/WORD_ICON.png" alt="Word" title="تحميل Word" style="width:30px;height:30px;cursor:pointer;vertical-align:middle;">';

        // Helper function for items with icons below (like Passports)
        function form_item_with_icons($title) {
            global $icon_word, $icon_pdf;
            echo '<li style="margin-bottom: 18px; list-style: disc; margin-right: 15px;">';
            echo '<div style="margin-bottom: 6px; font-size:13px; color: #333;">' . $title . '</div>';
            echo '<div style="line-height:1; margin-right: 20px;">' . $icon_pdf . $icon_word . '</div>';
            echo '</li>';
        }

        // Helper function for simple green links (like lower sections)
        function form_link_item($title) {
            echo '<li style="margin-bottom: 8px; list-style: disc; margin-right: 15px;">';
            echo '<a href="#" style="color: #00ab67; text-decoration: none; font-size: 13px;">' . $title . '</a>';
            echo '</li>';
        }
        ?>

        <!-- Passports Section -->
        <div style="margin-bottom: 20px;">
            <h4 style="color: #333; font-size: 14px; font-weight: bold; margin-bottom: 15px;">الجوازات:</h4>
            <ul style="padding-right: 0; margin-right: 0;">
                <?php form_item_with_icons('نموذج اصدار وتجديد جواز سفر'); ?>
                <?php form_item_with_icons('طلب تمديد تأشيرة زيارة'); ?>
                <?php form_item_with_icons('طلب تعديل المعلومات الخاصة بالوافدين المسجلين'); ?>
                <?php form_item_with_icons('طلب تصريح بالسفر للعراق'); ?>
                <?php form_item_with_icons('طلب إصدار وتجديد رخصة إقامة، تأشيرة سفر، نقل خدمات، إضافة تابع، نقل معلومات'); ?>
                <?php form_item_with_icons('استمارة بلاغ (فقدان - سرقة) الجواز'); ?>
            </ul>
        </div>

        <!-- Civil Affairs Section -->
        <div style="margin-bottom: 20px;">
            <h4 style="color: #333; font-size: 14px; font-weight: bold; margin-bottom: 10px;">الأحوال المدنية:</h4>
            <ul style="padding-right: 0; margin-right: 0;">
                <?php form_link_item('نموذج طلب: (تعديل - حذف - إضافة - تصحيح)'); ?>
                <?php form_link_item('نموذج إشعار تغيير بيانات مواطن'); ?>
                <?php form_link_item('نموذج إصدار الهوية الوطنية'); ?>
                <?php form_link_item('نموذج إصدار سجل أسرة - نسخة شهادة ميلاد للأم - بدل مفقود وتالف - نسخة قيد'); ?>
                <?php form_link_item('نموذج تسجيل واقعة: ( ميلاد - وفاة - زواج - طلاق - ربط)'); ?>
            </ul>
        </div>

        <!-- Traffic Section -->
        <div style="margin-bottom: 20px;">
            <h4 style="color: #333; font-size: 14px; font-weight: bold; margin-bottom: 10px;">المرور:</h4>
            <ul style="padding-right: 0; margin-right: 0;">
                <?php form_link_item('تصدير مركبة'); ?>
                <?php form_link_item('طلب إصدار رخصة قيادة'); ?>
                <?php form_link_item('طلب تسجيل مركبة'); ?>
                <?php form_link_item('تشليح مركبة'); ?>
                <?php form_link_item('نموذج تحديث عناوين'); ?>
                <?php form_link_item('تفويض قيادة مركبة'); ?>
            </ul>
        </div>

        <!-- Weapons Section -->
        <div style="margin-bottom: 20px;">
            <h4 style="color: #333; font-size: 14px; font-weight: bold; margin-bottom: 10px;">الأسلحة والمتفجرات:</h4>
            <ul style="padding-right: 0; margin-right: 0;">
                <?php form_link_item('طلب موافقة مبدئية للحصول على ترخيص إنشاء نادي رماية'); ?>
                <?php form_link_item('نموذج طلب ممارسة نشاط يتعلق بالأسلحة والذخائر'); ?>
                <?php form_link_item('استمارة رخصة سلاح ناري'); ?>
                <?php form_link_item('نموذج تنازل عن سلاح مرخص'); ?>
                <?php form_link_item('نموذج رخصة فتح محل أسلحة نارية فردية'); ?>
                <?php form_link_item('نموذج كشف طبي'); ?>
                <?php form_link_item('نموذج تعهد والتزام للحصول على رخصة سلاح ناري'); ?>
                <?php form_link_item('نموذج تحديث عنوان للمتقدم للحصول على رخصة سلاح ناري'); ?>
                <?php form_link_item('نموذج طلب نقل سلاح مرخص بالاقتناء'); ?>
                <?php form_link_item('نموذج الاعتراض على مخالفة الحج بلا تصريح لعام 1446 هـ'); ?>
            </ul>
        </div>

        <!-- Note Box -->
        <div style="margin: 20px 0; padding: 15px 20px; background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px; direction: rtl; text-align: right; font-size: 13px; color: #555;">
            <div style="margin-bottom: 10px; display: flex; align-items: center; justify-content: flex-start; gap: 8px;">
                <span>يتطلب عرض الوثائق أعلاه وجود</span>
                <img src="<?php echo BASE_URL; ?>images/reader_128.jpg" alt="Adobe" style="width:24px; height:24px; vertical-align:middle;">
                <a href="https://get.adobe.com/reader/" target="_blank" style="color: #00ab67; font-weight: bold; text-decoration: none;">أدوبي أكروبات ريدر</a>
            </div>
            <div style="display: flex; align-items: center; justify-content: flex-start; gap: 4px;">
                <span>في حال عدم تمكنك من عرض النماذج، يمكنك مراجعة صفحة</span>
                <a href="?tab=help" style="color: #00ab67; font-weight: bold; text-decoration: none;">المساعدة</a>
            </div>
        </div>
    </div>

</div>
</td>
