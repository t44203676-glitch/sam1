<?php
$page_title = "منصة منقولات";
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
                                        <h2 style="color: #4C4C4C; font-size: 24px; font-weight: bold; margin-bottom: 20px; text-align: right;">منصة منقولات الإلكترونية</h2>
                                        
                                        <!-- Action Buttons -->
                                        <div class="action-buttons mb-3" style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 10px 0; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; justify-content: flex-start; direction: rtl; margin-bottom: 25px;">
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
                                        <div style="background: #fff; padding: 25px; border: 1px solid #ddd; text-align: center;">
                                            <h2 style="color: #006b4d;">مرحباً بكم في منصة منقولات</h2>
                                            <p style="font-size: 16px; margin: 20px 0;">نظام إلكتروني موحد لإدارة وتتبع المنقولات اللوجستية والعهد الخاصة بقطاعات الوزارة.</p>
                                            <div style="background: #f9f9f9; border: 1px dashed #ccc; padding: 30px; margin: 30px auto; max-width: 400px;">
                                                <p>يتطلب الوصول لهذه المنصة تسجيل الدخول بشبكة الوزارة الداخلية.</p>
                                                <a href="#" style="background: #333; color: #fff; padding: 10px 25px; text-decoration: none; display: inline-block; margin-top: 10px; border-radius: 4px;">تسجيل الدخول للنظام</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" style="width: 300px;"><table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation"><tbody><tr><td style="width:100%;" valign="top"><div class="right_content"><div class="news_box"><div class="news_box_header" style="background: transparent; border-bottom: 2px solid #eee; padding-bottom: 5px;"><div class="news_box_heading pull-left" style="color: #4C4C4C; font-weight: bold; font-size: 14px;">تنبيهات المنظومة</div></div><div class="news_box_body"><p style="font-size: 12px; color: #666; padding: 15px 0; line-height: 1.6;">جميع العمليات في هذه المنصة خاضعة للرقابة والتدقيق الأمني الدوري.</p></div></div></div></td></tr></tbody></table></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>