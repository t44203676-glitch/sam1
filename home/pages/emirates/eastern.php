<?php
$page_title = "إمارة المنطقة الشرقية";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// Removing default breadcrumb to implement the custom one in the layout
// include '../../includes/breadcrumb.php';
?>

<style>
.eastern-page-container {
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
.eastern-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.eastern-col-right {
    width: 23%;
}
.eastern-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.eastern-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.eastern-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.eastern-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.eastern-menu li {
    border-bottom: 1px dotted #ccc;
}
.eastern-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.eastern-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.eastern-social-box {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: flex-end; /* Align right */
    gap: 10px;
}
.eastern-social-box a {
    display: block;
    width: 40px;
}
.eastern-social-box img {
    width: 100%;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Middle Column (Slider) */
.eastern-col-mid {
    width: 48%;
}
.eastern-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.eastern-slider {
    position: relative;
    background: #eaeaea;
    padding: 25px 50px;
    border-radius: 4px;
    overflow: hidden;
}
.eastern-slider-img {
    width: 100%;
    display: block;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.eastern-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
}
.eastern-slider-nav span {
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
.eastern-slider-nav span.active {
    background: #009c5d;
}
.eastern-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(140,140,140,0.85); /* Matches the lighter gray from Eastern reference */
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.eastern-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.eastern-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.eastern-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.eastern-col-left {
    width: 24%;
}
.eastern-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.eastern-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.eastern-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.eastern-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.eastern-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.eastern-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.eastern-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.eastern-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.eastern-about-text-col {
    width: 58%;
    text-align: right;
}
.eastern-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.eastern-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.eastern-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.eastern-about-map {
    width: 100%;
    max-width: 90px;
}
.eastern-about-more {
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
    .eastern-grid {
        flex-direction: column;
    }
    .eastern-col-right, .eastern-col-mid, .eastern-col-left {
        width: 100%;
    }
}
</style>

<div class="eastern-page-container">
    <div class="eastern-grid">
        
        <!-- Right Menu Column -->
        <div class="eastern-col-right">
            <div class="eastern-breadcrumb" style="padding-top: 10px;">
                <a href="../../index.php">الرئيسية</a> <span class="sep">&lt;</span> <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة المنطقة الشرقية</span>
            </div>
            <ul class="eastern-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المحافظات</a></li>
                <li><a href="#">المناقصات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
            </ul>
            <div class="eastern-social-box">
                <a href="#"><img src="../../images/X-icon-moi.svg" alt="X" style="background:#000; padding:8px; border-radius: 4px;"></a>
                <a href="#"><img src="../../images/insta.svg" alt="Instagram" style="background:radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); padding:8px; border-radius: 4px;"></a>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="eastern-col-mid">
            <div class="eastern-section-title">أهم الأحداث</div>
            <div class="eastern-slider">
                <div class="eastern-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="eastern-slider-img" alt="أمير المنطقة الشرقية">
                
                <div class="eastern-caption">
                    <a href="#" class="eastern-read-more-slider">اقرأ المزيد</a>
                    <div class="eastern-caption-text">الثلاثاء 14 ذو الحجة 1446 : أمير المنطقة الشرقية يشيد بجهود الجهات العاملة في منافذ المنطقة لخدمة ضيوف الرحمن</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="eastern-col-left">
            <div class="eastern-section-title">آخر الأخبار</div>
            
            <div class="eastern-news-block">
                <div class="eastern-news-title">الأمير سعود بن نايف بن عبدالعزيز يستقبل رئيس جامعة الملك فيصل</div>
                <div class="eastern-news-date">الثلاثاء 3 ربيع الأول 1447</div>
                <div class="eastern-news-text">استقبل صاحب السمو الملكي الأمير سعود بن نايف بن عبدالعزيز أمير المنطقة الشرقية في مكتبه اليوم، رئيس جامعة الملك فيصل الدكتور عادل بن محمد أبو زناده، ووكلاء الجامعة، وذلك</div>
                <a href="#" class="eastern-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="eastern-about-box">
                <div class="eastern-about-box-header">نبذة عن المنطقة</div>
                <div class="eastern-about-box-content">
                    <div class="eastern-about-img-col">
                        <!-- Eastern Province Map outline -->
                        <img src="../../images/emara/eastern.png" onerror="this.src='../../images/emara/riyadh.png'" class="eastern-about-map" alt="خريطة الشرقية">
                    </div>
                    <div class="eastern-about-text-col">
                        <div class="eastern-about-inner-title">نبذة عن المنطقة</div>
                        <div class="eastern-about-desc">تعد المنطقة الشرقية أكبر مناطق المملكة جغرافياً، وهي عبارة عن سهل صحراوي يمتد من شاطئ الخليج العربي حتى صحراء الدهناء، ...</div>
                        <a href="#" class="eastern-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>