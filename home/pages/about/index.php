<?php
$page_title = "عن الوزارة";
$active_page = "about";
include '../../includes/header.php';
include '../../includes/navigation.php';

// Using the same dynamic data
$data = include '../../includes/data_index.php';
include '../../includes/breadcrumb.php';

?>

<div class="container row">
    <table class="layoutRow ibmDndRow component-container" cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr>
                <!-- العمود الأول: القائمة الجانبية (يمين) -->
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

                <!-- العمود الثاني: المحتوى الرئيسي (وسط) - السلايدر فقط -->
                <td valign="top">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation" style="padding-left: 10px;">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="mid_content">
                                        <div id="slider">
                                            <h3 style="color: #4C4C4C; font-size: 18px; font-weight: bold; margin-bottom: 15px; text-align: right; border-right: 4px solid #006b4d; padding-right: 10px;">أهم الأحداث</h3>
                                            <?php if (isset($data['sidebar_news'][0])):
    $feat = $data['sidebar_news'][0]; ?>
                                            <div class="container">
                                                <img src="<?php echo BASE_URL . $feat['img']; ?>" alt="News Image" style="width: 100%; border: 1px solid #ddd;">
                                                <div style="background: rgba(0,0,0,0.8); color: #fff; padding: 10px; margin-top: -5px;">
                                                    <span style="font-size:12px;color:#cccccc"><?php echo $feat['date']; ?></span> : 
                                                    <a href="<?php echo BASE_URL; ?>pages/about/news.php" style="color: #fff; font-weight: bold; text-decoration: none;">&nbsp;<?php echo $feat['title']; ?></a>
                                                    <br>
                                                    <a href="<?php echo BASE_URL; ?>pages/about/news.php" style="color: #8dbdd8; font-size: 11px;">اقرأ المزيد</a>
                                                </div>
                                            </div>
                                            <?php
endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <!-- العمود الثالث: (يسار) - مجمع فيه كل العناصر -->
                <td valign="top" style="width: 320px;">
                    <table class="layoutColumn ibmDndColumn component-container layoutNode" cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <!-- آخر الأخبار -->
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="right_content" style="margin-bottom: 20px;">
                                        <div class="news_box">
                                            <div class="news_box_header" style="background: transparent; border-bottom: 2px solid #ccc; padding-bottom: 5px;"><div class="news_box_heading pull-left" style="color: #4C4C4C; font-weight: bold; font-size: 14px;">آخر الأخبار</div></div>
                                            <?php if (isset($data['sidebar_news'][0])):
    $news = $data['sidebar_news'][0]; ?>
                                            <div class="news_box_body">
                                                <div class="news_title"><a href="<?php echo BASE_URL; ?>pages/about/news.php">&nbsp;<?php echo $news['title']; ?></a></div>
                                                <div class="news_date"><?php echo $news['date']; ?></div>
                                                <div class="news_excerpt"><?php echo mb_substr($news['desc'], 0, 100) . '...'; ?></div>
                                                <div class="more_link pull-right"><a href="<?php echo BASE_URL; ?>pages/about/news.php">اقرأ المزيد</a></div>
                                            </div>
                                            <?php
endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- تصريحات المتحدث الأمني (منقولة لليسار) -->
                            <tr>
                                <td valign="top">
                                    <div class="events_box" style="margin-bottom: 20px; background: #fff; border: 1px solid #ddd;">
                                        <div class="events_box_header" style="background: #fcfcfc; border-bottom: 1px solid #ccc; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center;">
                                            <div class="events_box_heading" style="color: #4C4C4C; font-weight: bold; font-size: 14px;">تصريحات المتحدث الأمني</div>
                                            <div class="events_box_link"><a href="<?php echo BASE_URL; ?>pages/about/statements.php" style="color: #006b4d; font-size: 12px; text-decoration: none;">استعراض الكل</a></div>
                                        </div>
                                        <?php if (isset($data['statements'][0])):
    $st = $data['statements'][0]; ?>
                                        <div class="events_box_body">
                                            <img src="<?php echo BASE_URL . $st['img']; ?>" alt="thumbnail" width="80" align="right" style="margin-left:5px; border: 1px solid #ddd;">
                                            <h4 style="margin: 0; font-size: 13px; font-weight: bold; color: #006b4d;"><?php echo $st['title']; ?></h4>
                                            <div class="bodytext" style="font-size: 11px; color: #999;"><?php echo $st['date']; ?></div>
                                            <p style="font-size: 12px; line-height: 1.4;"><?php echo mb_substr($st['desc'], 0, 100) . '...'; ?></p>
                                            <div class="more_link pull-right"><a href="<?php echo BASE_URL; ?>pages/about/statements.php">المزيد...</a></div>
                                        </div>
                                        <?php
endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- مركز الاستقبال (منقول لليسار) -->
                            <tr>
                                <td valign="top">
                                    <div class="right_content" style="margin-bottom: 20px;">
                                        <a href="<?php echo BASE_URL; ?>pages/about/reception_centers.php">
                                            <img src="<?php echo BASE_URL; ?>images/banner.jpg" alt="مركز الاستقبال" style="width: 100%; border: 1px solid #ddd;">
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- فعاليات وأحداث (منقولة لليسار) -->
                            <tr>
                                <td valign="top">
                                    <div class="events_box" style="background: #fff; border: 1px solid #ddd;">
                                        <div class="events_box_header" style="background: #fcfcfc; border-bottom: 1px solid #ccc; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center;">
                                            <div class="events_box_heading" style="color: #4C4C4C; font-weight: bold; font-size: 14px;">فعاليات وأحداث</div>
                                            <div class="events_box_link"><a href="#" style="color: #006b4d; font-size: 12px; text-decoration: none;">استعراض الكل</a></div>
                                        </div>
                                        <?php if (isset($data['events'][0])):
    $ev = $data['events'][0]; ?>
                                        <div class="events_box_body">
                                            <div style="display: flex; gap: 10px;">
                                                <img src="<?php echo BASE_URL . $ev['img']; ?>" alt="Event" width="60" height="60" style="border: 1px solid #ddd;">
                                                <div>
                                                    <h4 style="margin: 0; font-size: 12px; font-weight: bold;"><a href="#" style="color: #006b4d; text-decoration: none;"><?php echo $ev['title']; ?></a></h4>
                                                    <div class="bodytext" style="font-size: 11px; color: #999;"><?php echo $ev['date']; ?></div>
                                                </div>
                                            </div>
                                            <div class="more_link pull-right" style="margin-top: 10px;"><a href="#">المزيد...</a></div>
                                        </div>
                                        <?php
endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- بنرات إضافية -->
                            <tr>
                                <td valign="top">
                                    <div class="right_content" style="margin-top: 15px;">
                                        <img src="<?php echo BASE_URL; ?>images/Caneras+banner11.png" alt="911" style="width: 100%; border: 1px solid #ddd; margin-bottom: 10px;">
                                        <img src="<?php echo BASE_URL; ?>images/law-banner-ar.jpg" alt="Law" style="width: 100%; border: 1px solid #ddd;">
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