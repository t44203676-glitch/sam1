<?php
$page_title = "أهداف ومهام الوزارة";
$active_page = "about";
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';
?>

<div class="container row">
    <table class="layoutRow ibmDndRow component-container" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr>
                <td valign="top" style="width: 180px;">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr><td style="width:100%;" valign="top"><?php include '../../includes/sidebar_about.php'; ?></td></tr>
                        </tbody>
                    </table>
                </td>

                <td valign="top">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="mid_content">
                                        <div class="heading_featured">أهداف ومهام وزارة الداخلية</div>
                                        <div class="action-buttons mb-3" style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 10px 0; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; justify-content: flex-start; direction: rtl; margin-bottom: 20px;">
                                            <a href="javascript:window.print()">طباعة <i class="fas fa-print"></i></a>
                                            <a href="#">إرسال <i class="fas fa-envelope"></i></a>
                                            <div class="share-container">
                                                <a href="#" class="share-btn">مشاركة <i class="fas fa-share-alt"></i> <i class="fas fa-chevron-down" style="font-size: 10px;"></i></a>
                                                <div class="share-dropdown">
                                                    <a href="https://www.facebook.com/sharer/sharer.php" target="_blank">Facebook <i class="fab fa-facebook-square"></i></a>
                                                    <a href="https://plus.google.com/share" target="_blank">Google <i class="fab fa-google-plus-square"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background: #fff; padding: 25px; border: 1px solid #ddd; line-height: 1.8;">
                                            <h3 style="color: #006b4d; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 0;">أهداف الوزارة</h3>
                                            <ul style="padding-right: 20px; list-style-type: square;">
                                                <li>تحقيق الأمن والاستقرار في جميع أنحاء المملكة.</li>
                                                <li>توفير أسباب الطمأنينة والأمان للمواطنين والمقيمين.</li>
                                                <li>مكافحة الجريمة بجميع أشكالها وأنواعها.</li>
                                                <li>حماية الأرواح والممتلكات العامة والخاصة.</li>
                                                <li>تعريف المواطنين بأهمية التقيد بالأنظمة والتعليمات.</li>
                                                <li>تطوير الخدمات المقدمة للجمهور عبر التحول الرقمي الشامل.</li>
                                            </ul>

                                            <h3 style="color: #006b4d; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 30px;">مهام الوزارة</h3>
                                            <ul style="padding-right: 20px; list-style-type: square;">
                                                <li>تنفيذ الأنظمة واللوائح والقرارات الصادرة من الجهات العليا.</li>
                                                <li>الإشراف على إمارات المناطق وتطوير أدائها.</li>
                                                <li>تنظيم شؤون الحج والزيارة وضمان أمن الحجاج والمعتمرين.</li>
                                                <li>إدارة شؤون الأحوال المدنية والجوازات.</li>
                                                <li>تنظيم الحركة المرورية والحد من حوادث الطرق.</li>
                                                <li>مكافحة المخدرات وحماية المجتمع من أخطارها.</li>
                                                <li>التعاون الأمني الدولي مع المنظمات والدول الصديقة.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <td valign="top" style="width: 300px;">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="right_content">
                                        <div class="news_box">
                                            <div class="news_box_header"><div class="news_box_heading pull-left">رؤية 2030</div></div>
                                            <div class="news_box_body" style="text-align: center;">
                                                <img src="<?php echo BASE_URL; ?>images/vision-2030-logo.png" alt="الرؤية" style="width: 150px; margin-bottom: 10px;">
                                                <p style="font-size: 13px;">تساهم الوزارة في تحقيق مستهدفات رؤية المملكة عبر مبادرات أمنية وخدمية طموحة.</p>
                                                <div class="more_link"><a href="<?php echo BASE_URL; ?>pages/about/vision.php">تفاصيل المبادرات</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>