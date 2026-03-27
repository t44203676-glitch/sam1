<?php
require_once __DIR__ . '/../core/bootstrap.php';

if (!isset($request) || !is_array($request)) {
    if (isset($_SESSION['inquiry_result']['data'])) {
        $request = $_SESSION['inquiry_result']['data'];
    }
    else {
        header("Location: " . BASE_URL . "?error=no_data");
        exit();
    }
}

$export_number = $request['export_number'] ?? '---';
$rawDate = $request['created_at'] ?? date('Y-m-d');
$date_hijri = convertToHijri($rawDate);

$office_name = $request['emirate'] ?? 'وزارة العمل';
$reference_num = $request['issuance_number'] ?? '---';
$establishment = $request['establishment_name'] ?? '---';
$employer_num = $request['national_id'] ?? '---';
$owner_name = $request['owner_name'] ?? $request['applicant_name'] ?? '---';
$file_num = $request['record_number'] ?? '---';
$status   = $request['status'] ?? 'تمت الموافقة';

$visa_items = $request['related_data'] ?? $request['related_partners'] ?? $request['related_members'] ?? [];

function toArabicDigits($number)
{
    if ($number === null || $number === '')
        return $number;
    $strNumber = (string)$number;

    // 1. تحويل أي أرقام هندية موجودة مسبقاً في قاعدة البيانات إلى أرقام إنجليزية مؤقتاً
    // (لأن الطلبات الجديدة تُحفظ بأرقام هندية مباشرة، ونريد معالجتها ككيانات HTML لتجاوز فلتر النظام)
    $strNumber = preg_replace_callback('/[\x{0660}-\x{0669}]/u', function ($m) {
        return (string)(mb_ord($m[0], 'UTF-8') - 0x0660);
    }, $strNumber);

    $out = '';
    // 2. تحويل جميع الأرقام الإنجليزية (التي كانت أصلاً إنجليزية أو التي حولناها تواً) إلى رموز HTML
    for ($i = 0; $i < strlen($strNumber); $i++) {
        $char = $strNumber[$i];
        $ord = ord($char);
        if ($ord >= 48 && $ord <= 57) {
            // HTML entity &#1632; = ٠, &#1633; = ١, etc.
            // هذا الكود محمي من أي تعبث من محررات الأكواد ويتجاوز فلتر bootstrap.php
            $out .= '&#' . (1632 + $ord - 48) . ';';
        }
        else {
            $out .= $char;
        }
    }
    // 3. تغليف الرقم بخط Arial / Amiri إجبارياً مع تحديد اللغة بـ ar-EG لمنع المتصفح من تحويلها لأرقام نظام التشغيل
    return '<span lang="ar-EG" dir="rtl" style="font-family: \'Amiri\', Arial, sans-serif !important;">' . $out . '</span>';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>تذكرة مراجعة تأشيرات</title>
<link rel="stylesheet" href="<?php echo getInquiryAsset('css/all.min.css'); ?>">
<script src="<?php echo getInquiryAsset('js/JsBarcode.all.min.js'); ?>"></script>
<style>
@font-face {
    font-family: 'GE SS Two';
    src: url('<?php echo getInquiryAsset('fonts/GE_SS_Two_Light.otf'); ?>') format('opentype');
    font-weight: 300;
    font-style: normal;
    font-display: swap;
}
@font-face {
    font-family: 'GE SS Two';
    src: url('<?php echo getInquiryAsset('fonts/GE_SS_Two_Medium.otf'); ?>') format('opentype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}
@font-face {
    font-family: 'GE SS Two';
    src: url('<?php echo getInquiryAsset('fonts/GE_SS_Two_Medium.otf'); ?>') format('opentype');
    font-weight: bold;
    font-style: normal;
    font-display: swap;
}
body{
    margin:0;
    background:#f0f0f0; 
    font-family: 'GE SS Two', 'Traditional Arabic', 'Amiri', Arial, sans-serif;
    color: #000; 
    font-weight: bold;
}
@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
    @page { 
        margin: 5mm;
        size: A4 portrait;
    }
    body { 
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .no-print { display: none !important; }
    .page { 
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important; 
        box-shadow: none !important;
        min-height: unset !important;
        padding: 10mm !important;
        background-color: #e6e88f !important;
        zoom: 0.72;
    }
}
.page{
    width: 100%;
    max-width: 260mm;
    min-height:297mm;
    margin:20px auto;
    background-color:#e6e88f; 
    box-sizing:border-box;
    padding:10mm 15mm;
    position:relative;
    box-shadow: 0 0 10px rgba(0,0,0,0.1); 
}


.header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    position: relative;
    padding-bottom: 20px;
}
.logo-block{
    width:200px;
    text-align:center;
    background: transparent;
}
.logo-block img{
    width:190px;
    display:block;
    margin:auto;
    mix-blend-mode: multiply !important;
    filter: brightness(1.1) contrast(1.5) !important;
}
.logo-title{
    color:#1b5e20;
    font-size:22px;
}
.logo-sub{
    color:#1b5e20;
    font-size:17px;
    margin-top: -5px;
}
.center-block{
    flex:1;
    text-align:center;
    margin-top:100px; /* نزول العنوان والترويسة لأسفل قليلاً */
    margin-right: -40px; /* إزاحة طفيفة لليمين لتقارب الشعار */
}
.title{
    font-size:22px;
    font-weight:bold;
    text-decoration:underline;
    color: #000;
}
.subtitle{
    font-size:18px; 
    margin-top: 2px;
    color: #344861; 
    font-weight: bold;
    white-space: nowrap;
}
.barcode-block{
    width:250px;
    text-align:left;
}
#barcode{
    width:220px;
    height:25px;
    background: transparent;
    margin-bottom: 2px;
}
.meta{
    font-size:17px;
    font-weight:bold;
    line-height:1.4;
    margin-top:0px;
    color: #344861;
}
.meta div {
    margin-bottom: 2px;
}
.blue{ 
    color:#1e3a8a; 
    letter-spacing: 1px;
    font-family: 'Amiri', 'Traditional Arabic', Arial, sans-serif !important;
} 
.office-row{
    text-align: center;
    margin-top:5px;
    font-size:19px;
    font-weight:bold;
    color: #000;
}
.office-row .reference-line {
    margin-top: 2px;
}
.approval{
    text-align:right;
    font-size:18px;
    font-weight:bold;
    margin-top:35px;
    color: #000;
}
.owner-row{
    display:flex;
    justify-content:space-between;
    margin-top:20px;
    font-size:21px;
    font-weight:bold;
    color: #000;
}
.table-outer{
    margin-top:40px;
    border:1.5px solid #344861;
    padding:2px;
    position: relative;
}
.watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-35deg);
    font-size: 100px;
    color: rgba(0, 171, 103, 0.15);
    font-weight: bold;
    pointer-events: none;
    white-space: nowrap;
    z-index: 10;
    font-family: 'GE SS Two', Arial, sans-serif;
}
table{
    width:100%;
    border-collapse:collapse;
    border:2px solid #344861;
}
th{
    border:2px solid #344861;
    padding:16px 5px;
    font-size:21px;
    font-weight:bold; 
    color: #000; 
}
td{
    border-right:2px solid #344861;
    border-left:2px solid #344861;
    padding:15px 5px;
    font-size:20px;
    font-weight:bold;
    text-align:center;
    color: #344861;
    letter-spacing: 1px;
    font-family: 'Amiri', 'Traditional Arabic', Arial, sans-serif !important;
}
.footer{
    margin-top:35px;
    text-align:center;
    font-size:20px; 
    font-weight:bold;
    color: #6b3e1f;
}
.copy{
    margin-top:8px;
    text-align:center;
    font-size:19px; 
    font-weight:bold;
    color: #39437bff;
}
</style>
</head>
<body>
<div class="page" id="printable-area">
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof JsBarcode !== 'undefined') {
            JsBarcode("#barcode","<?php echo htmlspecialchars($export_number); ?>",{
                format:"CODE128",
                width:2.0,
                height:25,
                displayValue:false,
                background: "transparent",
                margin:0
            });
        }
    });
    </script>

