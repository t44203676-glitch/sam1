<?php
$page_title = "إمارة منطقة جازان";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// include '../../includes/breadcrumb.php';
?>

<style>
.jazan-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 30px auto;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.jazan-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.jazan-col-right {
    width: 23%;
}
.jazan-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.jazan-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.jazan-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.jazan-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.jazan-menu li {
    border-bottom: 1px dotted #ccc;
}
.jazan-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.jazan-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.jazan-twitter-box {
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
.jazan-twitter-box img {
    width: 24px;
}
.jazan-twitter-box span {
    color: #008f5a;
}

/* Middle Column (Slider) */
.jazan-col-mid {
    width: 48%;
}
.jazan-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.jazan-slider {
    position: relative;
    background: #eaeaea;
    padding: 0;
    border-radius: 4px;
    overflow: hidden;
    height: 380px;
}
.jazan-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.jazan-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
    z-index: 10;
}
.jazan-slider-nav span {
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
.jazan-slider-nav span.active {
    background: #009c5d;
}
.jazan-caption {
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
.jazan-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.jazan-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.jazan-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.jazan-col-left {
    width: 24%;
}
.jazan-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.jazan-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.jazan-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.jazan-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.jazan-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.jazan-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.jazan-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.jazan-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.jazan-about-text-col {
    width: 58%;
    text-align: right;
}
.jazan-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.jazan-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.jazan-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.jazan-about-map {
    width: 100%;
    max-width: 90px;
}
.jazan-about-more {
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
    .jazan-grid {
        flex-direction: column;
    }
    .jazan-col-right, .jazan-col-mid, .jazan-col-left {
        width: 100%;
    }
}
</style>

<div class="jazan-page-container">
    <div class="jazan-grid">
        
        <!-- Right Menu Column -->
        <div class="jazan-col-right">
            <div class="jazan-breadcrumb">
                <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة جازان</span>
            </div>
            <ul class="jazan-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المناقصات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                <li><a href="#">المحافظات</a></li>
            </ul>
            <div class="jazan-twitter-box">
                <img src="../../images/X-icon-moi.svg" alt="X" onerror="this.style.display='none'">
                <span>للتواصل</span>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="jazan-col-mid">
            <div class="jazan-section-title">أهم الأحداث</div>
            <div class="jazan-slider">
                <div class="jazan-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="jazan-slider-img" alt="أمير منطقة جازان">
                <div class="jazan-caption">
                    <a href="#" class="jazan-read-more-slider">اقرأ المزيد</a>
                    <div class="jazan-caption-text">الأمير محمد بن عبدالعزيز يرعى حفل تخريج دفعة جديدة من طلاب جامعة جازان ويشيد بالمستوى الأكاديمي المتميز</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="jazan-col-left">
            <div class="jazan-section-title">آخر الأخبار</div>
            
            <div class="jazan-news-block">
                <div class="jazan-news-title">الأمير محمد بن عبدالعزيز يفتتح مشاريع خدمية جديدة في محافظات جازان لخدمة المواطنين</div>
                <div class="jazan-news-date">الأحد 1 ربيع الأول 1447</div>
                <div class="jazan-news-text">افتتح صاحب السمو الأمير محمد بن ناصر بن عبدالعزيز، أمير منطقة جازان، جملةً من المشاريع الخدمية لتعزيز البنية التحتية بمحافظات المنطقة</div>
                <a href="#" class="jazan-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="jazan-about-box">
                <div class="jazan-about-box-header">نبذة عن المنطقة</div>
                <div class="jazan-about-box-content">
                    <div class="jazan-about-img-col">
                        <img src="../../images/emara/jazan.png" onerror="this.src='../../images/emara/riyadh.png'" class="jazan-about-map" alt="خريطة جازان">
                    </div>
                    <div class="jazan-about-text-col">
                        <div class="jazan-about-inner-title">نبذة عن المنطقة</div>
                        <div class="jazan-about-desc">تقع في أقصى الجنوب الغربي للمملكة على ساحل البحر الأحمر. مساحتها 11,671 كم² وعدد سكانها يتجاوز 1.5 مليون نسمة. عاصمتها مدينة جازان.</div>
                        <a href="#" class="jazan-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>