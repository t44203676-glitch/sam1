<?php
$page_title = "دليل النماذج";
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
                                        <div class="heading_featured">دليل النماذج الإلكترونية</div>
                                        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; margin-top: 20px;">
                                            <p>يمكنكم تحميل النماذج الرسمية لمختلف قطاعات الوزارة عبر الروابط التالية:</p>
                                            <ul>
                                                <li><a href="#" style="color: #00ab67;">نماذج الجوازات</a></li>
                                                <li><a href="#" style="color: #00ab67;">نماذج المرور</a></li>
                                                <li><a href="#" style="color: #00ab67;">نماذج الأحوال المدنية</a></li>
                                            </ul>
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