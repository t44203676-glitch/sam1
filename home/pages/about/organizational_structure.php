<?php
$page_title = "الهيكل التنظيمي";
$active_page = "about";
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';
?>

<div class="container row">
    <table class="layoutRow ibmDndRow component-container" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr>
                <!-- العمود الأول: القائمة الجانبية (يمين) -->
                <td valign="top" style="width: 180px;">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <?php include '../../includes/sidebar_about.php'; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <!-- العمود الثاني: المحتوى الرئيسي (وسط) -->
                <td valign="top">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="mid_content">
                                        <div class="heading_featured">الهيكل التنظيمي</div>
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
                                        <div style="background: #fff; padding: 15px; border: 1px solid #ddd; text-align: center;">
                                            <p style="margin-bottom: 15px; font-weight: bold;">يمكنك عرض أو تحميل الهيكل التنظيمي للوزارة من خلال الملف أدناه:</p>
                                            
                                            <!-- عرض الـ PDF في إطار -->
                                            <div style="width: 100%; height: 600px; border: 1px solid #ccc; margin-bottom: 15px;">
                                                <iframe src="<?php echo BASE_URL; ?>images/chart.pdf" width="100%" height="100%" style="border: none;"></iframe>
                                            </div>

                                            <div class="more_link">
                                                <a href="<?php echo BASE_URL; ?>images/chart.pdf" target="_blank" style="background: #006b4d; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                                                    <i class="fa fa-download"></i> تحميل الهيكل التنظيمي (PDF)
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <!-- العمود الثالث: آخر الأخبار (يسار) -->
                <td valign="top" style="width: 300px;">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="right_content">
                                        <div class="news_box">
                                            <div class="news_box_header">
                                                <div class="news_box_heading pull-left">آخر الأخبار</div>
                                            </div>
                                            <div class="news_box_body">
                                                <div class="news_title"><a href="<?php echo BASE_URL; ?>pages/about/news.php">وكيل وزارة الداخلية يرأس اجتماع وكلاء إمارات المناطق الـ(60)</a></div>
                                                <div class="news_date">الخميس 10 شعبان 1447</div>
                                                <div class="news_excerpt">
                                                    رأس معالي وكيل وزارة الداخلية الدكتور خالد بن محمد البتال، اليوم، اجتماع وكلاء إمارات المناطق الـ(60)...
                                                </div>
                                                <div class="more_link pull-right"><a href="<?php echo BASE_URL; ?>pages/about/news.php">اقرأ المزيد</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="right_content" style="margin-top: 20px;">
                                        <img src="<?php echo BASE_URL; ?>images/Caneras+banner11.png" alt="911" style="width: 100%;">
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