<?php
$page_title = "لمحة تاريخية";
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
                                        <div class="heading_featured">لمحة تاريخية عن الوزارة</div>
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
                                        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; line-height: 1.8;">
                                            <p>تعد وزارة الداخلية في المملكة العربية السعودية من أقدم وأهم الوزارات، حيث مرت بعدة مراحل تطويرية منذ تأسيسها في عهد الملك عبدالعزيز آل سعود - طيب الله ثراه.</p>
                                            <p>بدأت الوزارة بمهام بسيطة تركزت على حفظ الأمن وتنظيم الشؤون الداخلية، ثم توسعت لتشمل قطاعات أمنية وخدمية متعددة تخدم المواطن والمقيم على حد سواء.</p>
                                            <img src="<?php echo BASE_URL; ?>images/MOI.jpg" alt="تاريخ الوزارة" style="width: 100%; margin: 15px 0; border: 1px solid #eee;">
                                            <p>اليوم، تتبنى الوزارة أحدث التقنيات العالمية في مجالات الأمن الوقائي والخدمات الإلكترونية الشاملة، مما جعلها نموذجاً يحتذى به في التحول الرقمي والأداء الأمني المتميز.</p>
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
                                            <div class="news_box_header"><div class="news_box_heading pull-left">آخر الأخبار</div></div>
                                            <div class="news_box_body">
                                                <div class="news_title"><a href="<?php echo BASE_URL; ?>pages/about/news.php">فيديو.. وزير الداخلية يدشن عدداً من الخدمات الإلكترونية</a></div>
                                                <div class="news_date">الأربعاء 9 شعبان 1447</div>
                                                <div class="news_excerpt">تدشين خدمات جديدة ضمن برنامج التحول الرقمي للوزارة...</div>
                                                <div class="more_link pull-right"><a href="<?php echo BASE_URL; ?>pages/about/news.php">اقرأ المزيد</a></div>
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