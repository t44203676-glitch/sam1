<?php
$page_title = "إمارة منطقة مكة المكرمة";
$active_page = "emirates";
include '../../includes/header.php';
include '../../includes/navigation.php';
// Removing default breadcrumb to implement the custom one in the layout
// include '../../includes/breadcrumb.php';
?>

<style>
.makkah-page-container {
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
.makkah-grid {
    display: flex;
    justify-content: space-between;
    gap: 25px;
}

/* Right Column (Menu) */
.makkah-col-right {
    width: 23%;
}
.makkah-breadcrumb {
    text-align: right;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    padding-right: 5px;
}
.makkah-breadcrumb a {
    color: #008f5a;
    text-decoration: none;
}
.makkah-breadcrumb span.sep {
    color: #888;
    margin: 0 5px;
    font-size: 14px;
}
.makkah-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    border-top: 5px solid #a8a8a8;
}
.makkah-menu li {
    border-bottom: 1px dotted #ccc;
}
.makkah-menu li a {
    display: block;
    padding: 12px 10px;
    text-decoration: none;
    color: #444;
    font-weight: bold;
    font-size: 15px;
    text-align: right;
    transition: all 0.2s;
}
.makkah-menu li a:hover {
    background-color: #f9f9f9;
    color: #008f5a;
    padding-right: 15px;
}
.makkah-social-box {
    margin-top: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 5px;
}
.makkah-social-box a {
    display: block;
    width: 24%;
}
.makkah-social-box img {
    width: 100%;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Middle Column (Slider) */
.makkah-col-mid {
    width: 48%;
}
.makkah-section-title {
    font-weight: bold;
    color: #555;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: right;
}
.makkah-slider {
    position: relative;
    background: #eaeaea;
    padding: 0; /* Full bleed image for Makkah based on the design */
    border-radius: 4px;
    overflow: hidden;
    height: 380px; /* fixed height to match proportion */
}
.makkah-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.makkah-slider-nav {
    position: absolute;
    top: 25px;
    left: 25px;
    display: flex;
    gap: 8px;
    z-index: 10;
}
.makkah-slider-nav span {
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
.makkah-slider-nav span.active {
    background: #009c5d;
}
.makkah-caption {
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
.makkah-caption-text {
    width: 80%;
    font-size: 14px;
    line-height: 1.6;
    text-align: right;
    font-weight: 500;
}
.makkah-read-more-slider {
    color: #00a651;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.makkah-read-more-slider::before {
    content: "◀";
    font-size: 12px;
}

/* Left Column (News & About) */
.makkah-col-left {
    width: 24%;
}
.makkah-news-block {
    margin-bottom: 40px;
    text-align: right;
}
.makkah-news-title {
    color: #00a651;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.4;
}
.makkah-news-date {
    color: #888;
    font-size: 12px;
    margin-bottom: 12px;
    font-weight: bold;
}
.makkah-news-text {
    font-size: 14px;
    color: #444;
    line-height: 1.7;
    margin-bottom: 15px;
}
.makkah-read-more {
    color: #00a651;
    font-weight: bold;
    text-decoration: none;
    font-size: 14px;
    float: left;
}

.makkah-about-box {
    border: 1px solid #dcdcdc;
    background: #fff;
}
.makkah-about-box-header {
    background: #f7f7f7;
    padding: 12px 15px;
    font-weight: bold;
    color: #222;
    border-bottom: 1px solid #dcdcdc;
    text-align: right;
    font-size: 16px;
}
.makkah-about-box-content {
    padding: 15px;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.makkah-about-text-col {
    width: 58%;
    text-align: right;
}
.makkah-about-img-col {
    width: 38%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.makkah-about-inner-title {
    color: #00a651;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 10px;
}
.makkah-about-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.6;
}
.makkah-about-map {
    width: 100%;
    max-width: 90px;
}
.makkah-about-more {
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
    .makkah-grid {
        flex-direction: column;
    }
    .makkah-col-right, .makkah-col-mid, .makkah-col-left {
        width: 100%;
    }
}
</style>

<div class="makkah-page-container">
    <div class="makkah-grid">
        
        <!-- Right Menu Column -->
        <div class="makkah-col-right">
            <div class="makkah-breadcrumb" style="padding-top: 10px;">
                <a href="../../index.php">الرئيسية</a> <span class="sep">&lt;</span> <a href="#">الإمارات</a> <span class="sep">&lt;</span> <span>إمارة منطقة مكة المكرمة</span>
            </div>
            <ul class="makkah-menu">
                <li><a href="#">الرئيسية</a></li>
                <li><a href="#">نبذة عن المنطقة</a></li>
                <li><a href="#">الهيكل التنظيمي</a></li>
                <li><a href="#">المهام</a></li>
                <li><a href="#">الأخبار</a></li>
                <li><a href="#">المحافظات</a></li>
                <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
            </ul>
            <div class="makkah-social-box">
                <a href="#"><img src="../../images/youtube.svg" alt="YouTube" style="background:#ff0000; padding:5px; border-radius: 4px;"></a>
                <a href="#"><img src="../../images/insta.svg" alt="Instagram" style="background:radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); padding:5px; border-radius: 4px;"></a>
                <a href="#"><img src="../../images/snap.svg" alt="Snapchat" style="background:#fffc00; padding:5px; border-radius: 4px;"></a>
                <a href="#"><img src="../../images/X-icon-moi.svg" alt="X" style="background:#000; padding:5px; border-radius: 4px;"></a>
            </div>
        </div>

        <!-- Middle Slider Column -->
        <div class="makkah-col-mid">
            <div class="makkah-section-title">أهم الأحداث</div>
            <div class="makkah-slider">
                <div class="makkah-slider-nav">
                    <span>٣</span>
                    <span>٢</span>
                    <span class="active">١</span>
                </div>
                <!-- Using a generic placeholder image if specific Emir image is missing -->
                <img src="../../images/emirates_news.png" onerror="this.src='../../images/brgrdlogo.jpg'" class="makkah-slider-img" alt="أمير منطقة مكة المكرمة">
                
                <div class="makkah-caption">
                    <a href="#" class="makkah-read-more-slider">اقرأ المزيد</a>
                    <div class="makkah-caption-text">الأحد 12 ذو الحجة 1446 : نائب أمير منطقة مكة المكرمة يعلن نجاح حج هذا العام 1446هـ</div>
                </div>
            </div>
        </div>

        <!-- Left News Column -->
        <div class="makkah-col-left">
            <div class="makkah-section-title">آخر الأخبار</div>
            
            <div class="makkah-news-block">
                <div class="makkah-news-title">سعود بن نهار يستقبل مدير شرطة محافظة الطائف</div>
                <div class="makkah-news-date">الاثنين 17 صفر 1447</div>
                <div class="makkah-news-text">استقبل صاحب السمو الملكي الأمير سعود بن نهار بن سعود بن عبدالعزيز محافظ الطائف اليوم، مدير شرطة الطائف اللواء عثمان بن عبدالرحمن اليوسف.</div>
                <a href="#" class="makkah-read-more">اقرأ المزيد ◀</a>
                <div class="clearfix"></div>
            </div>

            <div class="makkah-about-box">
                <div class="makkah-about-box-header">نبذة عن المنطقة</div>
                <div class="makkah-about-box-content">
                    <div class="makkah-about-img-col">
                        <!-- Assuming you have a green highlighted map for makkah, otherwise fallback to riyadh -->
                        <img src="../../images/emara/makkah.png" onerror="this.src='../../images/emara/riyadh.png'" class="makkah-about-map" alt="خريطة مكة المكرمة">
                    </div>
                    <div class="makkah-about-text-col">
                        <div class="makkah-about-inner-title">نبذة عن المنطقة</div>
                        <div class="makkah-about-desc">مهوى أفئدة المسلمين في شتى أرجاء الأرض، مهبط الوحي وموقع المسجد الحرام المبارك، وكعبته المشرفة، مقصد قوافل الحجاج والمعتمرين والزائرين، أطهر بقعة على وجه الأرض</div>
                        <a href="#" class="makkah-about-more">المزيد ◀</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>