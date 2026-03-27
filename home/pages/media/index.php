<?php
$page_title = "المركز الإعلامي";
$active_page = "media";
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';
?>

<style>
    @media (max-width: 768px) {
        .container.row { display: block !important; padding: 10px !important; }
        .layoutRow { display: block !important; width: 100% !important; }
        .layoutRow > tbody > tr { display: flex; flex-direction: column; }
        .layoutRow > tbody > tr > td { display: block !important; width: 100% !important; padding: 10px 0 !important; }
        .sidebar_box { width: 100% !important; margin-bottom: 20px; }
        h1 { font-size: 20px !important; }
        .intro-text { font-size: 14px !important; line-height: 1.6 !important; }
    }
</style>
<div class="container row" style="direction: rtl;">
    <table class="layoutRow ibmDndRow component-container" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%;">
        <tr>
            <!-- Sidebar (Right in RTL) -->
                <?php include '../../includes/sidebar_media.php'; ?>

            <!-- Main Content (Left in RTL) -->
            <td valign="top" style="width: 100%;">
                <div style="direction: rtl; max-width: 720px;">

                    <!-- Header with title and action buttons -->
                    <div style="max-width: 680px; margin-bottom: 20px;">
                        <h1 style="color: #333; font-size: 24px; margin: 0 0 15px 0; font-family: 'Droid Arabic Kufi', Tahoma !important; font-weight: bold; text-align: right;">المركز الإعلامي</h1>

                        <div style="display: flex; gap: 20px; align-items: center; color: #00ab67; font-size: 14px; padding-bottom: 10px; border-bottom: 2px solid #ddd; justify-content: flex-end; direction: rtl;">
                            <a href="javascript:window.print()" style="color: #00ab67; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: bold;">
                                <i class="icon-print"></i> طباعة
                            </a>
                            <a href="#" style="color: #00ab67; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: bold;">
                                <i class="icon-mail"></i> إرسال
                            </a>
                            <a href="#" style="color: #00ab67; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: bold;">
                                <i class="icon-share"></i> مشاركة
                            </a>
                        </div>
                    </div>

                    <!-- Intro Text -->
                    <div style="margin-bottom: 30px; line-height: 1.9; color: #444; font-size: 15px; text-align: justify; direction: rtl; max-width: 680px;">
                        يسهم المركز الإعلامي في الإدارة العامة للإعلام والاتصال المؤسسي بوزارة الداخلية في إنتاج ونشر المواد الإعلامية عبر وسائل الإعلام والاتصال المختلفة: المرئية، والمسموعة، والمقروءة، وإبراز جهود الوزارة وإمارات المناطق والقطاعات الأمنية، ونشر الوعي الأمني المجتمعي، وتحقيق مفهوم الإعلام الأمني الشامل، وتعزيز التواصل بالمجتمعين الداخلي والخارجي.
                    </div>

                </div>
            </td>
        </tr>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
