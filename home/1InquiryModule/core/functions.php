<?php

/**
 * تحويل الأرقام العربية الشرقية (٠-٩) والفارسية (۰-۹) إلى أرقام إنجليزية (0-9)
 */
if (!function_exists('toWesternDigits')) {
    function toWesternDigits($str)
    {
        if (empty($str))
            return $str;
        $str = (string)$str;
        $str = preg_replace_callback('/[\x{0660}-\x{0669}]/u', function ($m) {
            return (string)(mb_ord($m[0], 'UTF-8') - 0x0660);
        }, $str);
        $str = preg_replace_callback('/[\x{06F0}-\x{06F9}]/u', function ($m) {
            return (string)(mb_ord($m[0], 'UTF-8') - 0x06F0);
        }, $str);
        return $str;
    }
}

if (!function_exists('format_serial_number')) {
    /**
     * Formats a given value into a 10-digit serial number with service prefix.
     * @param mixed $value The input value (ID, or existing SN).
     * @param string $serviceKey The service key to determine the prefix.
     * @return string The formatted 10-digit serial number.
     */
    function format_serial_number($value, $serviceKey = 'marriage')
    {
        if (empty($value) || $value === '---')
            return '---';

        $prefixes = [
            'marriage' => '70',
            'civil_affairs' => '11',
            'civil_inquiry' => '11',
            'recruitment' => '20',
            'labor' => '50',
            'change_profession' => '30',
            'profession_changes' => '30',
            'followup' => '40',
            'followup_requests' => '40',
            'family_visa' => '90',
            'business_visit' => '60',
            'commercial_visa' => '60',
            'tourist_visa' => '80',
            'default' => '10'
        ];

        $prefix = $prefixes[$serviceKey] ?? $prefixes['default'];

        // Remove non-numeric characters
        $numericPart = preg_replace('/[^0-9]/', '', (string)$value);

        // Take the last 8 digits and prepend the prefix to make 10 digits
        if (strlen($numericPart) >= 8) {
            $base = substr($numericPart, -8);
        }
        else {
            $base = str_pad($numericPart, 8, '0', STR_PAD_LEFT);
        }

        return $prefix . $base;
    }
}