<div class="header">

<div class="logo-block">
    <img id="ministry-logo" src="<?php echo getInquiryAsset('images/images2.ico'); ?>" alt="وزارة العمل" crossorigin="anonymous">
</div>

<div class="center-block" style="flex: 1; text-align: center; margin-top: 80px;">
    <div class="title">تذكرة مراجعة تأشيرات</div>
    <div class="no-print" style="margin-top: 10px;">
        <?php echo get_status_badge($status); ?>
    </div>
</div>

<div class="barcode-block" style="width: 250px; text-align: left; position: relative;">
    <svg id="barcode" style="color:#000;"></svg>
    <div class="meta">
        <div>رقم الصادر : <span class="blue"><?php echo toArabicDigits($export_number); ?></span></div>
        <div>التاريخ : <span class="blue"><?php echo toArabicDigits($date_hijri); ?></span></div>
    </div>
    <div class="subtitle" style="margin-top: 35px; font-size: 16px; font-weight: bold; white-space: nowrap;">نظام سداد للمدفوعات الحكومية</div>
</div>

</div>

<div class="office-row" style="margin-top: 15px; font-size: 19px; font-weight: bold; text-align: righ; position: relative;">
    <span class="office-name-part blue" style="white-space: nowrap;"><?php echo htmlspecialchars($office_name); ?></span>
    <div class="reference-line" style="position: absolute; left: 3%; top: 0; white-space: nowrap;">المرجع / <span class="blue"><?php echo toArabicDigits($reference_num); ?></span></div>
</div>

