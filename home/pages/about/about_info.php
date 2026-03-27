<?php
$page_title = "عن الوزارة";
$active_page = "about";
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';
?>

<div class="container row">
    <table class="layoutRow ibmDndRow component-container" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr>
                <!-- Sidebar -->
                <td valign="top" style="width: 180px;">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <?php include '../../includes/sidebar_about.php'; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <!-- Main Content -->
                <td valign="top">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation" style="padding-left: 20px;">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="mid_content">
                                        
                                        <!-- Title -->
                                        <h1 style="color: #4C4C4C; font-size: 20px; font-weight: bold; margin-bottom: 15px; text-align: right;">معلومات عن الوزارة</h1>
                                        
                                        <!-- Actions Bar -->
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

                                        <!-- Links List -->
                                        <ul style="list-style-type: disc; padding-right: 20px; margin: 0; text-align: right; color: #4C4C4C;">
                                            <li style="margin-bottom: 10px;">
                                                <a href="<?php echo BASE_URL; ?>pages/about/history.php" style="color: #00ab67; text-decoration: none; font-size: 15px;">لمحة تاريخية</a>
                                            </li>
                                            <li style="margin-bottom: 10px;">
                                                <a href="<?php echo BASE_URL; ?>pages/about/goals.php" style="color: #00ab67; text-decoration: none; font-size: 15px;">أهداف ومهام الوزارة</a>
                                            </li>
                                            <li style="margin-bottom: 10px;">
                                                <a href="<?php echo BASE_URL; ?>pages/about/organizational_structure.php" style="color: #00ab67; text-decoration: none; font-size: 15px;">الهيكل التنظيمي</a>
                                            </li>
                                            <li style="margin-bottom: 10px;">
                                                <a href="<?php echo BASE_URL; ?>pages/about/address.php" style="color: #00ab67; text-decoration: none; font-size: 15px;">عنوان الوزارة</a>
                                            </li>
                                        </ul>

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
