<?php
// views/inquiry.php - صفحة الاستعلام الديناميكية بتصميم جديد
// تم دمج تصميم Tailwind مع منطق PHP الحالي

// التأكد من بدء الجلسة
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$message = '';
$messageType = ''; // 'error' or 'success'

// إنشاء رمز تحقق جديد إذا لم يكن موجوداً في الجلسة
if (!isset($_SESSION['captcha'])) {
    require_once 'includes/functions.php';
    $_SESSION['captcha'] = generateRandomCaptcha(4);
}

// معالجة نموذج الاستعلام عند إرساله
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'inquiry') {
    require_once 'includes/database.php';
    require_once 'includes/functions.php';
    
    if (USE_DATABASE && $pdo) {
        try {
            // التحقق من رمز التحقق
            if (!isset($_POST['captcha']) || !isset($_SESSION['captcha']) || strtolower($_POST['captcha']) != strtolower($_SESSION['captcha'])) {
                $message = "رمز التحقق غير صحيح. يرجى المحاولة مرة أخرى.";
                $messageType = 'error';
                unset($_SESSION['captcha']); // مسح الرمز بعد المحاولة الخاطئة
                $_SESSION['captcha'] = generateRandomCaptcha(4); // توليد رمز جديد فوراً
            } else {
                unset($_SESSION['captcha']); // مسح الرمز بعد التحقق الناجح

                // استخدام النظام الذكي للبحث
                require_once 'smart_inquiry.php';
                $inquiry = new SmartInquiry($pdo);
                
                $nationalId = trim($_POST['idNum']);
                $issueNumber = trim($_POST['issueNum']);
                
                $result = $inquiry->smartSearch($nationalId, $issueNumber);
                
                if ($result['success']) {
                    // حفظ النتيجة في الجلسة
                    $_SESSION['inquiry_result'] = $result;
                    // إعادة التوجيه إلى صفحة النتائج
                    header('Location: index.php?page=inquiry_result');
                    exit;
                } else {
                    $message = $result['message'];
                    $messageType = 'error';
                    $_SESSION['captcha'] = generateRandomCaptcha(4);
                }
            }
        } catch (PDOException $e) {
            $message = "حدث خطأ في قاعدة البيانات: " . $e->getMessage();
            $messageType = 'error';
        }
    } else {
        $message = "تم استلام طلبك بنجاح (وضع الاختبار).";
        $messageType = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>نظام الاستعلام - SOSO</title>
    <script src="public/js/tailwindcss.js"></script>
    <link rel="stylesheet" href="public/css/all.min.css">
    <link rel="stylesheet" href="public/css/fonts.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #fcfcfc;
            -webkit-font-smoothing: antialiased;
        }
        * {
            font-weight: 700;
        }
        input:focus {
            outline: none;
            border-color: #059669 !important;
            box-shadow: 0 0 0 1px #059669;
        }
        .captcha-box {
            position: relative;
            background: #e0e0e0;
            border: 1px solid #6b7280;
        }
        .captcha-lines {
            position: absolute;
            inset: 0;
            opacity: 0.4;
            pointer-events: none;
            overflow: hidden;
        }
        .captcha-line-1 {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background: black;
            transform: rotate(-3deg);
        }
        .captcha-line-2 {
            position: absolute;
            top: 25%;
            left: 0;
            width: 100%;
            height: 1px;
            background: black;
            transform: rotate(6deg);
        }
        .captcha-code {
            font-size: 2.25rem;
            font-weight: 900;
            letter-spacing: 0.1em;
            font-style: italic;
            color: #1f2937;
            user-select: none;
            position: relative;
            z-index: 10;
        }
        .btn-primary {
            background-color: #10b981;
            transition: all 0.3s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .btn-primary:hover {
            background-color: #059669;
        }
        .btn-primary:active {
            transform: scale(0.98);
        }
        .required-field {
            border: 1px dashed #d1d5db;
            padding: 8px;
            text-align: right;
            margin-bottom: 2rem;
        }
        .input-field {
            border: 1px solid #059669;
            border-radius: 2px;
            padding: 8px;
            text-align: right;
            font-size: 1.125rem;
            font-weight: 900;
            color: #1f2937;
        }
        .input-field::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }
        .refresh-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: #0ea5e9;
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .refresh-btn:hover {
            color: #0284c7;
        }
        .refresh-btn:active {
            transform: scale(0.95);
        }
        .refresh-icon {
            font-size: 1.5rem;
            transition: transform 0.3s;
        }
        .rotating {
            animation: rotate 0.5s linear;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg bg-white shadow-sm border border-gray-200 p-8 md:p-10 rounded-sm">
        <!-- صندوق التنبيه العلوي -->
        <div class="required-field w-full">
            <span class="text-red-600 font-black text-2xl ml-1">*</span>
            <span class="text-gray-800 font-black text-base">حقول مطلوبة</span>
        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-6 p-4 rounded-sm border <?php echo $messageType === 'error' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-green-50 border-green-200 text-green-700'; ?>">
                <p class="text-sm font-black"><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>

        <form id="queryForm" method="post" action="index.php?page=inquiry#inquiry" class="space-y-8 flex flex-col items-start w-full">
            <input type="hidden" name="action" value="inquiry">
            
            <!-- حقل رقم الهوية -->
            <div class="flex flex-col items-start w-full">
                <label for="idNum" class="text-gray-800 font-black mb-2 text-xl">
                    رقم الهوية <span class="text-red-600 font-black">*</span>
                </label>
                <input
                    type="text"
                    id="idNum"
                    name="idNum"
                    placeholder="أدخل رقم الهوية"
                    class="input-field w-full md:w-64"
                    required
                    inputmode="numeric"
                />
            </div>

            <!-- حقل رقم الصادر -->
            <div class="flex flex-col items-start w-full">
                <label for="issueNum" class="text-gray-800 font-black mb-2 text-xl">
                    رقم الصادر <span class="text-red-600 font-black">*</span>
                </label>
                <input
                    type="text"
                    id="issueNum"
                    name="issueNum"
                    placeholder="أدخل الرقم الصادر"
                    class="input-field w-full md:w-64"
                    required
                    maxlength="12"
                />
                <p class="text-xs text-gray-500 mt-2">يتكون من 10 إلى 12 رقم</p>
            </div>

            <!-- قسم الرمز المرئي -->
            <div class="flex items-center gap-4 py-2 w-full justify-start">
                <!-- صندوق الرمز المرئي -->
                <div class="captcha-box w-52 h-14 flex items-center justify-center overflow-hidden">
                    <div class="captcha-lines">
                        <div class="captcha-line-1"></div>
                        <div class="captcha-line-2"></div>
                    </div>
                    <span id="captcha_display" class="captcha-code"><?php echo htmlspecialchars($_SESSION['captcha']); ?></span>
                </div>

                <!-- زر تحديث CAPTCHA -->
                <button 
                    type="button" 
                    id="refreshCaptchaBtn"
                    class="refresh-btn"
                    title="تحديث الرمز"
                    onclick="refreshCaptcha()"
                >
                    <i class="fas fa-sync-alt refresh-icon" id="refreshIcon"></i>
                </button>
            </div>

            <!-- حقل إدخال الرمز المرئي -->
            <div class="flex flex-col items-start w-full">
                <label for="captcha" class="text-gray-800 font-black mb-2 text-xl">
                    الرمز المرئي <span class="text-red-600 font-black">*</span>
                </label>
                <input
                    type="text"
                    id="captcha"
                    name="captcha"
                    class="input-field w-full md:w-64 text-center"
                    placeholder="أدخل الرمز أعلاه"
                    required
                    autocomplete="off"
                />
            </div>

            <!-- أزرار العمليات -->
            <div class="w-full space-y-4 pt-6">
                <button
                    type="submit"
                    class="btn-primary w-full text-white font-black text-2xl py-3 rounded-sm"
                >
                    عرض
                </button>
                <button
                    type="reset"
                    id="clearBtn"
                    class="btn-primary w-full text-white font-black text-2xl py-3 rounded-sm"
                >
                    مسح
                </button>
            </div>
        </form>
    </div>

    <script>
    function refreshCaptcha() {
        const icon = document.getElementById('refreshIcon');
        icon.classList.add('rotating');
        
        // استدعاء AJAX للحصول على رمز جديد
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'generate_captcha_value.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('captcha_display').innerText = xhr.responseText;
                setTimeout(() => {
                    icon.classList.remove('rotating');
                }, 500);
            }
        };
        xhr.send();
    }

    // مسح الحقول (يتعامل معه زر reset تلقائياً، ولكن للتأكد من تحديث الكابتشا)
    document.getElementById('clearBtn').addEventListener('click', function() {
        setTimeout(refreshCaptcha, 50);
    });
    </script>
</body>
</html>
