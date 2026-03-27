<?php
$page_title = "الأسئلة الشائعة";
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
                                        <div class="heading_featured">الأسئلة الشائعة</div>
                                        <div class="faq-container" style="margin-top: 20px;">
                                            <div class="faq-item" style="border-bottom: 1px solid #eee; padding: 15px 0;">
                                                <h4 style="color: #4C4C4C; cursor: pointer;">ما هي أبرز الخدمات الإلكترونية التي تقدمها الوزارة؟</h4>
                                                <p style="color: #666; margin-top: 10px;">تقدم الوزارة في بوابتها الإلكترونية العديد من الخدمات مثل أبشر، الجوازات، المرور، والأحوال المدنية.</p>
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