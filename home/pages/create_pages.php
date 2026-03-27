<?php
/**
 * سكريبت لإنشاء جميع صفحات الموقع تلقائياً - النسخة الشاملة
 */

function createPage($folder, $filename, $title, $active_page)
{
    $content = '<?php
$page_title = "' . $title . '";
$active_page = "' . $active_page . '";
include \'../../includes/header.php\';
include \'../../includes/navigation.php\';
include \'../../includes/breadcrumb.php\';
?>

<div class="container row">
    <div class="main-content" style="width: 100%;">
        <div class="page-content" style="padding: 100px 20px; min-height: 500px; background: #fff; text-align: center;">
            <h1 style="color: #00a651; margin-bottom: 30px; font-size: 32px;"><?php echo $page_title; ?></h1>
            <p style="color: #888; font-size: 20px; font-weight: bold;">
                سيتم إضافة محتوى هذه الصفحة قريباً
            </p>
        </div>
    </div>
</div>

<?php include \'../../includes/footer.php\'; ?>';

    $dir = __DIR__ . '/' . $folder;
    $filepath = $dir . '/' . $filename;

    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($filepath, $content);
}

$about_pages = [
    ['file' => 'index.php', 'title' => 'عن الوزارة'],
    ['file' => 'goals.php', 'title' => 'أهداف ومهام الوزارة'],
    ['file' => 'organizational_structure.php', 'title' => 'الهيكل التنظيمي'],
    ['file' => 'history.php', 'title' => 'لمحة تاريخية'],
    ['file' => 'address.php', 'title' => 'عنوان الوزارة'],
    ['file' => 'reception_centers.php', 'title' => 'مراكز الاستقبال والتواصل الإلكتروني'],
    ['file' => 'news.php', 'title' => 'الأخبار'],
    ['file' => 'statements.php', 'title' => 'تصريحات المتحدث الأمني'],
    ['file' => 'manqoolat.php', 'title' => 'منصة منقولات'],
    ['file' => 'security_reports.php', 'title' => 'البلاغات الأمنية'],
    ['file' => 'forms.php', 'title' => 'النماذج الإلكترونية'],
    ['file' => 'faqs.php', 'title' => 'الأسئلة الشائعة'],
    ['file' => 'regulations.php', 'title' => 'أنظمة وتعليمات'],
    ['file' => 'contact.php', 'title' => 'اتصل بنا'],
    ['file' => 'webmail.php', 'title' => 'البريد الإلكتروني لمنسوبي الوزارة']
];

$sectors_pages = [
    ['file' => 'index.php', 'title' => 'القطاعات'],
    ['file' => 'diwan.php', 'title' => 'ديوان وزارة الداخلية'],
    ['file' => 'security_capabilities.php', 'title' => 'وكالة وزارة الداخلية للقدرات الأمنية'],
    ['file' => 'military_affairs.php', 'title' => 'وكالة وزارة الداخلية للشؤون العسكرية'],
    ['file' => 'regions_affairs.php', 'title' => 'وكالة وزارة الداخلية لشؤون المناطق'],
    ['file' => 'interpol.php', 'title' => 'الإدارة العامة للشرطة الدولية'],
    ['file' => 'expats_affairs.php', 'title' => 'الإدارة العامة لشؤون الوافدين'],
    ['file' => 'weapons_explosives.php', 'title' => 'الإدارة العامة للأسلحة والمتفجرات'],
    ['file' => 'crime_research.php', 'title' => 'مركز أبحاث مكافحة الجريمة'],
    ['file' => 'public_security.php', 'title' => 'المديرية العامة للأمن العام'],
    ['file' => 'traffic_admin.php', 'title' => 'الإدارة العامة للمرور'],
    ['file' => 'road_security.php', 'title' => 'القوات الخاصة لأمن الطرق'],
    ['file' => 'civil_defense.php', 'title' => 'المديرية العامة للدفاع المدني'],
    ['file' => 'passports.php', 'title' => 'المديرية العامة للجوازات'],
    ['file' => 'civil_affairs.php', 'title' => 'وكالة وزارة الداخلية للأحوال المدنية'],
    ['file' => 'king_fahd_college.php', 'title' => 'كلية الملك فهد الأمنية'],
    ['file' => 'prisons.php', 'title' => 'المديرية العامة للسجون'],
    ['file' => 'nic.php', 'title' => 'مركز المعلومات الوطني'],
    ['file' => 'security_operations.php', 'title' => 'المركز الوطني للعمليات الأمنية'],
    ['file' => 'security_protection.php', 'title' => 'القوات الخاصة للأمن والحماية'],
    ['file' => 'environmental_security.php', 'title' => 'القوات الخاصة للأمن البيئي'],
    ['file' => 'facilities_security.php', 'title' => 'قوات أمن المنشآت'],
    ['file' => 'anti_narcotics.php', 'title' => 'المديرية العامة لمكافحة المخدرات'],
    ['file' => 'border_guard.php', 'title' => 'المديرية العامة لحرس الحدود'],
    ['file' => 'mujahideen.php', 'title' => 'الإدارة العامة للمجاهدين'],
    ['file' => 'medical_services.php', 'title' => 'الادارة العامة للخدمات الطبية'],
    ['file' => 'officers_club.php', 'title' => 'الإدارة العامة لأندية منسوبي الوزارة']
];

$emirates_pages = [
    ['file' => 'index.php', 'title' => 'الإمارات'],
    ['file' => 'riyadh.php', 'title' => 'إمارة منطقة الرياض'],
    ['file' => 'makkah.php', 'title' => 'إمارة منطقة مكة المكرمة'],
    ['file' => 'madinah.php', 'title' => 'إمارة منطقة المدينة المنورة'],
    ['file' => 'eastern.php', 'title' => 'إمارة المنطقة الشرقية'],
    ['file' => 'jowf.php', 'title' => 'إمارة منطقة الجوف'],
    ['file' => 'baha.php', 'title' => 'إمارة منطقة الباحة'],
    ['file' => 'asir.php', 'title' => 'إمارة منطقة عسير'],
    ['file' => 'qasim.php', 'title' => 'إمارة منطقة القصيم'],
    ['file' => 'hail.php', 'title' => 'إمارة منطقة حائل'],
    ['file' => 'tabuk.php', 'title' => 'إمارة منطقة تبوك'],
    ['file' => 'northern.php', 'title' => 'إمارة منطقة الحدود الشمالية'],
    ['file' => 'jazan.php', 'title' => 'إمارة منطقة جازان'],
    ['file' => 'najran.php', 'title' => 'إمارة منطقة نجران']
];

$media_pages = [
    ['file' => 'index.php', 'title' => 'المركز الإعلامي']
];

foreach ($about_pages as $p)
    createPage('about', $p['file'], $p['title'], 'about');
foreach ($sectors_pages as $p)
    createPage('sectors', $p['file'], $p['title'], 'sectors');
foreach ($emirates_pages as $p)
    createPage('emirates', $p['file'], $p['title'], 'emirates');
foreach ($media_pages as $p)
    createPage('media', $p['file'], $p['title'], 'media');

echo "Comprehensive pages created.";
?>
