<?php
$page_title = "عنوان الوزارة";
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
                                        <div class="heading_featured">عنوان وزارة الداخلية</div>
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
                                        <div style="background: #fff; padding: 25px; border: 1px solid #ddd; line-height: 2;">
                                            <h3 style="color: #006b4d; border-bottom: 2px solid #eee; padding-bottom: 10px;">المقر الرئيسي - الرياض</h3>
                                            <p><strong>العنوان:</strong> طريق الملك فهد، حي المربع</p>
                                            <p><strong>المدينة:</strong> الرياض - المملكة العربية السعودية</p>
                                            <p><strong>صندوق البريد:</strong> 12345 الرياض 11431</p>
                                            <p><strong>الهاتف:</strong> 011 4011111</p>
                                            <p><strong>ساعات العمل:</strong> من الأحد إلى الخميس (8:00 صباحاً - 2:30 مساءً)</p>
                                            
                                            <div style="margin-top: 20px; border: 1px solid #ddd; padding: 10px;">
                                                <p style="text-align: center; color: #666;">[ خريطة الموقع التفاعلية ستظهر هنا ]</p>
                                                <img src="<?php echo BASE_URL; ?>images/banner.jpg" alt="خريطة الموقع" style="width: 100%; opacity: 0.7;">
                                            </div>
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
                                        <img src="<?php echo BASE_URL; ?>images/Caneras+banner11.png" alt="اتصل بنا" style="width: 100%;">
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