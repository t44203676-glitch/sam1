<?php
/* 
   Emirates Final Reconstruction - 100% Match
*/
$page_title = "إمارات المناطق";
$active_page = "emirates";
$body_class = "emirates-page";
include '../../includes/header.php';
include '../../includes/navigation.php';
include '../../includes/breadcrumb.php';

$regions_list = [
    'riyadh' => 'الرياض',
    'makkah' => 'مكة المكرمة',
    'madinah' => 'المدينة المنورة',
    'eastern-province' => 'المنطقة الشرقية',
    'jawf' => 'الجوف',
    'bahah' => 'الباحة',
    'asir' => 'عسير',
    'qassim' => 'القصيم',
    'hail' => 'حائل',
    'tabuk' => 'تبوك',
    'northern-borders' => 'الحدود الشمالية',
    'jizan' => 'جازان',
    'najran' => 'نجران'
];
?>

<link rel="stylesheet" href="../../css/emirates_final.css">
<script src="../../js/emirates_final.js" defer></script>

<div class="match-emirates-section" id="printable-area">
    <div class="match-emirates-wrapper">
        <!-- Right Column: Regions List -->
        <div class="match-regions-sidebar">
            <ul class="match-regions-list">
                <?php foreach ($regions_list as $id => $name): ?>
                    <li class="<?= $id === 'riyadh' ? 'active' : '' ?>" data-region="<?= $id ?>">
                        <a href="javascript:void(0)"><?= $name ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Middle Column: Map & Links -->
        <div class="match-main-display">
            <div class="match-display-header">الرياض</div>
            <div class="match-display-body">
                <img src="<?= BASE_URL ?>images/emara/riyadh.png" alt="Map">
            </div>
            <div class="match-display-footer">
                <?php foreach ($regions_list as $id => $name): ?>
                    <ul class="match-links-ul <?= $id === 'riyadh' ? 'active' : '' ?>" id="match-links-<?= $id ?>">
                        <li><a href="#">الرئيسية</a></li>
                        <li><a href="#">نبذة عن المنطقة</a></li>
                        <li><a href="#">الهيكل التنظيمي</a></li>
                        <li><a href="#">المهام</a></li>
                        <li><a href="#">الأخبار</a></li>
                        <li><a href="#">المناقصات</a></li>
                        <li><a href="#">لجنة التعاملات الإلكترونية</a></li>
                        <li><a href="#">المحافظات</a></li>
                        <?php if ($id === 'riyadh'): ?>
                            <li><a href="#">أرقام تهمك</a></li>
                        <?php endif; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Left Column: News & Services -->
        <div class="match-news-sidebar">
            <div class="match-news-card">
                <h3>أمير المدينة المنورة يستقبل وفداً من أعضاء مجلس الشورى</h3>
                <p class="date">الخميس 15 جمادى الأولى 1447</p>
                <div class="match-news-img">
                    <img src="<?= BASE_URL ?>images/emirates_news.png" alt="News Image">
                </div>
                <div class="match-news-content">
                    <p>استقبل صاحب السمو الملكي الأمير سلمان بن سلطان بن عبدالعزيز، أمير منطقة المدينة المنورة، وفداً من أعضاء مجلس الشورى برئاسة عضو المجلس فضل بن سعد البوعينين، وذلك خلال...</p>
                    <a href="#" class="match-more-link">المزيد ❮</a>
                </div>
                <div class="match-pagination">
                    <a href="#" class="match-pager-btn">❯</a>
                    <span>1 من 3</span>
                    <a href="#" class="match-pager-btn active">❮</a>
                </div>
            </div>

            <div class="match-service-box">
                <div class="match-service-title">مراكز التسجيل والتفعيل</div>
                <div class="match-service-more">
                    <a href="#">المزيد...</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
