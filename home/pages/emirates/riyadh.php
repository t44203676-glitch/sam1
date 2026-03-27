<?php
$page_title = "إمارة منطقة الرياض";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// include '../../includes/breadcrumb.php';
?>

<style>
.riyadh-page-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
    font-family: 'Tahoma', 'Arial', sans-serif;
    direction: rtl;
    background: #fff;
    padding: 0 15px;
    box-sizing: border-box;
}
.riyadh-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.riyadh-col-right {
    width: 23%;
}
.riyadh-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.riyadh-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.riyadh-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.riyadh-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.riyadh-menu li {
    border-bottom: 1px dotted #ccc;
}
.riyadh-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.riyadh-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.riyadh-social-box {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: flex-end; /* Align right */
    gap: 10px;
}
.riyadh-social-box a {
    display: block;
    width: 40px;
}
.riyadh-social-box img {
    width: 100%;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Middle Column (Slider) */
.riyadh-col-mid {
    width: 48%;
}
.riyadh-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.riyadh-slider {
    position: relative;
    background: #eaeaea;
    padding: 25px 50px;
    border-radius: 4px;
    overflow: hidden;
}
.riyadh-slider-img {
    width: 100%;
    display: block;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.riyadh-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
}
.riyadh-slider-nav span {
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
.riyadh-slider-nav span.active {
    background: #009c5d;
}
.riyadh-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(30,30,30,0.85); /* Dark gray for Riyadh */
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.riyadh-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.riyadh-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.riyadh-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.riyadh-col-left {
    width: 24%;
}
.riyadh-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.riyadh-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.riyadh-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.riyadh-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.riyadh-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.riyadh-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.riyadh-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.riyadh-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.riyadh-about-text-col {
    width: 58%;
    text-align: right;
}
.riyadh-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.riyadh-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.riyadh-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.riyadh-about-map {
    width: 100%;
    max-width: 90px;
}
.riyadh-about-more {
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
    .riyadh-grid {
        flex-direction: column;
    }
    .riyadh-col-right, .riyadh-col-mid, .riyadh-col-left {
        width: 100%;
    }
}
</style>

<div class="riyadh-page-container">
    <div class="riyadh-grid">
        
        <!-- Right Menu Column -->
        <div class="riyadh-col-right">
            <div class="riyadh-breadcrumb" style="padding-top: 20px;">
                <a href="../../index.php">الرئيسية</a> <span class="sep">&lt;</span> <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة الرياض</span>
            </div>
            <ul class="riyadh-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المحافظات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                <li><a href="#">أرقام تهمك</a></li>
            </ul>
            <div class="riyadh-social-box">
            <div class="riyadh-social-box">
                <a href="#"><img src="../../images/X-icon-moi.svg" alt="X" style="background:#333; padding:8px; border-radius: 4px; filter: invert(1);"></a>
                <a href="#"><img src="../../images/youtube.svg" alt="YouTube" style="background:#ff0000; padding:8px; border-radius: 4px;"></a>
            </div>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="riyadh-col-mid">
            <div class="riyadh-section-title">أهم الأحداث</div>
            <div class="riyadh-slider">
                <div class="riyadh-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="riyadh-slider-img" alt="نائب أمير منطقة الرياض">
                
                <div class="riyadh-caption">
                    <a href="#" class="riyadh-read-more-slider">اقرأ المزيد</a>
                    <div class="riyadh-caption-text">الاثنين 17 صفر 1447 : نائب أمير الرياض يطلع على جهود الموارد البشرية والتعليم التقني والمهني</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="riyadh-col-left">
            <div class="riyadh-section-title">آخر الأخبار</div>
            
            <div class="riyadh-news-block">
                <div class="riyadh-news-title">نائب أمير منطقة الرياض يدشّن مشاريع تعليمية بقيمة تتجاوز مليار ريال</div>
                <div class="riyadh-news-date">الاثنين 24 صفر 1447</div>
                <div class="riyadh-news-text">دشّن صاحب السمو الملكي الأمير محمد بن عبدالرحمن بن عبدالعزيز نائب أمير منطقة الرياض، في مكتبه بقصر الحكم، مشاريع الإدارة العامة للتعليم بمحافظات المنطقة</div>
                <a href="#" class="riyadh-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="riyadh-about-box">
                <div class="riyadh-about-box-header">نبذة عن المنطقة</div>
                <div class="riyadh-about-box-content">
                    <div class="riyadh-about-img-col">
                        <img src="../../images/emara/riyadh.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="riyadh-about-map" alt="خريطة الرياض">
                    </div>
                    <div class="riyadh-about-text-col">
                        <div class="riyadh-about-inner-title">نبذة عن المنطقة</div>
                        <div class="riyadh-about-desc">تقع منطقة الرياض وسط المملكة، وتبلغ مساحتها 412,000 كيلو متر مربع، وعدد سكانها 5,455,363 نسمة، وهي تعد ثاني أكبر منطقة في المملكة من حيث المساحة والكثافة السكانية..</div>
                        <a href="#" class="riyadh-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>