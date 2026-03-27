<?php
$page_title = "إمارة منطقة الباحة";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// include '../../includes/breadcrumb.php';
?>

<style>
.baha-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 30px auto;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.baha-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.baha-col-right {
    width: 23%;
}
.baha-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.baha-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.baha-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.baha-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.baha-menu li {
    border-bottom: 1px dotted #ccc;
}
.baha-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.baha-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.baha-social-box {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: flex-end; /* Align right */
    gap: 10px;
}
.baha-social-box a {
    display: block;
    width: 40px;
}
.baha-social-box img {
    width: 100%;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Middle Column (Slider) */
.baha-col-mid {
    width: 48%;
}
.baha-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.baha-slider {
    position: relative;
    background: #eaeaea;
    padding: 25px 50px;
    border-radius: 4px;
    overflow: hidden;
}
.baha-slider-img {
    width: 100%;
    display: block;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.baha-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
}
.baha-slider-nav span {
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
.baha-slider-nav span.active {
    background: #009c5d;
}
.baha-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(180,180,180,0.85); /* Lighter gray for Baha */
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.baha-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.baha-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.baha-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.baha-col-left {
    width: 24%;
}
.baha-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.baha-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.baha-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.baha-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.baha-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.baha-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.baha-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.baha-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.baha-about-text-col {
    width: 58%;
    text-align: right;
}
.baha-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.baha-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.baha-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.baha-about-map {
    width: 100%;
    max-width: 90px;
}
.baha-about-more {
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
    .baha-grid {
        flex-direction: column;
    }
    .baha-col-right, .baha-col-mid, .baha-col-left {
        width: 100%;
    }
}
</style>

<div class="baha-page-container">
    <div class="baha-grid">
        
        <!-- Right Menu Column -->
        <div class="baha-col-right">
            <div class="baha-breadcrumb">
                <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة الباحة</span>
            </div>
            <ul class="baha-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المحافظات</a></li>
            </ul>
            <div class="baha-social-box">
                <a href="#"><img src="../../images/X-icon-moi.svg" alt="X" style="background:#000; padding:8px;"></a>
                <a href="#"><img src="../../images/snap.svg" alt="Snapchat" style="background:#fffc00; padding:8px;"></a>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="baha-col-mid">
            <div class="baha-section-title">أهم الأحداث</div>
            <div class="baha-slider">
                <div class="baha-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="baha-slider-img" alt="أمير منطقة الباحة">
                
                <div class="baha-caption">
                    <a href="#" class="baha-read-more-slider">اقرأ المزيد</a>
                    <div class="baha-caption-text">الخميس 9 ذو الحجة 1446 : أمير منطقة الباحة يهنئ القيادة بمناسبة حلول عيد الأضحى المبارك</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="baha-col-left">
            <div class="baha-section-title">آخر الأخبار</div>
            
            <div class="baha-news-block">
                <div class="baha-news-title">أمير الباحة يستقبل أعضاء لجنة إصلاح ذات البين بمنطقة نجران</div>
                <div class="baha-news-date">الثلاثاء 3 ربيع الأول 1447</div>
                <div class="baha-news-text">استقبل صاحب السمو الملكي الأمير الدكتور حسام بن سعود بن عبدالعزيز أمير منطقة الباحة، في مكتبه بالإمارة اليوم، أعضاء لجنة إصلاح ذات البين بمنطقة نجران، وذلك</div>
                <a href="#" class="baha-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="baha-about-box">
                <div class="baha-about-box-header">نبذة عن المنطقة</div>
                <div class="baha-about-box-content">
                    <div class="baha-about-img-col">
                        <img src="../../images/emara/baha.png" onerror="this.src='../../images/emara/riyadh.png'" class="baha-about-map" alt="خريطة الباحة">
                    </div>
                    <div class="baha-about-text-col">
                        <div class="baha-about-inner-title">نبذة عن المنطقة</div>
                        <div class="baha-about-desc">تقع منطقة الباحة جنوب غرب المملكة العربية السعودية ، حيث تقع على خط الطول 41 ْ 42 وخط العرض شرقاً 19 ْ 20 شمالاً ..</div>
                        <a href="#" class="baha-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>