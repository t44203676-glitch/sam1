<?php
$page_title = "إمارة منطقة حائل";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// Removing default breadcrumb to implement the custom one in the layout
// include '../../includes/breadcrumb.php';
?>

<style>
.hail-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 0 0 0;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.hail-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.hail-col-right {
    width: 23%;
}
.hail-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.hail-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.hail-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.hail-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.hail-menu li {
    border-bottom: 1px dotted #ccc;
}
.hail-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.hail-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.hail-twitter-box {
    margin-top: 15px;
    background: #333;
    color: #fff;
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px dotted #bbb;
    font-weight: bold;
    gap: 10px;
}
.hail-twitter-box img {
    width: 24px;
}
.hail-twitter-box span {
    color: #008f5a;
}

/* Middle Column (Slider) */
.hail-col-mid {
    width: 48%;
}
.hail-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.hail-slider {
    position: relative;
    background: #eaeaea;
    padding: 0;
    border-radius: 4px;
    overflow: hidden;
    height: 380px; 
}
.hail-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.hail-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
    z-index: 10;
}
.hail-slider-nav span {
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
.hail-slider-nav span.active {
    background: #009c5d;
}
.hail-caption {
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
.hail-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.hail-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.hail-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.hail-col-left {
    width: 24%;
}
.hail-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.hail-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.hail-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.hail-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.hail-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.hail-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.hail-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.hail-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.hail-about-text-col {
    width: 58%;
    text-align: right;
}
.hail-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hail-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.hail-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.hail-about-map {
    width: 100%;
    max-width: 90px;
}
.hail-about-more {
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
    .hail-grid {
        flex-direction: column;
    }
    .hail-col-right, .hail-col-mid, .hail-col-left {
        width: 100%;
    }
}
</style>

<div class="hail-page-container">
    <div class="hail-grid">
        
        <!-- Right Menu Column -->
        <div class="hail-col-right">
            <div class="hail-breadcrumb" style="padding-top: 10px;">
                <a href="../../index.php">الرئيسية</a> <span class="sep">&lt;</span> <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة حائل</span>
            </div>
            <ul class="hail-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                <li><a href="#">المحافظات</a></li>
            </ul>
            <div class="hail-twitter-box">
                <img src="../../images/X-icon-moi.svg" alt="X" onerror="this.style.display='none'">
                <span>للتواصل</span>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="hail-col-mid">
            <div class="hail-section-title">أهم الأحداث</div>
            <div class="hail-slider">
                <div class="hail-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="hail-slider-img" alt="أمير منطقة حائل">
                
                <div class="hail-caption">
                    <a href="#" class="hail-read-more-slider">اقرأ المزيد</a>
                    <div class="hail-caption-text">الثلاثاء 3 ربيع الأول 1447 : أمير منطقة حائل يوافق على إطلاق مبادرة "هاكاثون حائل الدولي للابتكار"</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="hail-col-left">
            <div class="hail-section-title">آخر الأخبار</div>
            
            <div class="hail-news-block">
                <div class="hail-news-title">أمير منطقة حائل يوافق على إطلاق مبادرة "هاكاثون حائل الدولي للابتكار"</div>
                <div class="hail-news-date">الثلاثاء 3 ربيع الأول 1447</div>
                <div class="hail-news-text">وافق صاحب السمو الملكي الأمير عبدالعزيز بن سعد بن عبدالعزيز، أمير منطقة حائل، على إطلاق مبادرة "هاكاثون حائل الدولي للابتكار"، الذي يهدف إلى تعزيز ثقافة</div>
                <a href="#" class="hail-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="hail-about-box">
                <div class="hail-about-box-header">نبذة عن المنطقة</div>
                <div class="hail-about-box-content">
                    <div class="hail-about-img-col">
                        <img src="../../images/emara/hail.png" onerror="this.src='../../images/emara/riyadh.png'" class="hail-about-map" alt="خريطة حائل">
                    </div>
                    <div class="hail-about-text-col">
                        <div class="hail-about-inner-title">نبذة عن المنطقة</div>
                        <div class="hail-about-desc">منطقة حائل أنشئت عام 1340هـ بعد توحيد المملكة العربية السعودية على يد المؤسس جلالة الملك عبدالعزيز بن عبدالرحمن آل سعود...</div>
                        <a href="#" class="hail-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>