<?php
$page_title = "اتصل بنا";
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
                                        <div class="heading_featured">اتصل بنا</div>
                                        <div style="background: #fff; padding: 30px; border: 1px solid #ddd; margin-top: 20px;">
                                            <h4 style="color: #006b4d; margin-bottom: 20px;">بيانات التواصل:</h4>
                                            <p><strong>العنوان:</strong> طريق الملك فهد، الرياض، المملكة العربية السعودية</p>
                                            <p><strong>الرقم الموحد:</strong> 920020405</p>
                                            <p><strong>البريد الإلكتروني:</strong> info@moi.gov.sa</p>
                                            <p><strong>ساعات العمل:</strong> من الأحد إلى الخميس، من 8:00 ص إلى 4:00 م</p>
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