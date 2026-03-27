<?php
$page_title = "إمارة منطقة نجران";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// include '../../includes/breadcrumb.php';
?>

<style>
.najran-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 30px auto;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.najran-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.najran-col-right {
    width: 23%;
}
.najran-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.najran-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.najran-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.najran-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.najran-menu li {
    border-bottom: 1px dotted #ccc;
}
.najran-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.najran-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.najran-twitter-box {
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
.najran-twitter-box img {
    width: 24px;
}
.najran-twitter-box span {
    color: #008f5a;
}

/* Middle Column (Slider) */
.najran-col-mid {
    width: 48%;
}
.najran-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.najran-slider {
    position: relative;
    background: #eaeaea;
    padding: 0;
    border-radius: 4px;
    overflow: hidden;
    height: 380px; 
}
.najran-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.najran-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
    z-index: 10;
}
.najran-slider-nav span {
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
.najran-slider-nav span.active {
    background: #009c5d;
}
.najran-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(30,30,30,0.85); /* Dark gray for Najran */
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.najran-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.najran-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.najran-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.najran-col-left {
    width: 24%;
}
.najran-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.najran-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.najran-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.najran-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.najran-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.najran-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.najran-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.najran-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.najran-about-text-col {
    width: 58%;
    text-align: right;
}
.najran-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.najran-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.najran-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.najran-about-map {
    width: 100%;
    max-width: 90px;
}
.najran-about-more {
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
    .najran-grid {
        flex-direction: column;
    }
    .najran-col-right, .najran-col-mid, .najran-col-left {
        width: 100%;
    }
}
</style>

<div class="najran-page-container">
    <div class="najran-grid">
        
        <!-- Right Menu Column -->
        <div class="najran-col-right">
            <div class="najran-breadcrumb">
                <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة نجران</span>
            </div>
            <ul class="najran-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المناقصات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                <li><a href="#">المحافظات</a></li>
            </ul>
            <div class="najran-twitter-box">
                <img src="../../images/X-icon-moi.svg" alt="X" onerror="this.style.display='none'">
                <span>للتواصل</span>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="najran-col-mid">
            <div class="najran-section-title">أهم الأحداث</div>
            <div class="najran-slider">
                <div class="najran-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="najran-slider-img" alt="أمير منطقة نجران">
                
                <div class="najran-caption">
                    <a href="#" class="najran-read-more-slider">اقرأ المزيد</a>
                    <div class="najran-caption-text">الثلاثاء 3 ربيع الأول 1447 : الأمير جلوي بن عبدالعزيز يشهد توقيع اتفاقيات تعاون بين برنامج مدينة نجران الصحية وعدد من الجهات بالمنطقة</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="najran-col-left">
            <div class="najran-section-title">آخر الأخبار</div>
            
            <div class="najran-news-block">
                <div class="najran-news-title">الأمير جلوي بن عبدالعزيز يشهد توقيع اتفاقيات تعاون بين برنامج مدينة نجران الصحية وعدد من الجهات بالمنطقة</div>
                <div class="najran-news-date">الثلاثاء 3 ربيع الأول 1447</div>
                <div class="najran-news-text">شهد صاحب السمو الأمير جلوي بن عبدالعزيز بن مساعد، أمير منطقة نجران رئيس اللجنة الرئيسة لمدينة نجران الصحية، اليوم، توقيع عدد من اتفاقيات تعاون للمسؤولية المجتمعية</div>
                <a href="#" class="najran-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="najran-about-box">
                <div class="najran-about-box-header">نبذة عن المنطقة</div>
                <div class="najran-about-box-content">
                    <div class="najran-about-img-col">
                        <img src="../../images/emara/najran.png" onerror="this.src='../../images/emara/riyadh.png'" class="najran-about-map" alt="خريطة نجران">
                    </div>
                    <div class="najran-about-text-col">
                        <div class="najran-about-inner-title">نبذة عن المنطقة</div>
                        <div class="najran-about-desc">تقع في جنوب غرب المملكة على الحدود مع اليمن. وتبلغ مساحة منطقة نجران 360000 كم2، وعدد سكانها 620,000 ألف نسمة. وعاصمتها هي مدينة نجران.. تواصل معنا</div>
                        <a href="#" class="najran-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>