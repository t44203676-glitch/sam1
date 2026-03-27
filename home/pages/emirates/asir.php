<?php
$page_title = "إمارة منطقة عسير";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// include '../../includes/breadcrumb.php';
?>

<style>
.asir-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 30px auto;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.asir-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.asir-col-right {
    width: 23%;
}
.asir-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.asir-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.asir-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.asir-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.asir-menu li {
    border-bottom: 1px dotted #ccc;
}
.asir-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.asir-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.asir-twitter-box {
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
.asir-twitter-box img {
    width: 24px;
}
.asir-twitter-box span {
    color: #008f5a;
}

/* Middle Column (Slider) */
.asir-col-mid {
    width: 48%;
}
.asir-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.asir-slider {
    position: relative;
    background: #eaeaea;
    padding: 0;
    border-radius: 4px;
    overflow: hidden;
    height: 380px;
}
.asir-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.asir-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
    z-index: 10;
}
.asir-slider-nav span {
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
.asir-slider-nav span.active {
    background: #009c5d;
}
.asir-caption {
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
.asir-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.asir-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.asir-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.asir-col-left {
    width: 24%;
}
.asir-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.asir-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.asir-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.asir-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.asir-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.asir-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.asir-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.asir-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.asir-about-text-col {
    width: 58%;
    text-align: right;
}
.asir-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.asir-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.asir-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.asir-about-map {
    width: 100%;
    max-width: 90px;
}
.asir-about-more {
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
    .asir-grid {
        flex-direction: column;
    }
    .asir-col-right, .asir-col-mid, .asir-col-left {
        width: 100%;
    }
}
</style>

<div class="asir-page-container">
    <div class="asir-grid">
        
        <!-- Right Menu Column -->
        <div class="asir-col-right">
            <div class="asir-breadcrumb">
                <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة عسير</span>
            </div>
            <ul class="asir-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المناقصات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                <li><a href="#">المحافظات</a></li>
            </ul>
            <div class="asir-twitter-box">
                <img src="../../images/X-icon-moi.svg" alt="X" onerror="this.style.display='none'">
                <span>للتواصل</span>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="asir-col-mid">
            <div class="asir-section-title">أهم الأحداث</div>
            <div class="asir-slider">
                <div class="asir-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="asir-slider-img" alt="أمير منطقة عسير">
                <div class="asir-caption">
                    <a href="#" class="asir-read-more-slider">اقرأ المزيد</a>
                    <div class="asir-caption-text">الأمير تركي بن طلال يرأس اجتماع مجلس المنطقة بمشاركة المسؤولين ورؤساء الجهات الحكومية بمنطقة عسير</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="asir-col-left">
            <div class="asir-section-title">آخر الأخبار</div>
            
            <div class="asir-news-block">
                <div class="asir-news-title">الأمير تركي بن طلال يرأس اجتماع مجلس منطقة عسير ويستعرض مستجدات المشاريع التنموية</div>
                <div class="asir-news-date">الاثنين 2 ربيع الأول 1447</div>
                <div class="asir-news-text">ترأس صاحب السمو الأمير تركي بن طلال بن عبدالعزيز، أمير منطقة عسير، اجتماع مجلس المنطقة لمناقشة المشاريع التنموية والخطط المستقبلية للمنطقة</div>
                <a href="#" class="asir-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="asir-about-box">
                <div class="asir-about-box-header">نبذة عن المنطقة</div>
                <div class="asir-about-box-content">
                    <div class="asir-about-img-col">
                        <img src="../../images/emara/asir.png" onerror="this.src='../../images/emara/riyadh.png'" class="asir-about-map" alt="خريطة عسير">
                    </div>
                    <div class="asir-about-text-col">
                        <div class="asir-about-inner-title">نبذة عن المنطقة</div>
                        <div class="asir-about-desc">تقع في جنوب غرب المملكة وتتميز بطبيعتها الجبلية الخلابة. مساحتها 81,000 كم² وعدد سكانها يتجاوز 2.2 مليون نسمة. عاصمتها أبها.</div>
                        <a href="#" class="asir-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>