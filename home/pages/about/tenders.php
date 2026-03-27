<?php
$page_title = "مناقصات و إعلانات";
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
                                        <div class="heading_featured">مناقصات وإعلانات</div>
                                        <div style="background: #fff; padding: 25px; border: 1px solid #ddd; direction: rtl; text-align: right; line-height: 1.8;">
                                            <div style="padding: 15px 0; border-bottom: 1px solid #eee; margin-bottom: 15px;">
                                                <h4 style="color: #006b4d; font-weight: bold; margin-bottom: 8px;">مناقصة توريد أنظمة أمنية متطورة</h4>
                                                <p style="font-size: 13px; color: #888; margin-bottom: 10px;">تاريخ الإغلاق: 30-10-1447</p>
                                                <a href="#" style="color: #006b4d; font-weight: bold; text-decoration: none; font-size: 14px;">تفاصيل المناقصة <i class="fas fa-arrow-left" style="font-size: 10px; margin-right: 5px;"></i></a>
                                            </div>
                                            <div style="padding: 15px 0;">
                                                <h4 style="color: #006b4d; font-weight: bold; margin-bottom: 8px;">إعلان عن وظائف تقنية مؤقتة</h4>
                                                <p style="font-size: 13px; color: #888; margin-bottom: 10px;">تاريخ الإغلاق: 15-11-1447</p>
                                                <a href="#" style="color: #006b4d; font-weight: bold; text-decoration: none; font-size: 14px;">تفاصيل الإعلان <i class="fas fa-arrow-left" style="font-size: 10px; margin-right: 5px;"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td valign="top" style="width: 300px;"><table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation"><tbody><tr><td style="width:100%;" valign="top"><div class="right_content"><img src="<?php echo BASE_URL; ?>images/banner.jpg" style="width:100%;" alt="مناقصات"></div></td></tr></tbody></table></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
