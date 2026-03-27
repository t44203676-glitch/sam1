<?php
// smart_inquiry.php - نظام الاستعلام الذكي
// يحدد نوع النموذج بناءً على رقم الصادر

require_once 'includes/database.php';

class SmartInquiry
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function validateIssueNumber($issueNumber)
    {
        return preg_match('/^\d+$/', $issueNumber);
    }

    /**
     * تحديد نوع الخدمة بناءً على الرقم الأول من الصادر
     * 0 = تصريح زواج
     * 1 = زيارة عائلية.
     * 2 = زيارة سياحية
     * 3 = زيارة تجارية
     * 4 = عمالة
     * 5 = إلغاء بلاغ هروب
     * 6 = تغيير مهنة
     * 7 = أحوال مدنية
     */
    public function getServiceType($issueNumber)
    {
        if (strpos($issueNumber, '1900') === 0) {
            return 'civil_affairs';
        }

        $firstDigit = $issueNumber[0];

        $serviceMap = [
            '0' => 'marriage_permit',
            '1' => 'family_visit',
            '2' => 'tourism_visit',
            '3' => 'business_visit',
            '4' => 'labor',
            '5' => 'followup',
            '6' => 'profession_change',
            '7' => 'civil_affairs', // backward compatibility
            '8' => 'recruitment', // --- تعديل: إضافة خدمة الاستقدام ---
        ];

        return $serviceMap[$firstDigit] ?? null;
    }

    /**
     * البحث في جدول محدد
     */
    private function searchInTable($nationalId, $issueNumber, $tableName, $serviceName, $permitType = null)
    {
        try {
            $baseQuery = "SELECT * FROM `$tableName` AS mp WHERE mp.national_id = ? AND mp.export_number = ?
            ";

            $params = [$nationalId, $issueNumber];

            if ($permitType) {
                $baseQuery .= " AND mp.permit_type = ?";
                $params[] = $permitType;
            }

            $stmt = $this->pdo->prepare($baseQuery);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // توحيد شكل البيانات لتتوافق مع صفحة النتائج
                $result['full_name'] = $result['applicant_name'] ?? 'غير متوفر';
                $result['request_date'] = $result['created_at'];
                $result['service_name'] = $serviceName;
                $result['issue_number'] = $result['export_number'];

                // Ensure these fields are available for marriage_inquiry_result.php
                $result['serial_number'] = $result['serial_number'] ?? '---';
                $result['wife_name'] = $result['wife_name'] ?? '---'; // Assuming the table row is for the wife
                $result['status'] = $result['status'] ?? '---';
                $result['remarks'] = $result['remarks'] ?? 'لا يوجد';

                // Set defaults if fields are missing or null
                $result['job_category'] = $result['job_category'] ?? 'غير متوفر';
                $result['nationality'] = $result['nationality'] ?? 'غير متوفر';
                $result['arrival_place'] = $result['arrival_place'] ?? 'غير متوفر';


                // ==================================================================
                // جلب البيانات المتعلقة بناءً على نوع الخدمة
                // ==================================================================
                $relatedDataMap = [
                    'marriage_permits' => ['fk' => 'marriage_permit_id', 'key' => 'related_partners'],
                    'family_visits' => ['fk' => 'family_visit_id', 'key' => 'related_members'],
                    'recruitment_requests' => ['fk' => 'recruitment_request_id', 'key' => 'recruited_persons'],
                    'business_visits' => ['fk' => 'business_visit_id', 'key' => 'related_data'],
                    'tourism_visits' => ['fk' => 'tourism_visit_id', 'key' => 'related_data'],
                    'labor_requests' => ['fk' => 'labor_request_id', 'key' => 'related_data'],
                    'followup_requests' => ['fk' => 'followup_request_id', 'key' => 'related_data'],
                    'profession_changes' => ['fk' => 'profession_change_id', 'key' => 'related_data'],
                    'civil_affairs_requests' => ['fk' => 'civil_affairs_request_id', 'key' => 'related_data'],
                ];

                if (isset($relatedDataMap[$tableName])) {
                    $fk = $relatedDataMap[$tableName]['fk'];
                    $key = $relatedDataMap[$tableName]['key'];

                    // Fetch related data (Visitors/Partners)
                    try {
                        $stmt_related = $this->pdo->prepare("SELECT * FROM related_data WHERE {$fk} = ?");
                        $stmt_related->execute([$result['id']]);
                        $related_items = $stmt_related->fetchAll(PDO::FETCH_ASSOC);
                        $result[$key] = $related_items;
                    }
                    catch (PDOException $e) {
                        log_error("Could not fetch related data for table `{$tableName}` with FK `{$fk}`: " . $e->getMessage(), __FILE__, __LINE__);
                        $result[$key] = [];
                    }
                }

                // --- Specific handling for Civil Affairs ---
                if ($serviceName === 'أحوال مدنية') {
                    return [
                        'success' => true,
                        'type' => 'nationality_issuance',
                        'data' => $result
                    ];
                }
                // --- نهاية التعديل ---


                return [
                    'success' => true,
                    'type' => $serviceName,
                    'data' => $result
                ];
            }

            log_error("No results found in table '{$tableName}' for national_id '{$nationalId}' and export_number '{$issueNumber}'.", __FILE__, __LINE__);
            return [
                'success' => false,
                'type' => $permitType,
                'message' => "لم يتم العثور على '{$serviceName}' بهذه البيانات"
            ];

        }
        catch (Exception $e) {
            return [
                'success' => false,
                'type' => $tableName,
                'message' => 'حدث خطأ في البحث في جدول ' . $tableName . ': ' . $e->getMessage()
            ];
        }
    }

    /**
     * البحث الشامل في جميع الجداول (احتياطي)
     */
    private function fallbackSearch($nationalId, $issueNumber, $excludeServiceType = null)
    {
        $serviceMap = [
            'marriage_permit' => ['table' => 'marriage_permits', 'title' => 'تصريح زواج'],
            'family_visit' => ['table' => 'family_visits', 'title' => 'زيارة عائلية'],
            'tourism_visit' => ['table' => 'tourism_visits', 'title' => 'زيارة سياحية'],
            'business_visit' => ['table' => 'business_visits', 'title' => 'زيارة تجارية'],
            'labor' => ['table' => 'labor_requests', 'title' => 'عمالة'],
            'followup' => ['table' => 'followup_requests', 'title' => 'التعقيب'],
            'profession_change' => ['table' => 'profession_changes', 'title' => 'تغيير مهنة'],
            'civil_affairs' => ['table' => 'civil_affairs_requests', 'title' => 'أحوال مدنية'],
            'recruitment' => ['table' => 'recruitment_requests', 'title' => 'استقدام'],
        ];

        foreach ($serviceMap as $type => $config) {
            // تخطي النوع الذي تم البحث فيه بالفعل
            if ($type === $excludeServiceType) {
                continue;
            }

            $result = $this->searchInTable($nationalId, $issueNumber, $config['table'], $config['title']);
            if ($result['success']) {
                return $result;
            }
        }

        return null;
    }

    /**
     * البحث الذكي - يحدد النوع تلقائياً ويبحث
     */
    public function smartSearch($nationalId, $issueNumber)
    {
        $nationalId = trim($nationalId);
        $issueNumber = trim($issueNumber);

        // التحقق من صحة رقم الصادر
        if (!$this->validateIssueNumber($issueNumber)) {
            return [
                'success' => false,
                'message' => 'رقم الصادر المدخل غير صحيح. يجب أن يتكون من أرقام فقط.'
            ];
        }

        // تحديد نوع الخدمة المبدئي
        $serviceType = $this->getServiceType($issueNumber);
        $initialResult = null;

        if ($serviceType) {
            // البحث حسب نوع الخدمة المتوقع
            switch ($serviceType) {
                case 'marriage_permit':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'marriage_permits', 'تصريح زواج');
                    break;
                case 'family_visit':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'family_visits', 'زيارة عائلية');
                    break;
                case 'tourism_visit':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'tourism_visits', 'زيارة سياحية');
                    break;
                case 'business_visit':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'business_visits', 'زيارة تجارية');
                    break;
                case 'labor':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'labor_requests', 'عمالة');
                    break;
                case 'followup':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'followup_requests', 'التعقيب');
                    break;
                case 'profession_change':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'profession_changes', 'تغيير مهنة');
                    break;
                case 'civil_affairs':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'civil_affairs_requests', 'أحوال مدنية');
                    break;
                case 'recruitment':
                    $initialResult = $this->searchInTable($nationalId, $issueNumber, 'recruitment_requests', 'استقدام');
                    break;
            }
        }

        // إذا نجح البحث المبدئي، انتهى الأمر
        if ($initialResult && $initialResult['success']) {
            return $initialResult;
        }

        // --- البحث الاحتياطي (Fallback) ---
        // إذا فشل البحث المبدئي أو لم يتم تحديد نوع الخدمة، نبحث في جميع الجداول الأخرى
        $fallbackResult = $this->fallbackSearch($nationalId, $issueNumber, $serviceType);

        if ($fallbackResult) {
            return $fallbackResult;
        }

        // --- محاولة البحث بالبيانات المعكوسة (Swap Check) ---
        // المستخدم قد يعكس إدخال رقم الهوية ورقم الصادر عن طريق الخطأ
        // نحاول البحث بتبديل القيمتين: نعتبر رقم الصادر المدخل هو رقم الهوية، ورقم الهوية المدخل هو رقم الصادر
        // لاحظ: نستخدم $issueNumber في مكان الهوية، و $nationalId في مكان الصادر

        // التحقق من صحة "رقم الصادر" الجديد (الذي هو في الأصل رقم هوية مدخل) كونه رقم صادر محتمل (رقم الصادر عادة 10 خانات)
        // وكذلك "رقم الهوية" الجديد

        // المحاولة الأولى: عكس القيم والبحث الذكي
        $swappedServiceType = $this->getServiceType($nationalId); // استخدام رقم الهوية المدخل كرقم صادر محتمل لتحديد نوع الخدمة
        if ($swappedServiceType) {
            // نحاول البحث في الجدول المتوقع للخدمة بالقيم المعكوسة
            $swappedTitle = '';
            $swappedTable = '';
            switch ($swappedServiceType) {
                case 'marriage_permit':
                    $swappedTable = 'marriage_permits';
                    $swappedTitle = 'تصريح زواج';
                    break;
                case 'civil_affairs':
                    $swappedTable = 'civil_affairs_requests';
                    $swappedTitle = 'أحوال مدنية';
                    break;
                case 'family_visit':
                    $swappedTable = 'family_visits';
                    $swappedTitle = 'زيارة عائلية';
                    break;
                case 'followup':
                    $swappedTable = 'followup_requests';
                    $swappedTitle = 'التعقيب';
                    break;
                case 'labor':
                    $swappedTable = 'labor_requests';
                    $swappedTitle = 'عمالة';
                    break;
            // ... يمكن إضافة البقية
            }

            if ($swappedTable) {
                $swapResult = $this->searchInTable($issueNumber, $nationalId, $swappedTable, $swappedTitle); // لاحظ التبديل في المعاملات
                if ($swapResult['success']) {
                    return $swapResult;
                }
            }
        }

        // المحاولة الثانية: البحث الشامل (Fallback) بالقيم المعكوسة
        $swapFallbackResult = $this->fallbackSearch($issueNumber, $nationalId); // لاحظ التبديل
        if ($swapFallbackResult) {
            return $swapFallbackResult;
        }


        // إذا فشل البحث في كل مكان
        $msg = 'لم يتم العثور على أي معاملة بهذه البيانات.';
        if ($initialResult && isset($initialResult['message'])) {
        // $msg = $initialResult['message']; 
        }

        return [
            'success' => false,
            'message' => $msg
        ];
    }
}

// معالجة الطلب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'smart_inquiry') {
    $inquiry = new SmartInquiry($pdo);

    $nationalId = trim($_POST['national_id'] ?? '');
    $issueNumber = trim($_POST['issue_number'] ?? '');

    $result = $inquiry->smartSearch($nationalId, $issueNumber);

    // حفظ النتيجة في الجلسة
    $_SESSION['inquiry_result'] = $result;

    // إعادة التوجيه إلى صفحة النتائج
    header('Location: index.php?page=inquiry_result');
    exit;
}
?>
