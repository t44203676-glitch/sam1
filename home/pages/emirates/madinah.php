<?php
$page_title = "إمارة منطقة المدينة المنورة";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// include '../../includes/breadcrumb.php';
?>

<style>
.madinah-page-container {
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
.madinah-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.madinah-col-right {
    width: 23%;
}
.madinah-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.madinah-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.madinah-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.madinah-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.madinah-menu li {
    border-bottom: 1px dotted #ccc;
}
.madinah-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.madinah-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.madinah-social-box {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 5px;
}
.madinah-social-box a {
    display: block;
    width: 24%;
}
.madinah-social-box img {
    width: 100%;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Middle Column (Slider) */
.madinah-col-mid {
    width: 48%;
}
.madinah-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.madinah-slider {
    position: relative;
    background: #eaeaea;
    padding: 0;
    border-radius: 4px;
    overflow: hidden;
    height: 380px; 
}
.madinah-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.madinah-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
    z-index: 10;
}
.madinah-slider-nav span {
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
.madinah-slider-nav span.active {
    background: #009c5d;
}
.madinah-caption {
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
.madinah-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.madinah-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.madinah-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.madinah-col-left {
    width: 24%;
}
.madinah-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.madinah-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.madinah-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.madinah-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.madinah-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.madinah-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.madinah-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.madinah-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.madinah-about-text-col {
    width: 58%;
    text-align: right;
}
.madinah-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.madinah-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.madinah-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.madinah-about-map {
    width: 100%;
    max-width: 90px;
}
.madinah-about-more {
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
    .madinah-grid {
        flex-direction: column;
    }
    .madinah-col-right, .madinah-col-mid, .madinah-col-left {
        width: 100%;
    }
}
</style>

<div class="madinah-page-container">
    <div class="madinah-grid">
        
        <!-- Right Menu Column -->
        <div class="madinah-col-right">
            <div class="madinah-breadcrumb" style="padding-top: 10px;">
                <a href="../../index.php">الرئيسية</a> <span class="sep">&lt;</span> <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة المدينة المنورة</span>
            </div>
            <ul class="madinah-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المحافظات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
            </ul>
            <div class="madinah-social-box">
                <a href="#"><img src="../../images/telegram.svg" alt="Telegram" style="background:#0088cc; padding:5px; border-radius: 4px;"></a>
                <a href="#"><img src="../../images/insta.svg" alt="Instagram" style="background:radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); padding:5px; border-radius: 4px;"></a>
                <a href="#"><img src="../../images/snap.svg" alt="Snapchat" style="background:#fffc00; padding:5px; border-radius: 4px;"></a>
                <a href="#"><img src="../../images/X-icon-moi.svg" alt="X" style="background:#000; padding:5px; border-radius: 4px;"></a>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="madinah-col-mid">
            <div class="madinah-section-title">أهم الأحداث</div>
            <div class="madinah-slider">
                <div class="madinah-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="madinah-slider-img" alt="أمير المدينة المنورة">
                
                <div class="madinah-caption">
                    <a href="#" class="madinah-read-more-slider">اقرأ المزيد</a>
                    <div class="madinah-caption-text">الخميس 15 جمادى الأول 1447 : أمير المدينة المنورة يستقبل وفداً من أعضاء مجلس الشورى</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="madinah-col-left">
            <div class="madinah-section-title">آخر الأخبار</div>
            
            <div class="madinah-news-block">
                <div class="madinah-news-title">أمير المدينة المنورة يستقبل وفداً من أعضاء مجلس الشورى</div>
                <div class="madinah-news-date">الخميس 15 جمادى الأول 1447</div>
                <div class="madinah-news-text">استقبل صاحب السمو الملكي الأمير سلمان بن سلطان بن عبدالعزيز، أمير منطقة المدينة المنورة، وفداً من أعضاء مجلس الشورى برئاسة عضو المجلس فضل بن سعد البوعينين، وذلك خلال</div>
                <a href="#" class="madinah-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="madinah-about-box">
                <div class="madinah-about-box-header">نبذة عن المنطقة</div>
                <div class="madinah-about-box-content">
                    <div class="madinah-about-img-col">
                        <img src="../../images/emara/madinah.png" onerror="this.src='../../images/emara/riyadh.png'" class="madinah-about-map" alt="خريطة المدينة المنورة">
                    </div>
                    <div class="madinah-about-text-col">
                        <div class="madinah-about-inner-title">نبذة عن المنطقة</div>
                        <div class="madinah-about-desc">وتعد إمارة منطقة المدينة المنورة إحدى هذه الإمارات الثلاث عشرة، وتأتي في المرتبة الثالثة من حيث المساحة، والخامسة من حيث عدد السكان ..</div>
                        <a href="#" class="madinah-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>