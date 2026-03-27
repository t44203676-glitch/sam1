<?php
$page_title = "الأخبار";
$active_page = "about";
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';

// Using the same dynamic data
$data = include '../../includes/data_index.php';
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
                    <!-- Modern News Content can go here, but restoring layout structure first -->
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="mid_content">
                                        <div class="heading_featured">أخبار الوزارة</div>
                                        <!-- Action Buttons -->
                                        <div class="action-buttons mb-3" style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; padding: 10px 0; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; justify-content: flex-start; direction: rtl; margin-bottom: 25px;">
                                            <a href="javascript:window.print()">طباعة <i class="fas fa-print"></i></a>
                                            <a href="#">إرسال <i class="fas fa-envelope"></i></a>
                                        </div>

                                        <!-- News List -->
                                        <div style="margin-top: 20px;">
                                            <?php foreach ($data['sidebar_news'] as $item): ?>
                                            <div style="display: flex; gap: 20px; border-bottom: 1px solid #f5f5f5; padding: 20px 0; align-items: flex-start;">
                                                <div style="flex-shrink: 0;">
                                                    <img src="<?php echo BASE_URL . $item['img']; ?>" alt="" style="width: 150px; height: 100px; object-fit: cover; border: 1px solid #eee;">
                                                </div>
                                                <div style="flex: 1;">
                                                    <h4 style="color: #4C4C4C; font-size: 18px; font-weight: bold; margin-bottom: 10px;"><?php echo $item['title']; ?></h4>
                                                    <div style="font-size: 13px; color: #999; margin-bottom: 15px;"><?php echo $item['date']; ?></div>
                                                    <p style="font-size: 14px; color: #666; line-height: 1.8;"><?php echo $item['desc']; ?></p>
                                                    <a href="#" style="color:#00ab67; font-weight: bold; text-decoration: none;">اقرأ المزيد</a>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
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