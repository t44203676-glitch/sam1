<?php
$page_title = "إمارة منطقة تبوك";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// Removing default breadcrumb to implement the custom one in the layout
// include '../../includes/breadcrumb.php';
?>

<style>
.tabuk-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 30px auto;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.tabuk-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.tabuk-col-right {
    width: 23%;
}
.tabuk-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.tabuk-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.tabuk-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.tabuk-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.tabuk-menu li {
    border-bottom: 1px dotted #ccc;
}
.tabuk-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.tabuk-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.tabuk-twitter-box {
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
.tabuk-twitter-box img {
    width: 24px;
}
.tabuk-twitter-box span {
    color: #008f5a;
}

/* Middle Column (Slider) */
.tabuk-col-mid {
    width: 48%;
}
.tabuk-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.tabuk-slider {
    position: relative;
    background: #eaeaea;
    padding: 25px 50px;
    border-radius: 4px;
    overflow: hidden;
}
.tabuk-slider-img {
    width: 100%;
    display: block;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.tabuk-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
}
.tabuk-slider-nav span {
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
.tabuk-slider-nav span.active {
    background: #009c5d;
}
.tabuk-caption {
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
.tabuk-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.tabuk-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.tabuk-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.tabuk-col-left {
    width: 24%;
}
.tabuk-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.tabuk-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.tabuk-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.tabuk-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.tabuk-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.tabuk-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.tabuk-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.tabuk-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.tabuk-about-text-col {
    width: 58%;
    text-align: right;
}
.tabuk-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tabuk-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.tabuk-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.tabuk-about-map {
    width: 100%;
    max-width: 90px;
}
.tabuk-about-more {
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
    .tabuk-grid {
        flex-direction: column;
    }
    .tabuk-col-right, .tabuk-col-mid, .tabuk-col-left {
        width: 100%;
    }
}
</style>

<div class="tabuk-page-container">
    <div class="tabuk-grid">
        
        <!-- Right Menu Column -->
        <div class="tabuk-col-right">
            <div class="tabuk-breadcrumb">
                <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة تبوك</span>
            </div>
            <ul class="tabuk-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المحافظات</a></li>
            </ul>
            <div class="tabuk-twitter-box">
                <img src="../../images/X-icon-moi.svg" alt="X" onerror="this.style.display='none'">
                <span>للتواصل</span>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="tabuk-col-mid">
            <div class="tabuk-section-title">أهم الأحداث</div>
            <div class="tabuk-slider">
                <div class="tabuk-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="tabuk-slider-img" alt="أمير تبوك">
                
                <div class="tabuk-caption">
                    <a href="#" class="tabuk-read-more-slider">اقرأ المزيد</a>
                    <div class="tabuk-caption-text">الأحد 12 ذو الحجة 1446 : أمير منطقة تبوك يرفع التهنئة للقيادة بمناسبة نجاح موسم الحج لهذا العام</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="tabuk-col-left">
            <div class="tabuk-section-title">آخر الأخبار</div>
            
            <div class="tabuk-news-block">
                <div class="tabuk-news-title">أمير تبوك يتابع تنفيذ المرحلة الثانية والأخيرة لتوديع ضيوف الرحمن عبر منفذ حالة عمار</div>
                <div class="tabuk-news-date">الاثنين 13 ذو الحجة 1446</div>
                <div class="tabuk-news-text">تابع صاحب السمو الملكي الأمير فهد بن سلطان بن عبد العزيز أمير منطقة تبوك المشرف العام على أعمال الحج بالمنطقة، تنفيذ المرحلة الثانية والأخيرة</div>
                <a href="#" class="tabuk-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="tabuk-about-box">
                <div class="tabuk-about-box-header">نبذة عن المنطقة</div>
                <div class="tabuk-about-box-content">
                    <div class="tabuk-about-img-col">
                        <img src="../../images/emara/riyadh.png" onerror="this.src='../../images/JOUF.png'" class="tabuk-about-map" alt="خريطة تبوك">
                    </div>
                    <div class="tabuk-about-text-col">
                        <div class="tabuk-about-inner-title">نبذة عن المنطقة</div>
                        <div class="tabuk-about-desc">منطقة تبوك تقع في شمال غرب المملكة بجوار دولة الأردن يحدها من الشرق الجوف وحائل ومن الجنوب المدينة المنورة ومن الغرب خليج العقبة والبحر الأحمر ..</div>
                        <a href="#" class="tabuk-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>