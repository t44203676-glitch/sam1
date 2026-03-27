<?php
$page_title = "البريد الإلكتروني";
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
                                        <div class="heading_featured">بوابة البريد الإلكتروني لمنسوبي الوزارة</div>
                                        
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
                                        <div style="background: #fff; padding: 50px 20px; border: 1px solid #ddd; text-align: center;">
                                            <img src="<?php echo BASE_URL; ?>images/145-e-mail-Ar_ver3.jpg" alt="البريد" style="margin-bottom: 20px; border: 1px solid #eee;">
                                            <p style="font-size: 18px; margin-bottom: 30px;">اضغط على الرابط أدناه للانتقال إلى بوابة البريد الإلكتروني الرسمية:</p>
                                            <a href="https://mail.moi.gov.sa" target="_blank" style="background: #006b4d; color: #fff; padding: 15px 40px; text-decoration: none; font-weight: bold; font-size: 18px; border-radius: 5px;">الدخول إلى البريد الإلكتروني</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" style="width: 300px;"><table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation"><tbody><tr><td style="width:100%;" valign="top"><div class="right_content"><div class="news_box"><div class="news_box_header" style="background: transparent; border-bottom: 2px solid #eee; padding-bottom: 5px;"><div class="news_box_heading pull-left" style="color: #4C4C4C; font-weight: bold; font-size: 14px;">تعليمات الأمان</div></div><div class="news_box_body"><p style="font-size: 12px; color: #666; padding: 15px 0; line-height: 1.6;">يرجى عدم مشاركة كلمة المرور الخاصة بك مع أي شخص، وتغييرها دورياً لضمان أمان حسابك.</p></div></div></div></td></tr></tbody></table></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>