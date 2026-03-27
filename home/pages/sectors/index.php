<?php
$page_title = "القطاعات";
$active_page = "sectors";
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';
?>

<style>
    :root {
        --main-green: #009b5a;
        --text-color: #333;
        --light-gray: #f5f5f5;
        --border-color: #ddd;
    }

    .sectors-container-wrapper {
        display: flex;
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        gap: 30px;
    }

    /* Main Content (Right Side) */
    .sectors-main-content {
        flex: 2;
    }

    .page-header h1 {
        color: var(--main-green);
        font-size: 24px;
        margin-bottom: 10px;
        margin-top: 0;
    }

    .page-intro {
        font-size: 14px;
        color: #555;
        margin-bottom: 25px;
        text-align: justify;
    }

    .sectors-header {
        background-color: var(--main-green);
        color: white;
        padding: 10px 15px;
        font-size: 18px;
        font-weight: bold;
        width: 100%;
        margin-bottom: 15px;
    }

    .sectors-container {
        display: flex;
        gap: 20px;
    }

    .sectors-col {
        flex: 1;
        list-style: none;
        padding: 0;
    }

    .sectors-col li {
        width: 100%;
        padding: 8px 10px;
        font-size: 14px;
        font-weight: bold;
        color: #333;
        display: flex;
        align-items: center;
        border-bottom: 1px dotted #ccc;
    }

    .sectors-col li::before {
        content: '';
        display: inline-block;
        width: 0;
        height: 0;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        border-right: 5px solid #333;
        margin-left: 10px;
    }

    .sectors-col li a {
        text-decoration: none;
        color: #333;
        transition: color 0.2s;
    }

    .sectors-col li a:hover {
        color: var(--main-green);
    }

    /* Sidebar (Left Side) */
    .sectors-sidebar {
        flex: 1;
        max-width: 350px;
    }

    .news-widget {
        margin-bottom: 30px;
    }

    .news-title {
        color: var(--main-green);
        font-size: 16px;
        font-weight: bold;
        line-height: 1.4;
        margin-bottom: 5px;
    }

    .news-date {
        font-size: 12px;
        color: #777;
        margin-bottom: 10px;
        display: block;
    }

    .news-image {
        width: 100%;
        height: auto;
        border-radius: 4px;
        margin-bottom: 10px;
        display: block;
    }

    .news-excerpt {
        font-size: 13px;
        color: #444;
        margin-bottom: 10px;
        line-height: 1.5;
    }

    .more-link {
        color: var(--main-green);
        font-size: 13px;
        font-weight: bold;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        float: left;
    }

    .more-link::before {
        content: '◄';
        font-size: 10px;
        margin-right: 5px;
    }

    .events-widget {
        border-top: 1px solid #eee;
        padding-top: 15px;
    }

    .events-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #ccc;
        margin-bottom: 15px;
        padding-bottom: 5px;
    }

    .events-header-row h3 {
        font-size: 16px;
        color: #333;
        position: relative;
    }

    .events-view-all {
        font-size: 12px;
        color: var(--main-green);
        text-decoration: none;
    }

    .event-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }

    .event-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .event-details {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .event-details h4 {
        font-size: 14px;
        margin-bottom: 8px;
        font-weight: bold;
        line-height: 1.6;
        color: #333;
    }

    .event-date {
        font-size: 11px;
        color: #1aa34a;
        display: block;
    }

    .slider-nav {
        margin-top: 10px;
        display: flex;
        gap: 5px;
        justify-content: flex-start;
    }

    .nav-arrow {
        background: #ccc;
        color: white;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
    }

    .nav-stats {
        font-size: 12px;
        color: #777;
        margin-left: 10px;
    }

    /* News Details View */
    #news-details-view {
        display: none;
    }

    #news-details-view h2 {
        color: #000;
        font-size: 22px;
        margin-bottom: 5px;
    }

    .detail-meta {
        font-size: 13px;
        color: #777;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        display: flex;
        justify-content: space-between;
    }

    .detail-meta #detail-date {
        color: #000;
        font-weight: bold;
    }

    .detail-tools {
        color: var(--main-green);
        font-weight: bold;
    }

    .detail-content-area {
        overflow: hidden;
    }

    #detail-image {
        width: 45%;
        float: left;
        margin-right: 20px;
        margin-bottom: 10px;
        border-radius: 4px;
    }

    #detail-body {
        line-height: 1.8;
        color: #333;
        font-size: 15px;
        text-align: justify;
    }

    .last-update {
        margin-top: 30px;
        font-size: 12px;
        color: #999;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }

    .page-header h1 {
        cursor: pointer;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .sectors-container-wrapper {
            flex-direction: column;
        }

        .sectors-main-content,
        .sectors-sidebar {
            flex: auto;
            max-width: 100%;
        }
    }

    /* MOI Diwan Section Styles */
    .moidiwan-container {
        display: flex;
        gap: 20px;
    }

    .navleft_sections {
        width: 250px;
        flex-shrink: 0;
    }

    .heading_sections {
        background-color: #009b5a;
        color: white;
        padding: 10px 15px;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .navleft {
        width: 100%;
        margin-bottom: 2px;
    }

    .navleft1_link {
        display: block;
        background-color: #f5f5f5;
        border-right: 3px solid #009b5a;
    }

    .navleft1_link a {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.2s;
    }

    .navleft1_link a:hover {
        background-color: #e8e8e8;
    }

    .mid_content {
        flex: 1;
    }

    .heading_featured {
        background-color: #009b5a;
        color: white;
        padding: 10px 15px;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 0;
    }

    .slider-container {
        background: #fff;
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }

    .slider-image {
        width: 100%;
        height: auto;
        display: block;
    }

    .img_dsc {
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 15px;
        font-size: 14px;
    }

    .img_dsc a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    .slider-readmore a {
        color: #009b5a;
        text-decoration: none;
        font-weight: bold;
    }

    .jqImageLinks {
        list-style: none;
        padding: 10px;
        margin: 0;
        text-align: center;
        background: #f5f5f5;
    }

    .jqImageLinks li {
        display: inline-block;
        margin: 0 5px;
    }

    .jqImageLinks a {
        display: block;
        width: 30px;
        height: 30px;
        line-height: 30px;
        background: #ccc;
        color: white;
        text-decoration: none;
        border-radius: 3px;
    }

    .jqImageLinks a.selected {
        background: #009b5a;
    }

    .right_content {
        width: 300px;
        flex-shrink: 0;
    }

    .news_box {
        background: #fff;
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }

    .news_box_header {
        background: #009b5a;
        color: white;
        padding: 10px 15px;
    }

    .news_box_heading {
        font-size: 16px;
        font-weight: bold;
    }

    .news_box_body {
        padding: 15px;
    }

    .news_box .news_title {
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .news_box .news_title a {
        color: #333;
        text-decoration: none;
    }

    .news_box .news_date {
        font-size: 12px;
        color: #777;
        margin-bottom: 10px;
    }

    .news_box .news_excerpt {
        font-size: 13px;
        color: #555;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .news_box .more_link {
        text-align: left;
    }

    .news_box .more_link a {
        color: #009b5a;
        text-decoration: none;
        font-weight: bold;
        font-size: 13px;
    }

    .events_box {
        background: #fff;
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }

    .events_box_header {
        background: #009b5a;
        color: white;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .events_box_heading {
        font-size: 16px;
        font-weight: bold;
    }

    .events_box_link a {
        color: white;
        text-decoration: none;
        font-size: 13px;
    }

    .events_box_body {
        padding: 15px;
    }

    .grid-col-container {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .grid-col-container:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .grid-col1-3 img {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 4px;
    }

    .grid-col2-3 {
        flex: 1;
    }

    .grid-col2-3 h4 {
        font-size: 14px;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .grid-col2-3 h4 a {
        color: #333;
        text-decoration: none;
    }

    .bodytext {
        font-size: 12px;
        color: #777;
    }


</style>


<div class="sectors-container-wrapper">
    <!-- Right Column: Main Content -->
    <main class="sectors-main-content">
        <div id="sectors-view">
            <div class="page-header">
                <h1>القطاعات</h1>
            </div>
            <p class="page-intro">
                تضم بوابة وزارة الداخلية على شبكة الإنترنت مواقع لمجموعة من القطاعات التي لديها اتصال مع الجمهور، حيث
                يمكن لزوار الموقع الاطلاع على أخبار القطاع، والهيكل التنظيمي، وكذلك لمحة تاريخية عنه، بالإضافة إلى
                الخدمات المعلوماتية والإعلانات وغيرها من المعلومات المختلفة التي يرغب القطاع في تقديمها للجمهور على شبكة
                الإنترنت
            </p>

            <div class="sectors-header">
                قائمة القطاعات
            </div>

            <div class="sectors-container">
                <!-- Right Column -->
                <ul class="sectors-col">
                    <li><a href="#" onclick="showMoidiwan(); return false;">ديوان وزارة الداخلية</a></li>
                    <li><a href="#">وكالة الوزارة لشؤون المناطق</a></li>
                    <li><a href="#">وكالة وزارة الداخلية للشؤون العسكرية</a></li>
                    <li><a href="#">وكالة وزارة الداخلية للقدرات الأمنية</a></li>
                    <li><a href="#">الإدارة العامة لشؤون الوافدين</a></li>
                    <li><a href="#">الإدارة العامة للشرطة الدولية</a></li>
                    <li><a href="#">الإدارة العامة للأسلحة والمتفجرات</a></li>
                    <li><a href="#">المديرية العامة للأمن العام</a></li>
                    <li><a href="#">الإدارة العامة للمرور</a></li>
                    <li><a href="#">القوات الخاصة لأمن الطرق</a></li>
                    <li><a href="#">القوات الخاصة للأمن البيئي</a></li>
                    <li><a href="#">المديرية العامة للدفاع المدني</a></li>
                </ul>

                <!-- Left Column -->
                <ul class="sectors-col">
                    <li><a href="#">المديرية العامة للجوازات</a></li>
                    <li><a href="#">وكالة وزارة الداخلية للأحوال المدنية</a></li>
                    <li><a href="#">كلية الملك فهد الأمنية</a></li>
                    <li><a href="#">المديرية العامة للسجون</a></li>
                    <li><a href="#">مركز المعلومات الوطني</a></li>
                    <li><a href="#">المركز الوطني للعمليات الأمنية</a></li>
                    <li><a href="#">مركز أبحاث مكافحة الجريمة</a></li>
                    <li><a href="#">قوات أمن المنشآت</a></li>
                    <li><a href="#">المديرية العامة لمكافحة المخدرات</a></li>
                    <li><a href="#">المديرية العامة لحرس الحدود</a></li>
                    <li><a href="#">الإدارة العامة للمجاهدين</a></li>
                    <li><a href="#">الإدارة العامة للخدمات الطبية</a></li>
                    <li><a href="#">الإدارة العامة لأندية منسوبي وزارة الداخلية</a></li>
                    <li><a href="#">القوات الخاصة للأمن والحماية</a></li>
                </ul>
            </div>
        </div>

        <!-- News Details View (Hidden by default) -->
        <div id="news-details-view">
            <div class="page-header">
                <h1 onclick="showSectors()">القطاعات</h1>
            </div>

            <h2 id="detail-title"></h2>
            <div class="detail-meta">
                <span id="detail-date"></span>
                <span class="detail-tools">مشاركة | إرسال | طباعة</span>
            </div>

            <div class="detail-content-area">
                <img id="detail-image" src="" alt="صورة الخبر">
                <div id="detail-body"></div>
            </div>

            <div class="last-update">
                آخر تحديث: <span id="detail-update"></span>
            </div>
        </div>
    </main>

    <!-- Left Column: Sidebar -->
    <aside class="sectors-sidebar">
        <!-- News Widget -->
        <div class="news-widget">
            <h2 class="news-title" id="news-title">وكيل وزارة الداخلية يرأس اجتماع وكلاء إمارات المناطق الـ(60)</h2>
            <span class="news-date" id="news-date">الخميس 10 شعبان 1447</span>

            <img src="<?php echo BASE_URL; ?>images/MOI.jpg" alt="صورة الخبر" class="news-image" id="news-image">

            <p class="news-excerpt" id="news-excerpt">
                رأس معالي وكيل وزارة الداخلية الدكتور خالد بن محمد البتال، اليوم، اجتماع وكلاء إمارات المناطق الـ(60)، الذي عقد بمقر ديوان الوزارة بمدينة الرياض.
            </p>

            <a href="#" class="more-link">المزيد</a>
            <div style="clear:both;"></div>

            <div class="slider-nav">
                <div class="nav-arrow" id="prev-btn" style="border-radius: 0 3px 3px 0;">&#10094;</div>
                <div class="nav-arrow" id="next-btn" style="border-radius: 3px 0 0 3px;">&#10095;</div>
                <div class="nav-stats" id="nav-stats">1 من 3</div>
            </div>
        </div>

        <!-- Events Widget -->
        <div class="events-widget">
            <div class="events-header-row">
                <h3>فعاليات وأحداث</h3>
                <a href="#" class="events-view-all">استعراض الكل</a>
            </div>

            <div class="event-item">
                <img src="<?php echo BASE_URL; ?>images/99.jpg" alt="صورة مصغرة" class="event-thumb">
                <div class="event-details">
                    <h4>معالي رئيس المنظمة الدولية للشرطة الجنائية "الإنتربول" يقلد معالي الأمين العام لمجلس وزراء الداخلية العرب وسام المنظمة من الطبقة الخاصة</h4>
                    <span class="event-date">الأربعاء 21 جمادى الأول 1447</span>
                </div>
            </div>
        </div>
    </aside>
</div>

<!-- MOI Diwan Section (Hidden by default) -->
<div class="sectors-container-wrapper moidiwan-container" id="moidiwan-view" style="display: none;">
    <!-- القائمة الجانبية اليمنى -->
    <div class="navleft_sections">
        <table dir="RTL">
            <tr><td><div class="heading_sections">&nbsp;الأقسام</div></td></tr>
            <tr><td>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#" onclick="showSectors(); return false;">&nbsp;← العودة للقطاعات</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;الرئيسية</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;عن الوزارة</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;الأخبار</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;بيانات المتحدث الأمني</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;البلاغات الأمنية</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;النماذج الإلكترونية</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;مناقصات و إعلانات</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;أنظمة وتعليمات</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;مكتب تحقيق الرؤية</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;برنامج تطوير الوزارة</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;الأسئلة الشائعة</a></span>
                    </td></tr>
                </table>
                <table class="navleft">
                    <tr><td>
                        <span class="navleft1_link"><a href="#">&nbsp;اتصل بنا</a></span>
                    </td></tr>
                </table>
            </td></tr>
        </table>
    </div>

    <!-- المحتوى الأوسط -->
    <div class="mid_content">
        <div class="slider-container">
            <div class="heading_featured">أهم الأحداث</div>
            
            <div id="slider-content">
                <img src="<?php echo BASE_URL; ?>images/MOI.jpg" alt="أهم الأحداث" class="slider-image">
                <div class="img_dsc">
                    <span style="font-size:12px;color:#cccccc">الخميس 10 شعبان 1447</span> : 
                    <a href="#">وكيل وزارة الداخلية يرأس اجتماع وكلاء إمارات المناطق الـ(60)</a><br>
                    <span class="slider-readmore">
                        <a href="#">اقرأ المزيد</a>
                    </span>
                </div>
            </div>

            <ul class="jqImageLinks">
                <li><a href="#" class="selected">١</a></li>
                <li><a href="#">٢</a></li>
                <li><a href="#">٣</a></li>
            </ul>
        </div>
    </div>

    <!-- القائمة الجانبية اليسرى -->
    <div class="right_content">
        <!-- آخر الأخبار -->
        <div class="news_box">
            <div class="news_box_header">
                <div class="news_box_heading pull-left">آخر الأخبار</div>
            </div>
            <div class="news_box_body">
                <div class="news_title">
                    <a href="#">القوة الأمنية السعودية تختتم مشاركتها في التمرين التعبوي "أمن الخليج العربي 4" بدولة قطر</a>
                </div>
                <div class="news_date">الثلاثاء 4 فبراير 2026</div>
                <div class="news_excerpt">
                    أنهت القوة الأمنية السعودية، اليوم، مهمتها في التمرين التعبوي المشترك للأجهزة الأمنية بدول مجلس التعاون لدول الخليج العربية "أمن الخليج العربي 4"
                </div>
                <div class="more_link pull-right">
                    <a href="#">اقرأ المزيد</a>
                </div>
            </div>
        </div>

        <!-- فعاليات وأحداث -->
        <div class="events_box">
            <div class="events_box_header">
                <div class="events_box_heading pull-left">فعاليات وأحداث</div>
                <div class="events_box_link pull-right">
                    <a href="#">استعراض الكل</a>
                </div>
            </div>
            <div class="events_box_body">
                <div class="grid-col-container">
                    <div class="grid-col1-3">
                        <img src="<?php echo BASE_URL; ?>images/99.jpg" alt="Event Thumbnail">
                    </div>
                    <div class="grid-col2-3">
                        <h4>
                            <a href="#">معالي رئيس المنظمة الدولية للشرطة الجنائية "الإنتربول" يقلد معالي الأمين العام لمجلس وزراء الداخلية العرب وسام المنظمة من الطبقة الخاصة</a>
                        </h4>
                        <div class="bodytext">الأربعاء 12 نوفمبر 2025</div>
                    </div>
                </div>
                <div class="more_link pull-right">
                    <a href="#">المزيد...</a>
                </div>

                <div class="grid-col-container" style="margin-top: 15px;">
                    <div class="grid-col1-3">
                        <img src="<?php echo BASE_URL; ?>images/MOI.jpg" alt="Event Thumbnail">
                    </div>
                    <div class="grid-col2-3">
                        <h4>
                            <a href="#">وزارة الداخلية تشارك في المعرض المصاحب لأعمال ملتقى إعلام الحج</a>
                        </h4>
                        <div class="bodytext">الاثنين 2 يونيو 2025</div>
                    </div>
                </div>
                <div class="more_link pull-right">
                    <a href="#">المزيد...</a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    const newsData = [
        {
            title: 'وكيل وزارة الداخلية يرأس اجتماع وكلاء إمارات المناطق الـ(60)',
            date: 'الخميس 10 شعبان 1447',
            image: BASE_URL + 'images/MOI.jpg',
            excerpt: 'رأس معالي وكيل وزارة الداخلية الدكتور خالد بن محمد البتال، اليوم، اجتماع وكلاء إمارات المناطق الـ(60)، الذي عقد بمقر ديوان الوزارة بمدينة الرياض.',
            fullContent: `
                <p>رأس معالي وكيل وزارة الداخلية الدكتور خالد بن محمد البتال، اليوم، اجتماع وكلاء إمارات المناطق الـ(60)، الذي عقد بمقر ديوان الوزارة بمدينة الرياض.</p>
                <p>ونقل معاليه خلال الاجتماع للمشاركين تحيّات صاحب السمو الملكي الأمير عبدالعزيز بن سعود بن نايف بن عبدالعزيز وزير الداخلية، وحرص سموه على أن تُحقق هذه الاجتماعات الغاية المرجوة منها.</p>
                <p>وجرى استعراض ومناقشة الموضوعات المدرجة على جدول أعمال الاجتماع القادم لأصحاب السمو أمراء المناطق السنوي الـ(33) الذي سينعقد برئاسة سمو وزير الداخلية.</p>
            `,
            lastUpdate: 'الأحد 13 شعبان 1447'
        },
        {
            title: 'عقد الاجتماع الثالث لمجموعة عمل الأمن المنبثقة عن لجنة التعاون السياسي والقنصلي والأمني السعودي الهندي',
            date: 'الخميس 10 شعبان 1447',
            image: BASE_URL + 'images/99.jpg',
            excerpt: 'رأس مدير عام الشؤون القانونية والتعاون الدولي بوزارة الداخلية الأستاذ أحمد بن سليمان العيسى، والمدير العام لشؤون مكافحة الإرهاب بوزارة الشؤون الخارجية الهندية الدكتور فينود جاناردهان بهادي.',
            fullContent: `
                <p>رأس مدير عام الشؤون القانونية والتعاون الدولي بوزارة الداخلية الأستاذ أحمد بن سليمان العيسى، والمدير العام لشؤون مكافحة الإرهاب بوزارة الشؤون الخارجية الهندية الدكتور فينود جاناردهان بهادي، اليوم، الاجتماع الثالث لمجموعة عمل الأمن المنبثقة عن لجنة التعاون السياسي والقنصلي والأمني في مجلس الشراكة الإستراتيجية السعودي الهندي.</p>
                <p>وجرى خلال الاجتماع، مناقشة مجالات التعاون المشتركة بين الجانبين، وسبل تعزيزها بما يخدم مصالح البلدين الصديقين، واستعراض المبادرات ووضع الآليات المناسبة لإنجازها.</p>
            `,
            lastUpdate: 'الأحد 13 شعبان 1447'
        },
        {
            title: 'الحملات الميدانية المشتركة تضبط (18200) مخالف لأنظمة الإقامة والعمل وأمن الحدود في مناطق المملكة خلال أسبوع',
            date: 'السبت 5 شعبان 1447',
            image: BASE_URL + 'images/news3.jpg',
            excerpt: 'أسفرت الحملات الميدانية المشتركة لمتابعة وضبط مخالفي أنظمة الإقامة والعمل وأمن الحدود، التي تمت في مناطق المملكة كافة، وذلك للفترة من 26 / 7 حتى 2 / 8 / 1447هـ الموافق 15 حتى 21 / 1 / 2026م عن النتائج التالية.',
            fullContent: `
                <p>أسفرت الحملات الميدانية المشتركة لمتابعة وضبط مخالفي أنظمة الإقامة والعمل وأمن الحدود، التي تمت في مناطق المملكة كافة، وذلك للفترة من 26 / 7 حتى 2 / 8 / 1447هـ الموافق 15 حتى 21 / 1 / 2026م عن النتائج التالية:</p>
                <p>أولاً: بلغ إجمالي المخالفين الذين تم ضبطهم بالحملات الميدانية الأمنية المشتركة في مناطق المملكة كافة (18200) مخالف، منهم (11442) مخالفًا لنظام الإقامة، و(3931) مخالفًا لنظام أمن الحدود، و(2827) مخالفًا لنظام العمل.</p>
                <p>ثانيًا: بلغ إجمالي من تم ضبطهم خلال محاولتهم عبور الحدود إلى داخل المملكة (1762) شخصًا (46%) منهم يمنيو الجنسية، و(53%) إثيوبيو الجنسية، وجنسيات أخرى (01%)، كما تم ضبط (46) شخصًا لمحاولتهم عبور الحدود إلى خارج المملكة بطريقة غير نظامية.</p>
                <p>ثالثًا: تم ضبط (11) متورطـًا في نقل وإيواء وتشغيل مخالفي أنظمة الإقامة والعمل وأمن الحدود والتستر عليهم.</p>
                <p>رابعًا: بلغ إجمالي من يتم إخضاعهم حاليًا لإجراءات تنفيذ الأنظمة (25477) وافدًا مخالفًا، منهم (23443) رجلًا، و(2034) امرأة.</p>
                <p>خامسًا: تم إحالة (18685) مخالفًا لبعثاتهم الدبلوماسية للحصول على وثائق سفر، وإحالة (3011) مخالفًا لاستكمال حجوزات سفرهم، وترحيل (14451) مخالفًا.</p>
                <p>وأكدت وزارة الداخلية أن كل من يسهل دخول مخالفي نظام أمن الحدود للمملكة أو نقلهم داخلها أو يوفر لهم المأوى أو يقدم لهم أي مساعدة أو خدمة بأي شكل من الأشكال، يعرض نفسه لعقوبات تصل إلى السجن مدة 15 سنة، وغرامة مالية تصل إلى مليون ريال، ومصادرة وسيلة النقل والسكن المستخدم للإيواء، إضافة إلى التشهير به، وأوضحت أن هذه الجريمة تعد من الجرائم الكبيرة الموجبة للتوقيف، والمخلة بالشرف والأمانة، حاثة على الإبلاغ عن أي حالات مخالفة على الرقم (911) بمناطق مكة المكرمة والمدينة المنورة والرياض والشرقية، و(999) و(996) في بقية مناطق المملكة.</p>
            `,
            lastUpdate: 'السبت 5 شعبان 1447'
        }
    ];

    let currentIndex = 0;

    const titleEl = document.getElementById('news-title');
    const dateEl = document.getElementById('news-date');
    const imageEl = document.getElementById('news-image');
    const excerptEl = document.getElementById('news-excerpt');
    const statsEl = document.getElementById('nav-stats');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const moreLink = document.querySelector('.more-link');

    const sectorsView = document.getElementById('sectors-view');
    const newsDetailsView = document.getElementById('news-details-view');
    const detailTitle = document.getElementById('detail-title');
    const detailDate = document.getElementById('detail-date');
    const detailImage = document.getElementById('detail-image');
    const detailBody = document.getElementById('detail-body');
    const detailUpdate = document.getElementById('detail-update');

    function updateNews(index) {
        const item = newsData[index];
        titleEl.textContent = item.title;
        dateEl.textContent = item.date;
        imageEl.src = item.image;
        imageEl.alt = item.title;
        excerptEl.textContent = item.excerpt;
        statsEl.textContent = (index + 1) + ' من ' + newsData.length;
    }

    function showNewsDetail() {
        const item = newsData[currentIndex];
        detailTitle.textContent = item.title;
        detailDate.textContent = item.date;
        detailImage.src = item.image;
        detailBody.innerHTML = item.fullContent;
        detailUpdate.textContent = item.lastUpdate;

        sectorsView.style.display = 'none';
        newsDetailsView.style.display = 'block';
    }

    function showSectors() {
        newsDetailsView.style.display = 'none';
        sectorsView.style.display = 'block';
    }

    moreLink.addEventListener('click', (e) => {
        e.preventDefault();
        showNewsDetail();
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = newsData.length - 1;
        }
        updateNews(currentIndex);
    });

    nextBtn.addEventListener('click', () => {
        if (currentIndex < newsData.length - 1) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateNews(currentIndex);
    });

    updateNews(currentIndex);

    // Functions to toggle between sectors and moidiwan views
    function showMoidiwan() {
        document.getElementById('sectors-view').parentElement.parentElement.style.display = 'none';
        document.getElementById('news-details-view').style.display = 'none';
        document.getElementById('moidiwan-view').style.display = 'flex';
        
        // Update breadcrumb (standardized with includes/breadcrumb.php)
        const bc = document.getElementById('dynamic-breadcrumb');
        bc.innerHTML = `
            <a href="#" onclick="showSectorsFromDiwan(); return false;">القطاعات</a>
            <span class="breadcrumb-separator"> > </span>
            <span class="breadcrumb-current">ديوان وزارة الداخلية</span>
        `;
        
        window.scrollTo(0, 0);
    }

    function showSectorsFromDiwan() {
        document.getElementById('moidiwan-view').style.display = 'none';
        document.getElementById('sectors-view').parentElement.parentElement.style.display = 'flex';
        document.getElementById('sectors-view').style.display = 'block';
        document.getElementById('news-details-view').style.display = 'none';
        
        // Update breadcrumb back to sectors only
        const bc = document.getElementById('dynamic-breadcrumb');
        bc.innerHTML = `<span class="breadcrumb-current">القطاعات</span>`;
        
        window.scrollTo(0, 0);
    }

    // Make showSectors available globally for the onclick in moidiwan section
    window.showSectors = showSectorsFromDiwan;

</script>

<?php include '../../includes/footer.php'; ?>
