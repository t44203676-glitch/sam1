<?php
$page_title = "إمارة منطقة الجوف";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// Removing default breadcrumb to implement the custom one in the layout
// include '../../includes/breadcrumb.php';
?>

<style>
.jowf-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 30px auto;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.jowf-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.jowf-col-right {
    width: 23%;
}
.jowf-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.jowf-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.jowf-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.jowf-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.jowf-menu li {
    border-bottom: 1px dotted #ccc;
}
.jowf-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.jowf-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.jowf-twitter-box {
    margin-top: 15px;
    background: linear-gradient(135deg, #e8eced, #f5f7f8);
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px dotted #bbb;
    font-weight: bold;
    gap: 10px;
}
.jowf-twitter-box img {
    width: 24px;
}
.jowf-twitter-box span {
    color: #008f5a;
}
.jowf-strategy-banner {
    margin-top: 15px;
    display: block;
    text-align: center;
    border-top: 1px dotted #ccc;
    padding-top: 15px;
}
.jowf-strategy-banner img {
    max-width: 100%;
    height: auto;
}

/* Middle Column (Slider) */
.jowf-col-mid {
    width: 48%;
}
.jowf-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.jowf-slider {
    position: relative;
    background: #eaeaea;
    padding: 25px 50px;
    border-radius: 4px;
    overflow: hidden;
}
.jowf-slider-img {
    width: 100%;
    display: block;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.jowf-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
}
.jowf-slider-nav span {
    width: 28px;
    height: 28px;
    background: #666;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
}
.jowf-slider-nav span.active {
    background: #009c5d;
}
.jowf-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(30,30,30,0.85);
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.jowf-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.jowf-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.jowf-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.jowf-col-left {
    width: 24%;
}
.jowf-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.jowf-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.jowf-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.jowf-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.jowf-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.jowf-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.jowf-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.jowf-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.jowf-about-text-col {
    width: 58%;
    text-align: right;
}
.jowf-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.jowf-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.jowf-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.jowf-about-map {
    width: 100%;
    max-width: 90px;
}
.jowf-about-more {
    display: block;
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 13px;
    margin-top: 15px;
    text-align: left;
}

.clearfix::after {
    content: "";
    clear: both;
    display: table;
}

/* Responsive */
@media (max-width: 992px) {
    .jowf-grid {
        flex-direction: column;
    }
    .jowf-col-right, .jowf-col-mid, .jowf-col-left {
        width: 100%;
    }
}
</style>

<div class="jowf-page-container">
    <div class="jowf-grid">
        
        <!-- Right Menu Column -->
        <div class="jowf-col-right">
            <div class="jowf-breadcrumb">
                <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة الجوف</span>
            </div>
            <ul class="jowf-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المحافظات</a></li>
                <li><a href="#">المناقصات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                <li><a href="#">الإعلانات</a></li>
            </ul>
            <div class="jowf-twitter-box">
                <img src="../../images/X-icon-moi.svg" alt="X" onerror="this.style.display='none'">
                <span>للتواصل</span>
            </div>
            <!-- Added Jowf Strategy Banner based on image -->
            <div class="jowf-strategy-banner">
                <!-- Using placeholder if exact JOUF STRATEGY image doesn't exist locally -->
                <img src="../../images/cvaffrslogo17-12-2023.jpg" onerror="this.style.display='none'" alt="استراتيجية الجوف JOUF STRATEGY" style="filter: grayscale(100%);">
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="jowf-col-mid">
            <div class="jowf-section-title">أهم الأحداث</div>
            <div class="jowf-slider">
                <div class="jowf-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="jowf-slider-img" alt="أمير منطقة الجوف">
                
                <div class="jowf-caption">
                    <a href="#" class="jowf-read-more-slider">اقرأ المزيد</a>
                    <div class="jowf-caption-text">الاثنين 13 ذو الحجة 1446 : أمير الجوف يتابع المرحلة الثانية والأخيرة لتوديع الحجاج عبر منفذ الحديثة ومدينة الحجاج بالشقيق</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="jowf-col-left">
            <div class="jowf-section-title">آخر الأخبار</div>
            
            <div class="jowf-news-block">
                <div class="jowf-news-title">أمير الجوف يرأس اجتماع لجنة الدفاع المدني الرئيسة بالمنطقة</div>
                <div class="jowf-news-date">الخميس 15 جمادى الأول 1447</div>
                <div class="jowf-news-text">رأس صاحب السمو الملكي الأمير فيصل بن نواف بن عبدالعزيز أمير منطقة الجوف رئيس لجنة الدفاع المدني الرئيسة بالمنطقة، بقاعة الاجتماعات بالإمارة اليوم، اجتماع اللجنة الرئيسة للدفاع</div>
                <a href="#" class="jowf-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="jowf-about-box">
                <div class="jowf-about-box-header">نبذة عن المنطقة</div>
                <div class="jowf-about-box-content">
                    <div class="jowf-about-img-col">
                        <!-- Try to load Jowf map image, fallback to general map -->
                        <img src="../../images/JOUF.png" onerror="this.src='../../images/emara/riyadh.png'" class="jowf-about-map" alt="خريطة الجوف">
                    </div>
                    <div class="jowf-about-text-col">
                        <div class="jowf-about-inner-title">نبذة عن المنطقة</div>
                        <div class="jowf-about-desc">منطقة الجوف هي إحدى المناطق الإدارية بالمملكة، وتقع شمال البلاد على الحدود مع الأردن.</div>
                        <a href="#" class="jowf-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>