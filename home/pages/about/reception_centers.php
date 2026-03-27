<?php
$page_title = "مراكز الاستقبال والتواصل الإلكتروني";
$active_page = "about";
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';
?>

<div class="container row">
    <table class="layoutRow ibmDndRow component-container" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr>
                <td valign="top" style="width: 180px;"><table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation"><tbody><tr><td style="width:100%;" valign="top"><?php include '../../includes/sidebar_about.php'; ?></td></tr></tbody></table></td>
                <td valign="top">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="mid_content">
                                        <div class="heading_featured">مراكز الاستقبال والتواصل الإلكتروني</div>
                                        
                                        <!-- Action Buttons -->
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
                                        <div style="background: #fff; padding: 20px; border: 1px solid #ddd;">
                                            <p>تهدف مراكز الاستقبال والتواصل الإلكتروني إلى تسهيل تواصل المواطنين والمقيمين مع قطاعات الوزارة المختلفة بكل يسر وسهولة.</p>
                                            <div style="margin-top: 20px;">
                                                <h4 style="color: #006b4d;">قنوات التواصل المتوفرة:</h4>
                                                <ul style="list-style: square; padding-right: 20px; line-height: 2;">
                                                    <li>بوابة الوزارة الإلكترونية (تواصل)</li>
                                                    <li>تطبيق "أبشر" للخدمات الإلكترونية</li>
                                                    <li>المراكز الحضورية في ديوان الوزارة وإمارات المناطق</li>
                                                    <li>مركز الاتصال الموحد للحالات الطارئة</li>
                                                </ul>
                                            </div>
                                            <img src="<?php echo BASE_URL; ?>images/banner.jpg" alt="تواصل" style="width: 100%; margin-top: 20px; border: 1px solid #eee;">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" style="width: 300px;"><table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation"><tbody><tr><td style="width:100%;" valign="top"><div class="right_content"><div class="news_box"><div class="news_box_header" style="background: transparent; border-bottom: 2px solid #eee; padding-bottom: 5px;"><div class="news_box_heading pull-left" style="color: #4C4C4C; font-weight: bold; font-size: 14px;">آخر الأخبار</div></div><div class="news_box_body" style="padding: 10px 0;"><div class="news_title"><a href="<?php echo BASE_URL; ?>pages/about/news.php" style="color: #00ab67; font-weight: bold; font-size: 13px; text-decoration: none;">الوزارة تطلق خدمة الاستفسار الموحدة</a></div><div class="news_date" style="font-size: 11px; color: #999; margin: 5px 0;">10 شعبان 1447</div><div class="news_excerpt" style="font-size: 12px; color: #444; line-height: 1.5;">تسهيلاً للمراجعين، أطلقت الوزارة نظام الاستفسار الشامل...</div><div class="more_link pull-right" style="margin-top: 10px;"><a href="<?php echo BASE_URL; ?>pages/about/news.php" style="color: #00ab67; font-size: 11px; font-weight: bold; text-decoration: none;">اقرأ المزيد</a></div></div></div></div></td></tr></tbody></table></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>