if (!function_exists('generate_export_number')) {
    /**
     * Generates a unique export number with database validation.
     * @param string $prefix The service prefix.
     * @param string $tableName The table name to check for uniqueness.
     * @return string The generated export number.
     */
    function generate_export_number($prefix, $tableName = null)
    {
        global $pdo;

        $maxAttempts = 10;
        $attempt = 0;

        do {
            if ($prefix === '1900') {
                // 4 digits '1900' + 7 digits = 11 digits
                $exportNumber = $prefix . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            }
            else {
                // Prefix (1) + Timestamp seconds (5) + Microseconds (4) + Random (2) = 12 digits
                $exportNumber = $prefix . substr(time(), -5) . substr(microtime(), 2, 4) . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            }

            // If no table name provided or no database connection, return immediately
            if (!$tableName || !USE_DATABASE || !isset($pdo)) {
                return $exportNumber;
            }

            // Check if this number already exists in the database
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$tableName` WHERE export_number = ?");
                $stmt->execute([$exportNumber]);
                $count = $stmt->fetchColumn();

                if ($count == 0) {
                    return $exportNumber; // Unique number found
                }
            }
            catch (PDOException $e) {
                // If there's an error, just return the generated number
                // error_log("Error checking export number uniqueness: " . $e->getMessage()); 
                return $exportNumber;
            }

            $attempt++;
        } while ($attempt < $maxAttempts);

        // If we couldn't generate a unique number after max attempts, add timestamp
        return $prefix . time() . rand(1000, 9999);
    }
}

if (!function_exists('render_form_group')) {
    /**
     * Renders a Bootstrap form group with a label, input, and error message.
     *
     * @param string $label The label text for the form group.
     * @param string $name The name and id for the input element.
     * @param string $type The type of the input element (e.g., 'text', 'select', 'textarea').
     * @param string $placeholder The placeholder text.
     * @param mixed $value The current value of the input.
     * @param array $options For 'select' type, an associative array of options. For other types, an array of attributes.
     * @param string|null $error The error message to display.
     * @param bool $required Whether the field is required.
     * @param string|null $class An optional additional class for the input.
     */
    function render_form_group($label, $name, $type = 'text', $placeholder = '', $value = '', $options = [], $error = null, $required = false, $class = null)
    {
        // Generate unique ID for the input
        $inputId = htmlspecialchars($name);

        echo '<div class="form-group">';
        echo '<label for="' . $inputId . '" class="form-label">' . htmlspecialchars($label) . ($required ? ' <span class="text-danger">*</span>' : '') . '</label>';

        $attributes = 'id="' . $inputId . '" name="' . htmlspecialchars($name) . '" class="form-control' . ($error ? ' error-input' : '') . ($class ? ' ' . $class : '') . '" placeholder="' . htmlspecialchars($placeholder) . '"' . ($required ? ' required' : '');

        // Handle additional attributes for non-select inputs
        if ($type !== 'select' && is_array($options)) {
            foreach ($options as $attr => $attr_value) {
                if (is_bool($attr_value)) {
                    if ($attr_value)
                        $attributes .= ' ' . $attr;
                }
                else {
                    $attributes .= ' ' . htmlspecialchars($attr) . '="' . htmlspecialchars($attr_value) . '"';
                }
            }
        }

        if ($type === 'select') {
            echo '<select ' . $attributes . '>';
            if (is_array($options)) {
                foreach ($options as $option_value => $option_label) {
                    $selected = ($option_value == $value) ? ' selected' : '';
                    echo '<option value="' . htmlspecialchars($option_value) . '"' . $selected . '>' . htmlspecialchars($option_label) . '</option>';
                }
            }
            echo '</select>';
        }
        elseif ($type === 'textarea') {
            echo '<textarea ' . $attributes . ' rows="3">' . htmlspecialchars($value) . '</textarea>';
        }
        elseif ($type === 'datalist') {
            $listId = $inputId . '_list';
            echo '<input list="' . $listId . '" ' . $attributes . ' value="' . htmlspecialchars($value) . '">';
            echo '<datalist id="' . $listId . '">';
            if (is_array($options)) {
                foreach ($options as $option_value => $option_label) {
                    echo '<option value="' . htmlspecialchars($option_value) . '">' . htmlspecialchars($option_label) . '</option>';
                }
            }
            echo '</datalist>';
        }
        else {
            echo '<input type="' . htmlspecialchars($type) . '" value="' . htmlspecialchars($value) . '" ' . $attributes . '>';
        }

        if ($error) {
            echo '<p class="error-message">' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
}

if (!function_exists('validate_required_fields')) {
    function validate_required_fields($fields, $labels)
    {
        $errors = [];
        foreach ($fields as $field => $value) {
            if (empty($value)) {
                $errors[$field] = 'حقل "' . ($labels[$field] ?? $field) . '" مطلوب.';
            }
        }
        return $errors;
    }
}

if (!function_exists('format_time_ago_arabic')) {
    /**
     * Formats a datetime string to show how long ago it was in Arabic.
     *
     * @param string $datetime The datetime string to format.
     * @return string The formatted time ago string in Arabic.
     */
    function format_time_ago_arabic($datetime)
    {
        if (empty($datetime)) {
            return 'غير متوفر';
        }

        try {
            $time = strtotime($datetime);
            if ($time === false) {
                return $datetime;
            }

            $diff = time() - $time;

            if ($diff < 60) {
                return 'منذ لحظات';
            }
            elseif ($diff < 3600) {
                $minutes = floor($diff / 60);
                return "منذ {$minutes} دقيقة";
            }
            elseif ($diff < 86400) {
                $hours = floor($diff / 3600);
                return "منذ {$hours} ساعة";
            }
            elseif ($diff < 604800) {
                $days = floor($diff / 86400);
                return "منذ {$days} يوم";
            }
            elseif ($diff < 2592000) {
                $weeks = floor($diff / 604800);
                return "منذ {$weeks} أسبوع";
            }
            elseif ($diff < 31536000) {
                $months = floor($diff / 2592000);
                return "منذ {$months} شهر";
            }
            else {
                $years = floor($diff / 31536000);
                return "منذ {$years} سنة";
            }
        }
        catch (Exception $e) {
            return $datetime;
        }
    }
}

if (!function_exists('set_flash_message')) {
    /**
     * Set a flash message in the session
     * @param string $message The message to display
     * @param string $type The type: 'success', 'error', 'warning', 'info'
     */
    function set_flash_message($message, $type = 'info')
    {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
}

if (!function_exists('render_flash_messages')) {
    /**
     * Renders a script tag to display a toast notification if a flash message is set in the session.
     */
    function render_flash_messages()
    {
        if (isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            $type = $_SESSION['flash_type'] ?? 'info';

            // Sanitize for JavaScript output
            $message_js = addslashes($message);
            $type_js = addslashes($type);

            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof showToast === 'function') {
                            showToast('$message_js', '$type_js');
                        } else {
                            console.error('showToast function not available.');
                        }
                    });
                  </script>";

            // Clear the flash message after rendering
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
        }
    }
}

if (!function_exists('generateRandomCaptcha')) {
    /**
     * Generates a random numeric CAPTCHA string of a given length.
     * @param int $length The length of the CAPTCHA string.
     * @return string The generated CAPTCHA string.
     */
    function generateRandomCaptcha($length = 4)
    {
        return substr(str_shuffle("0123456789"), 0, $length);
    }
}

if (!function_exists('convertToHijri')) {
    /**
     * Converts a Gregorian date string to a Hijri date string.
     * Requires the Intl PHP extension.
     * @param string $gregorianDate The Gregorian date string (e.g., 'YYYY-MM-DD').
     * @return string The formatted Hijri date string or the original date on failure.
     */
    function convertToHijri($gregorianDate)
    {
        if (empty($gregorianDate) || $gregorianDate === '---') {
            return $gregorianDate;
        }

        // Ensure we work with Western digits for the heuristic check
        $gregorianDate = toWesternDigits($gregorianDate);

        // Heuristic: If it looks like a date with a year < 1900, it's likely already Hijri.
        // Format: YYYY-MM-DD or DD/MM/YYYY (Allow times/trailing space by removing $)
        if (preg_match('/^(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/', (string)$gregorianDate, $matches)) {
            $year = (int)$matches[1];
            if ($year < 1900)
                return toWesternDigits("{$matches[3]}/{$matches[2]}/{$year}");
        }
        if (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/', (string)$gregorianDate, $matches)) {
            $year = (int)$matches[3];
            if ($year < 1900)
                return toWesternDigits("{$matches[1]}/{$matches[2]}/{$year}");
        }

        $timestamp = is_numeric($gregorianDate) ? $gregorianDate : strtotime($gregorianDate);
        if (!$timestamp)
            return (string)$gregorianDate;

        if (class_exists('IntlDateFormatter')) {
            // Using 'en-SA' to get English numerals (Western digits) for the Hijri date
            $formatter = new IntlDateFormatter('en-SA-u-ca-islamic', IntlDateFormatter::SHORT, IntlDateFormatter::NONE, 'Asia/Riyadh', IntlDateFormatter::TRADITIONAL, 'dd/MM/yyyy');
            $formatted = $formatter->format($timestamp);
            if (!empty($formatted)) {
                return toWesternDigits($formatted);
            }
        }

        // Fallback: Accurate algorithm for Hijri conversion
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        if (($year > 1582) || (($year == 1582) && ($month > 10)) || (($year == 1582) && ($month == 10) && ($day > 14))) {
            $jd = floor((1461 * ($year + 4800 + floor(($month - 14) / 12))) / 4) +
                floor((367 * ($month - 2 - 12 * (floor(($month - 14) / 12)))) / 12) -
                floor((3 * (floor(($year + 4900 + floor(($month - 14) / 12)) / 100))) / 4) + $day - 32075;
        }
        else {
            $jd = 367 * $year - floor((7 * ($year + 5001 + floor(($month - 9) / 7))) / 4) + floor((275 * $month) / 9) + $day + 1729777;
        }

        $l = $jd - 1948440 + 10632;
        $n = floor(($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = (floor((10985 - $l) / 5316)) * (floor((50 * $l) / 17719)) + (floor($l / 5670)) * (floor((43 * $l) / 15238));
        $l = $l - (floor((30 - $j) / 15)) * (floor((17719 * $j) / 50)) - (floor($j / 16)) * (floor((15238 * $j) / 43)) + 29;
        $m = floor((24 * $l) / 709);
        $d = $l - floor((709 * $m) / 24);
        $y = 30 * $n + $j - 30;

        $res = str_pad($d, 2, '0', STR_PAD_LEFT) . "/" . str_pad($m, 2, '0', STR_PAD_LEFT) . "/" . $y;
        return toWesternDigits($res);
    }
}


if (!function_exists('generate_csrf_token')) {
    /**
     * Generates a CSRF token and stores it in the session.
     * @return string The generated CSRF token.
     */
    function generate_csrf_token()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verify_csrf_token')) {
    /**
     * Verifies a CSRF token against the one stored in the session.
     * @param string $token The token to verify.
     * @return bool True if the token is valid, false otherwise.
     */
    function verify_csrf_token($token)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

/**
 * Returns a styled HTML badge for a given status string.
 */
if (!function_exists('get_status_badge')) {
    function get_status_badge($status)
    {
        if ($status === null || trim((string)$status) === '' || $status === '---') {
            return '---';
        }

        $s = trim((string)$status);
        
        // Default colors
        $bgColor = '#e0e0e0';
        $textColor = '#444';
        
        // Define color mapping based on keywords
        if (mb_stripos($s, 'موافق') !== false || mb_stripos($s, 'Approved') !== false || mb_stripos($s, 'Accepted') !== false || mb_stripos($s, 'مقبول') !== false || mb_stripos($s, 'فعال') !== false) {
            $bgColor = '#dcfce7'; // green-100
            $textColor = '#166534'; // green-800
        } elseif (mb_stripos($s, 'تحت') !== false || mb_stripos($s, 'قيد') !== false || mb_stripos($s, 'Processing') !== false || mb_stripos($s, 'انتظار') !== false || mb_stripos($s, 'مستلم') !== false) {
            $bgColor = '#fef9c3'; // yellow-100
            $textColor = '#854d0e'; // yellow-800
        } elseif (mb_stripos($s, 'مرفوض') !== false || mb_stripos($s, 'Rejected') !== false || mb_stripos($s, 'ملغي') !== false) {
            $bgColor = '#fee2e2'; // red-100
            $textColor = '#991b1b'; // red-800
        }

        return '<span class="status-badge" style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: bold; background-color: ' . $bgColor . '; color: ' . $textColor . '; white-space: nowrap;">' . htmlspecialchars($s) . '</span>';
    }
}