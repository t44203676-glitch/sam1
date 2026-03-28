<?php
$page_title = "الرئيسية";
$active_page = "home";
include 'includes/header.php';
include 'includes/navigation.php';

// Initializing dynamic data
$index_data = include 'includes/data_index.php';
?>

<?php include 'includes/breadcrumb.php'; ?>

<div class="container row">
    <table class="layoutRow ibmDndRow component-container " cellpadding="0" cellspacing="0" role="presentation">
        <tbody>
            <tr>
                <td valign="top">
                    <table
                        class="layoutColumn ibmDndColumn component-container id-Z7_9QGCHH42KG1A60AI72O0I51GI1 layoutNode"
                        cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="component-control id-Z7_9QGCHH42KG1A60AI72O0I51GA4">
                                        <div class="container-wcm row">
                                            <style>
                                                .box_green a,
                                                .box_green a .sprite-boxes {
                                                    background-color: #00ab67 !important;
                                                    color: #ffffff !important;
                                                }

                                                .box_green a:hover,
                                                .box_green a:hover .sprite-boxes {
                                                    background-color: #008f56 !important;
                                                }

                                                .absher_outer_links {
                                                    font-size: 23px;
                                                    color: white;
                                                    top: 30px;
                                                    float: right;
                                                    position: absolute;
                                                    padding-right: 11px;
                                                    width: 70%;
                                                }

                                                .rtl .absher_outer_links {
                                                    width: 56%;
                                                }

                                                .rtl .box-2of4,
                                                .rtl .box-4of4 {
                                                    margin-left: 0px !important;
                                                    float: left;
                                                }

                                                .box-2of4,
                                                .box-4of4 {
                                                    margin-right: 0px !important;
                                                    float: right;
                                                }

                                                .banners {
                                                    height: 100% !important;
                                                }

                                                .banners a {
                                                    background: #ffffff !important;
                                                    height: 100% !important;
                                                }

                                                .separator {
                                                    border-top: 0px #ed008c solid;
                                                }

                                                .separator-first {
                                                    border-top: 9px #ed008c solid;
                                                    display: none;
                                                    clear: both;
                                                    width: 100%;
                                                }

                                                .box {
                                                    border: 0px solid #cdcdcd;
                                                }

                                                .rtl .box {
                                                    float: none;
                                                }

                                                .lnks_box_double {
                                                    font-weight: bold;
                                                    position: relative;
                                                    padding: 0;
                                                    margin: 0 0px;
                                                    border-radius: 5px;
                                                    text-align: center;
                                                    display: inline-block;
                                                    width: 100%;
                                                    height: 77px;
                                                    vertical-align: middle;
                                                }

                                                .lnks_box_double .box {
                                                    position: absolute;
                                                    padding: 0;
                                                }

                                                .lnks_box_double a strong {
                                                    display: table-cell;
                                                    vertical-align: bottom;
                                                    text-align: center;
                                                    padding: 5px;
                                                }

                                                .lnks_box_double a {
                                                    display: table;
                                                    height: 77px;
                                                    width: 100%;
                                                    overflow: hidden;
                                                    color: #6e6e6e;
                                                    border: 1px solid #e3e3e3;
                                                    border-bottom: 1px solid #9af185;
                                                    text-decoration: none;
                                                    transition: all ease-in-out .3s;
                                                    border-radius: 5px;
                                                    font-size: 12px;
                                                    box-shadow: 0 0 2px 0 #f1eded;
                                                }

                                                .lnks_box {
                                                    font-weight: bold;
                                                    position: relative;
                                                    padding: 0;
                                                    margin: 0 0px;
                                                    border-radius: 5px;
                                                    text-align: center;
                                                    display: inline-block;
                                                    width: 46%;
                                                    height: 150px;
                                                    vertical-align: middle;
                                                    margin-right: 11px;
                                                }

                                                .rtl .lnks_box {
                                                    margin-left: 11px;
                                                    margin-right: 0;
                                                }

                                                .lnks_box .box {
                                                    position: absolute;
                                                    padding: 0;
                                                }

                                                .lnks_box a strong {
                                                    display: table-cell;
                                                    vertical-align: bottom;
                                                    text-align: center;
                                                    padding: 5px;
                                                }

                                                .lnks_box_col2 a {
                                                    display: table;
                                                    height: 150px;
                                                    width: 100%;
                                                    overflow: hidden;
                                                    color: #6e6e6e;
                                                    border: 1px solid #e3e3e3;
                                                    border-bottom: 1px solid #9af185;
                                                    text-decoration: none;
                                                    transition: all ease-in-out .3s;
                                                    border-radius: 5px;
                                                    font-size: 12px;
                                                    box-shadow: 0 0 2px 0 #f1eded;
                                                    background: #eaeaea;
                                                }

                                                .lnks_box_col2 {
                                                    font-weight: bold;
                                                    position: relative;
                                                    padding: 0;
                                                    margin: 0 0px;
                                                    border-radius: 5px;
                                                    text-align: center;
                                                    display: inline-block;
                                                    width: 100%;
                                                    height: auto;
                                                    vertical-align: middle;
                                                    margin-left: 11px;
                                                }

                                                .lnks_box_col2 .box {
                                                    position: absolute;
                                                    padding: 0;
                                                }

                                                .sprite-boxes {
                                                    background: #eaeaea;
                                                    text-align: center;
                                                    display: flex;
                                                    flex-direction: column;
                                                    justify-content: center;
                                                    align-items: center;
                                                }

                                                .sprite-boxes:before {
                                                    content: '';
                                                    display: inline-block;
                                                    vertical-align: middle;
                                                    width: 100%;
                                                    height: 110px;
                                                    margin-top: auto;
                                                }

                                                .lnks_box .sprite-boxes {
                                                    display: table;
                                                    height: 150px;
                                                    width: 100%;
                                                    overflow: hidden;
                                                    color: #6e6e6e;
                                                    border: 1px solid #e3e3e3;
                                                    border-bottom: 1px solid #9af185;
                                                    text-decoration: none;
                                                    transition: all ease-in-out .3s;
                                                    border-radius: 5px;
                                                    font-size: 12px;
                                                    box-shadow: 0 0 2px 0 #f1eded;
                                                }

                                                .lnks_box .sprite-boxes:hover {
                                                    background: #00ab67;
                                                }

                                                .lnks_box .sprite-boxes:hover strong {
                                                    color: #ffffff !important;
                                                }

                                                .sprite-news:before {
                                                    background-image: url("images/News.png");
                                                    background-size: contain;
                                                    background-repeat: no-repeat;
                                                    background-position: center;
                                                    height: 90px;
                                                    width: 90px;
                                                }

                                                .sprite-news:hover:before {
                                                    background-image: url("images/News.png");
                                                }

                                                .sprite-photos:before {
                                                    background-image: url("images/Photo.png");
                                                    background-size: contain;
                                                    background-repeat: no-repeat;
                                                    background-position: center;
                                                    height: 90px;
                                                    width: 90px;
                                                }

                                                .sprite-photos:hover:before {
                                                    background-image: url("images/Photo.png");
                                                }

                                                .sprite-videos:before {
                                                    background-image: url("images/Video.png");
                                                    background-size: contain;
                                                    background-repeat: no-repeat;
                                                    background-position: center;
                                                    height: 90px;
                                                    width: 90px;
                                                }

                                                .sprite-videos:hover:before {
                                                    background-image: url("images/Video.png");
                                                }

                                                .sprite-statements:before {
                                                    background-image: url("images/Statements.png");
                                                    background-size: contain;
                                                    background-repeat: no-repeat;
                                                    background-position: center;
                                                    height: 90px;
                                                    width: 90px;
                                                }

                                                .sprite-statements:hover:before {
                                                    background-image: url("images/Statements.png");
                                                }

                                                .sprite-imp-links:before {
                                                    background-image: url("images/absher_emblem.png");
                                                    background-size: contain;
                                                    background-repeat: no-repeat;
                                                    background-position: center;
                                                    width: 40%;
                                                    height: 75px;
                                                    float: right;
                                                    border-radius: 4px;
                                                }

                                                .sprite-absher:before {
                                                    background-image: url("images/absher_emblem.png");
                                                    background-size: contain;
                                                    background-repeat: no-repeat;
                                                    background-position: center;
                                                    width: 40%;
                                                    height: 75px;
                                                    float: right;
                                                    border-radius: 4px;
                                                }

                                                .nohover a:hover {
                                                    background: #eaeaea !important;
                                                }
                                            </style>

                                            <div class="main pull-left">
                                                <div class="banner">
                                                    <div id="slides">
                                                        <?php foreach ($index_data['banners'] as $banner): ?>
                                                            <a href="<?php echo $banner['link'] ?? '#'; ?>"
                                                                target="<?php echo $banner['target'] ?? '_self'; ?>">
                                                                <img alt="Banner Image"
                                                                    src="<?php echo BASE_URL . $banner['src']; ?>"
                                                                    width="750" height="300" loading="eager">
                                                            </a>
                                                            <?php
                                                        endforeach; ?>
                                                    </div>
                                                </div>

                                                <div class="rowsec">
                                                    <?php
                                                    $boxes = $index_data['service_boxes'];
                                                    for ($i = 0; $i < count($boxes); $i += 2):
                                                        ?>
                                                        <div
                                                            class="service-group pull-left <?php echo ($i >= 2) ? 'last' : ''; ?>">
                                                            <div class="lnks_box">
                                                                <a href="<?php echo $boxes[$i]['link']; ?>"
                                                                    class="lnks_box_blk2">
                                                                    <div
                                                                        class="sprite-boxes <?php echo $boxes[$i]['sprite']; ?> box">
                                                                        <strong><?php echo $boxes[$i]['title']; ?></strong>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <?php if (isset($boxes[$i + 1])): ?>
                                                                <div
                                                                    class="lnks_box <?php echo $boxes[$i + 1]['class'] ?? ''; ?>">
                                                                    <a href="<?php echo $boxes[$i + 1]['link']; ?>"
                                                                        class="lnks_box_blk3">
                                                                        <div
                                                                            class="sprite-boxes <?php echo $boxes[$i + 1]['sprite']; ?> box">
                                                                            <strong><?php echo $boxes[$i + 1]['title']; ?></strong>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                                <?php
                                                            endif; ?>
                                                        </div>
                                                        <?php
                                                    endfor; ?>
                                                </div>

                                                <div class="rowsec">
                                                    <?php foreach ($index_data['mid_banners'] as $idx => $mb): ?>
                                                        <div
                                                            class="service-group <?php echo ($idx % 2 != 0) ? 'last' : ''; ?> pull-left">
                                                            <div class="lnks_box_double banners">
                                                                <a href="<?php echo $mb['link']; ?>"><img
                                                                        src="<?php echo BASE_URL . $mb['src']; ?>"
                                                                        alt="Mid Banner" width="365" height="77"
                                                                        loading="lazy"
                                                                        style="margin-top:11px; <?php echo ($idx % 2 != 0) ? 'max-width:100%; max-height:100%;' : ''; ?>"></a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    endforeach; ?>
                                                </div>

                                                <div class="rowsec">
                                                    <?php
                                                    $small = $index_data['small_banners'];
                                                    for ($i = 0; $i < count($small); $i += 2):
                                                        ?>
                                                        <div
                                                            class="service-group pull-left <?php echo ($i >= 2) ? 'last' : ''; ?>">
                                                            <div class="lnks_box nohover">
                                                                <a target="<?php echo $small[$i]['target'] ?? '_self'; ?>"
                                                                    href="<?php echo $small[$i]['link']; ?>"
                                                                    class="lnks_box_blk2"><img
                                                                        class="<?php echo $small[$i]['class'] ?? ''; ?>"
                                                                        src="<?php echo BASE_URL . $small[$i]['src']; ?>"
                                                                        alt="Service Icon" width="165" height="150"
                                                                        loading="lazy"></a>
                                                            </div>
                                                            <?php if (isset($small[$i + 1])): ?>
                                                                <div
                                                                    class="lnks_box nohover lnks_box <?php echo $small[$i + 1]['class'] ?? ''; ?>">
                                                                    <a target="<?php echo $small[$i + 1]['target'] ?? '_self'; ?>"
                                                                        href="<?php echo $small[$i + 1]['link']; ?>"
                                                                        class="lnks_box_blk2 faqs"><img
                                                                            class="<?php echo $small[$i + 1]['class'] ?? ''; ?>"
                                                                            src="<?php echo BASE_URL . $small[$i + 1]['src']; ?>"
                                                                            alt="Service Icon" width="165" height="150"
                                                                            loading="lazy"></a>
                                                                </div>
                                                                <?php
                                                            endif; ?>
                                                        </div>
                                                        <?php
                                                    endfor; ?>
                                                </div>

                                                <div class="rowsec">
                                                    <div class="service-group pull-left">
                                                        <div class="lnks_box_double box_green">
                                                            <a href="https://www.absher.sa/" target="_blank"
                                                                class="lnks_box_blk2">
                                                                <div class="sprite-boxes sprite-absher box"></div>
                                                                <div class="absher_outer_links">الانتقال الى ابشر</div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="service-group last pull-left">
                                                        <div class="lnks_box_double box_green">
                                                            <a href="https://www.absher.sa/" target="_blank"
                                                                class="lnks_box_blk4">
                                                                <div class="sprite-boxes sprite-imp-links box"></div>
                                                                <div class="absher_outer_links">روابط مهمة ابشر</div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <td valign="top">
                    <table
                        class="layoutColumn ibmDndColumn component-container id-Z7_9QGCHH42KG1A60AI72O0I51GI5 layoutNode"
                        cellpadding="0" cellspacing="0" role="presentation">
                        <tbody>
                            <tr>
                                <td style="width:100%;" valign="top">
                                    <div class="component-control id-Z7_9QGCHH42KG1A60AI72O0I51GQ4">
                                        <div class="container-wcm row">
                                            <style>
                                                .news-ticker li {
                                                    min-height: auto !important;
                                                }

                                                .news-ticker img {
                                                    max-width: 100%;
                                                    height: auto;
                                                }

                                                .events_box_body img {
                                                    margin-left: 10px;
                                                }
                                            </style>
                                            <!-- Auto-rotation is handled by js/auto-rotate.js -->
                                            <div class="sidebar pull-right">
                                                <div class="news-ticker" style="width: 260px;">
                                                    <ul>
                                                        <?php foreach ($index_data['sidebar_news'] as $idx => $news): ?>
                                                            <li id="<?php echo $news['id']; ?>"
                                                                style="<?php echo ($idx > 0) ? 'display:none;' : ''; ?>">
                                                                <a href="pages/about/news.php">
                                                                    <h3><?php echo $news['title']; ?></h3>
                                                                </a>
                                                                <span
                                                                    id="pdate_<?php echo $news['id']; ?>"><?php echo $news['date']; ?></span>
                                                                <img src="<?php echo BASE_URL . $news['img']; ?>"
                                                                    width="260" height="168"
                                                                    alt="<?php echo $news['title']; ?>" loading="lazy">
                                                                <p><?php echo $news['desc']; ?>
                                                                    <a class="more" href="pages/about/news.php">المزيد <span
                                                                            class="arrow"></span></a>
                                                                </p>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                <div class="controls">
                                                    <a href="javascript:;" class="control_prev pull-left">&lt;</a>
                                                    <span class="count pull-left"><span id="current">1</span> من <span
                                                            id="total"><?php echo count($index_data['sidebar_news']); ?></span></span>
                                                    <a href="javascript:;" class="control_next pull-left">&gt;</a>
                                                </div>

                                                <div class="events_box" style="margin-top: 5px;">
                                                    <div class="events_box_header">
                                                        <div class="events_box_heading pull-left">تصريحات المتحدث الأمني
                                                        </div>
                                                        <div class="events_box_link pull-right"><a
                                                                href="pages/about/news.php">استعراض الكل</a></div>
                                                        <?php foreach ($index_data['statements'] as $st): ?>
                                                            <div class="events_box_body">
                                                                <img src="<?php echo BASE_URL . $st['img']; ?>" width="80"
                                                                    height="51" align="right"
                                                                    alt="<?php echo $st['title']; ?>" loading="lazy">
                                                                <h4><?php echo $st['title']; ?></h4>
                                                                <div class="bodytext"><?php echo $st['date']; ?></div>
                                                                <p><?php echo $st['desc']; ?></p>
                                                                <div class="more_link pull-right"><a
                                                                        href="pages/about/news.php">المزيد...</a></div>
                                                            </div>
                                                            <?php
                                                        endforeach; ?>
                                                    </div>
                                                </div>

                                                <div class="news-list events_box" style="margin-top: 5px;">
                                                    <div class="events_box_header">
                                                        <div class="events_box_heading pull-left">فعاليات وأحداث</div>
                                                        <div class="events_box_link pull-right"><a
                                                                href="pages/about/news.php">استعراض الكل</a></div>
                                                    </div>
                                                    <?php foreach ($index_data['events'] as $idx => $ev): ?>
                                                        <div class="events_box_body"
                                                            style="<?php echo ($idx > 0) ? 'display:none;' : ''; ?>">
                                                            <img src="<?php echo BASE_URL . $ev['img']; ?>" width="80"
                                                                height="51" align="right" alt="<?php echo $ev['title']; ?>">
                                                            <h4><?php echo $ev['title']; ?></h4>
                                                            <div class="bodytext"><?php echo $ev['date']; ?></div>
                                                            <div class="more_link pull-right"><a
                                                                    href="pages/about/news.php">المزيد...</a></div>
                                                        </div>
                                                        <?php
                                                    endforeach; ?>
                                                </div>
                                                <div class="controls">
                                                    <a href="javascript:;"
                                                        class="control_prev_events pull-left">&lt;</a>
                                                    <span class="count pull-left"><span id="event_current">1</span> من
                                                        <span
                                                            id="event_total"><?php echo count($index_data['events']); ?></span></span>
                                                    <a href="javascript:;"
                                                        class="control_next_events pull-left">&gt;</a>
                                                </div>
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

<?php include 'includes/footer.php'; ?>