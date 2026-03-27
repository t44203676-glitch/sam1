<?php
$page_title = "عن الوزارة";
$active_page = "about";
include '../../includes/header.php';
include '../../includes/navigation.php';
$data = include '../../includes/data_index.php';
include '../../includes/breadcrumb.php';

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'index';
?>

<div class="container row">
    <table class="layoutRow ibmDndRow component-container" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%;">
        <tbody>
            <tr>
                <!-- Right Sidebar ( العمود الأول في RTL هو اليمين ) -->
                <td valign="top" style="width: 180px;">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <?php include '../../includes/sidebar_about.php'; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <!-- Main Content ( العمود الثاني هو الوسط/اليسار ) -->
                <td valign="top" style="padding-right: 20px;">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%;">
                        <tbody>
                            <tr>
                                <?php 
                                $tab_map = [
                                    'index'              => 'tabs/index.php',
                                    'about_info'         => 'tabs/about_info.php',
                                    'history'            => 'tabs/history.php',
                                    'goals'              => 'tabs/goals.php',
                                    'address'            => 'tabs/address.php',
                                    'organizational_structure' => 'tabs/organizational_structure.php',
                                    'news'               => 'tabs/news.php',
                                    'news_archive'       => 'tabs/news_archive.php',
                                    'statements'         => 'tabs/statements.php',
                                    'security_reports'   => 'tabs/security_reports.php',
                                    'forms'              => 'tabs/forms.php',
                                    'tenders'            => 'tabs/tenders.php',
                                    'regulations'        => 'tabs/regulations.php',
                                    'vision'             => 'tabs/vision.php',
                                    'transformation'     => 'tabs/transformation.php',
                                    'faqs'               => 'tabs/faqs.php',
                                    'contact'            => 'tabs/contact.php',
                                    'reception_centers'  => 'tabs/reception_centers.php',
                                    'manqoolat'          => 'tabs/manqoolat.php',
                                    'webmail'            => 'tabs/webmail.php',
                                    'help'               => 'tabs/help.php',
                                ];

                                if (isset($tab_map[$tab]) && file_exists($tab_map[$tab])) {
                                    include $tab_map[$tab];
                                } else {
                                    echo '<td valign="top"><div class="mid_content" style="padding: 20px;"><h3>قريباً...</h3><p>هذا القسم قيد الإنشاء وسيتم إضافته قريباً.</p></div></td>';
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
