<?php
$page_title = "إمارة منطقة الحدود الشمالية";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// include '../../includes/breadcrumb.php';
?>

<style>
.northern-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 30px auto;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.northern-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.northern-col-right {
    width: 23%;
}
.northern-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.northern-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.northern-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.northern-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.northern-menu li {
    border-bottom: 1px dotted #ccc;
}
.northern-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.northern-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.northern-twitter-box {
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
.northern-twitter-box img {
    width: 24px;
}
.northern-twitter-box span {
    color: #008f5a;
}

/* Middle Column (Slider) */
.northern-col-mid {
    width: 48%;
}
.northern-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.northern-slider {
    position: relative;
    background: #eaeaea;
    padding: 0;
    border-radius: 4px;
    overflow: hidden;
    height: 380px;
}
.northern-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.northern-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
    z-index: 10;
}
.northern-slider-nav span {
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
.northern-slider-nav span.active {
    background: #009c5d;
}
.northern-caption {
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
.northern-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.northern-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.northern-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.northern-col-left {
    width: 24%;
}
.northern-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.northern-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.northern-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.northern-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.northern-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.northern-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.northern-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.northern-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.northern-about-text-col {
    width: 58%;
    text-align: right;
}
.northern-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.northern-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.northern-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.northern-about-map {
    width: 100%;
    max-width: 90px;
}
.northern-about-more {
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
    .northern-grid {
        flex-direction: column;
    }
    .northern-col-right, .northern-col-mid, .northern-col-left {
        width: 100%;
    }
}
</style>

<div class="northern-page-container">
    <div class="northern-grid">
        
        <!-- Right Menu Column -->
        <div class="northern-col-right">
            <div class="northern-breadcrumb">
                <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة الحدود الشمالية</span>
            </div>
            <ul class="northern-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المناقصات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                <li><a href="#">المحافظات</a></li>
            </ul>
            <div class="northern-twitter-box">
                <img src="../../images/X-icon-moi.svg" alt="X" onerror="this.style.display='none'">
                <span>للتواصل</span>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="northern-col-mid">
            <div class="northern-section-title">أهم الأحداث</div>
            <div class="northern-slider">
                <div class="northern-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="northern-slider-img" alt="أمير منطقة الحدود الشمالية">
                <div class="northern-caption">
                    <a href="#" class="northern-read-more-slider">اقرأ المزيد</a>
                    <div class="northern-caption-text">الأمير فيصل بن خالد يتفقد مستشفى عرعر المركزي ويوجه بتعزيز الخدمات الصحية لأبناء منطقة الحدود الشمالية</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="northern-col-left">
            <div class="northern-section-title">آخر الأخبار</div>
            
            <div class="northern-news-block">
                <div class="northern-news-title">الأمير فيصل بن خالد يرأس اجتماع لجنة التنمية الاجتماعية ويستعرض خطط دعم المواطنين بمنطقة الحدود الشمالية</div>
                <div class="northern-news-date">السبت 29 صفر 1447</div>
                <div class="northern-news-text">ترأس صاحب السمو الأمير فيصل بن خالد بن سلطان، أمير منطقة الحدود الشمالية، اجتماع لجنة التنمية الاجتماعية لمناقشة خطط دعم الأسر المستحقة وتطوير الخدمات.</div>
                <a href="#" class="northern-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="northern-about-box">
                <div class="northern-about-box-header">نبذة عن المنطقة</div>
                <div class="northern-about-box-content">
                    <div class="northern-about-img-col">
                        <img src="../../images/emara/northern.png" onerror="this.src='../../images/emara/riyadh.png'" class="northern-about-map" alt="خريطة الحدود الشمالية">
                    </div>
                    <div class="northern-about-text-col">
                        <div class="northern-about-inner-title">نبذة عن المنطقة</div>
                        <div class="northern-about-desc">تقع في أقصى شمال المملكة على الحدود مع العراق والأردن. مساحتها 111,797 كم² وعدد سكانها نحو 400,000 نسمة. عاصمتها مدينة عرعر.</div>
                        <a href="#" class="northern-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>