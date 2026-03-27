<?php
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'libraries/fpdf186/fpdf.php';
require_once 'FPDI-2.6.4/src/autoload.php';

use setasign\Fpdi\Fpdi;

if (!isset($_GET['id'])) {
    die('لم يتم تحديد رقم الطلب.');
}

$request_id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM marriage_permits WHERE id = ?"); // تم التوحيد
    $stmt->execute([$request_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        die('لم يتم العثور على الطلب.');
    }

    // سجل عملية الطباعة
    if (function_exists('log_query_action')) {
        $service_type = 'تصريح زواج'; 
        error_log("Attempting to log print for ID: " . ($request['national_id'] ?? 'N/A'));
        log_query_action($request['national_id'] ?? $request['husband_id'] ?? '---', $request['export_number'] ?? '---', $service_type, 'print');
    } else {
        error_log("log_query_action function NOT found in print.php");
    }

    // Create new PDF
    $pdf = new Fpdi();
    $pdf->AddPage();
    
    // Set the template file
    $templatePath = 'uploads/طباعه فقط -1-3-1.pdf';
    
    if (file_exists($templatePath)) {
        $pdf->setSourceFile($templatePath);
        // Import the first page
        $tplId = $pdf->importPage(1);
        // Use the imported page as the template
        $pdf->useTemplate($tplId, 0, 0, 210);
    } else {
        // Log warning but continue without template
        // log_error("Template file not found: $templatePath"); 
        // Just continue with blank page
    }

    // Add Tahoma font
    $pdf->AddFont('tahoma', '', 'tahoma.php');

    // Set font
    $pdf->SetFont('tahoma', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    // Set font
    // $pdf->SetRightToLeft(true); // ERROR: Method not defined in standard FPDF/FPDI

    // Add data to the PDF
    $data = [
        ['applicant_name', 120, 50],
        ['national_id', 120, 60],
        ['phone', 120, 70],
        ['service_number', 120, 80],
        ['service_desc', 120, 90],
        ['hijri_date', 120, 100],
        ['permit_type', 120, 110],
        ['emirate', 120, 120],
        ['approval_date', 120, 130],
        ['approval_time', 120, 140],
        ['attachments', 120, 150],
        ['record_number', 120, 160],
        ['issuance_number', 120, 170],
        ['submission_date', 120, 180],
        ['area', 120, 190],
        ['area_code', 120, 200],
        ['remarks', 120, 210],
    ];

    foreach ($data as $item) {
        $pdf->SetXY($item[1], $item[2]);
        $text = $request[$item[0]];
        $text = iconv('UTF-8', 'windows-1256', $text);
        $pdf->Cell(0, 10, $text);
    }

    // Output the PDF
    $pdf->Output();

} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
} catch (Exception $e) {
    die("حدث خطأ: " . $e->getMessage());
}
?>