<div class="approval">
حيث تقررت الموافقة على طلب <span style="display:inline-block; margin:0 4px;">:</span> <span class="blue" style="display:inline-block;"><?php echo htmlspecialchars($establishment); ?></span>
</div>

<div class="owner-row" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; font-size: 19px; font-weight: bold;">
    <div style="flex: 1; text-align: right; padding-right: 5%;">رقم صاحب العمل : <span class="blue"><?php echo toArabicDigits($employer_num); ?></span></div>
    <div style="flex: 1; text-align: left; padding-left: 10%;">رقم الملف : <span class="blue"><?php echo toArabicDigits($file_num); ?></span></div>
</div>

<div style="width: 100%; display: flex; justify-content: flex-start; margin-top: 15px; font-size: 19px; font-weight: bold; color: #000;">
    <div style="width: 30%; margin-right: 10%; text-align: center;">
        المالك : <span class="blue"><?php echo htmlspecialchars($owner_name); ?></span>
    </div>
</div>

<div class="table-outer">
    <div class="watermark">نسخة للمراجع</div>
<table>
<tr>
<th style="width:15%;">العدد</th>
<th style="width:30%;">المهنة</th>
<th style="width:25%;">الجنسية</th>
<th style="width:30%;">جهة القدوم</th>
</tr>
<?php
$count = 1;
$min_rows = 5;

foreach ($visa_items as $item):
    $serialStr = str_pad($count, 3, '0', STR_PAD_LEFT);
    $serialAr = toArabicDigits($serialStr);
?>
<tr>
<td data-label="العدد"><?php echo $serialAr; ?></td>
<td data-label="المهنة"><?php echo htmlspecialchars($item['job_category'] ?? '---'); ?></td>
<td data-label="الجنسية"><?php echo htmlspecialchars($item['nationality'] ?? '---'); ?></td>
<td data-label="جهة القدوم"><?php echo htmlspecialchars($item['country'] ?? '---'); ?></td>
</tr>
<?php
    $count++;
endforeach;

?>

<?php
for ($i = $count; $i <= $min_rows; $i++): ?>
<tr>
<td style="height:40px;"></td>
<td style="height:40px;"></td>
<td style="height:40px;"></td>
<td style="height:40px;"></td>
</tr>
<?php
endfor; ?>
<tr>
<td style="height:150px;"></td>
<td style="height:150px;"></td>
<td style="height:150px;"></td>
<td style="height:150px;"></td>
</tr>
</table>
</div>


<div class="footer">
ملحوظة تأمل وزارة الخارجية مراجعة الممثليات المعنية بعد ثلاثة أيام من تاريخه
</div>

<div class="copy">
صورة الملف رقم ( )
</div>
</div>

<div class="no-print" id="actions-bar" style="text-align:center; margin: 30px auto; display:flex; justify-content:center; gap:15px; padding-bottom: 40px;">
    <button onclick="window.print()"
        style="background:#00AB67; color:#fff; border:none; padding:10px 28px; border-radius:4px; cursor:pointer; font-family:inherit; font-size:16px; font-weight:bold; display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg> طباعة
    </button>
    <button onclick="window.location.href='<?php echo BASE_URL; ?>'"
        style="background:#00AB67; color:#fff; border:none; padding:10px 28px; border-radius:4px; cursor:pointer; font-family:inherit; font-size:16px; font-weight:bold; display: flex; align-items: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> إنهاء
    </button>
</div>

<script>
// سكربت مستقل تماماً لتجاوز أي أخطاء في الـ Barcode
document.addEventListener("DOMContentLoaded", function() {
    function enforceArabicDigits(node) {
        if (node.nodeType === 3) { 
            if (/[0-9]/.test(node.nodeValue) || /[\u0660-\u0669]/.test(node.nodeValue)) {
                node.nodeValue = node.nodeValue
                    .replace(/0/g, '٠').replace(/1/g, '١').replace(/2/g, '٢')
                    .replace(/3/g, '٣').replace(/4/g, '٤').replace(/5/g, '٥')
                    .replace(/6/g, '٦').replace(/7/g, '٧').replace(/8/g, '٨')
                    .replace(/9/g, '٩');
                
                // إضافة الخط الاجباري لأن خط GE SS Two يطمس الأرقام الهندية
                if (node.parentElement) {
                    node.parentElement.style.setProperty('font-family', 'Amiri, Arial, sans-serif', 'important');
                }
            }
        } else if (node.nodeType === 1 && node.nodeName !== 'SCRIPT' && node.nodeName !== 'STYLE' && node.nodeName !== 'SVG') {
            for (let i = 0; i < node.childNodes.length; i++) {
                enforceArabicDigits(node.childNodes[i]);
            }
        }
    }
    // تأخير بسيط لضمان طغيان التغيير
    setTimeout(function() {
        enforceArabicDigits(document.body);
    }, 10);
});
</script>

</body>
</html>
