<?php
$page_title = "إمارة منطقة القصيم";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// Removing default breadcrumb to implement the custom one in the layout
// include '../../includes/breadcrumb.php';
?>

<style>
.qasim-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 30px auto;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.qasim-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.qasim-col-right {
    width: 23%;
}
.qasim-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.qasim-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.qasim-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.qasim-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.qasim-menu li {
    border-bottom: 1px dotted #ccc;
}
.qasim-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.qasim-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.qasim-social-box {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: flex-end; /* Align right */
    gap: 10px;
}
.qasim-social-box a {
    display: block;
    width: 40px;
}
.qasim-social-box img {
    width: 100%;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Middle Column (Slider) */
.qasim-col-mid {
    width: 48%;
}
.qasim-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.qasim-slider {
    position: relative;
    background: #eaeaea;
    padding: 25px 50px;
    border-radius: 4px;
    overflow: hidden;
}
.qasim-slider-img {
    width: 100%;
    display: block;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.qasim-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
}
.qasim-slider-nav span {
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
.qasim-slider-nav span.active {
    background: #009c5d;
}
.qasim-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(240,240,240,0.85); /* Light gray for Qasim */
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.qasim-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.qasim-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.qasim-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.qasim-col-left {
    width: 24%;
}
.qasim-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.qasim-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.qasim-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.qasim-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.qasim-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.qasim-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.qasim-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.qasim-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.qasim-about-text-col {
    width: 58%;
    text-align: right;
}
.qasim-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.qasim-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.qasim-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.qasim-about-map {
    width: 100%;
    max-width: 90px;
}
.qasim-about-more {
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
    .qasim-grid {
        flex-direction: column;
    }
    .qasim-col-right, .qasim-col-mid, .qasim-col-left {
        width: 100%;
    }
}
</style>

<div class="qasim-page-container">
    <div class="qasim-grid">
        
        <!-- Right Menu Column -->
        <div class="qasim-col-right">
            <div class="qasim-breadcrumb">
                <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة القصيم</span>
            </div>
            <ul class="qasim-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المحافظات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
            </ul>
            <div class="qasim-social-box">
                <a href="#"><img src="../../images/X-icon-moi.svg" alt="X" style="background:#000; padding:8px;"></a>
                <a href="#"><img src="../../images/insta.svg" alt="Instagram" style="background:radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); padding:8px;"></a>
                <a href="#"><img src="../../images/snap.svg" alt="Snapchat" style="background:#fffc00; padding:8px;"></a>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="qasim-col-mid">
            <div class="qasim-section-title">أهم الأحداث</div>
            <div class="qasim-slider">
                <div class="qasim-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="qasim-slider-img" alt="أمير منطقة القصيم">
                
                <div class="qasim-caption">
                    <a href="#" class="qasim-read-more-slider">اقرأ المزيد</a>
                    <!-- Using the news title for the slider as it's cut off in the design, and this is typical -->
                    <div class="qasim-caption-text" style="color:#555;">الأربعاء 14 جمادى الأول 1447 : أمير منطقة القصيم يرعى توقيع اتفاقيات شراكة مجتمعية بأكثر من 55 مليون ريال</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="qasim-col-left">
            <div class="qasim-section-title">آخر الأخبار</div>
            
            <div class="qasim-news-block">
                <div class="qasim-news-title">أمير منطقة القصيم يرعى توقيع اتفاقيات شراكة مجتمعية بأكثر من 55 مليون ريال</div>
                <div class="qasim-news-date">الأربعاء 14 جمادى الأول 1447</div>
                <div class="qasim-news-text">رعى صاحب السمو الملكي الأمير الدكتور فيصل بن مشعل بن سعود بن عبدالعزيز أمير منطقة القصيم، بحضور معالي وزير البلديات والإسكان الأستاذ ماجد الحقيل، مراسم</div>
                <a href="#" class="qasim-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="qasim-about-box">
                <div class="qasim-about-box-header">نبذة عن المنطقة</div>
                <div class="qasim-about-box-content">
                    <div class="qasim-about-img-col">
                        <img src="../../images/emara/qasim.png" onerror="this.src='../../images/emara/riyadh.png'" class="qasim-about-map" alt="خريطة القصيم">
                    </div>
                    <div class="qasim-about-text-col">
                        <div class="qasim-about-inner-title">نبذة عن المنطقة</div>
                        <div class="qasim-about-desc">هي إحدى المناطق الإدارية بالسعودية ومقر إمارتها بريدة.تبلغ مساحتها 65,000 كم2، بين المنطقتين الوسطى والشمالية. وعدد سكانها 1,450,000 نسمة ..</div>
                        <a href="#" class="qasim-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>