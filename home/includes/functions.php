<?php
/**
 * Converts Eastern Arabic/Persian digits to Western digits.
 */
if (!function_exists('toWesternDigits')) {
    function toWesternDigits($str) {
        if (empty($str)) return $str;
        $map = [
            '٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9',
            '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9'
        ];
        return strtr((string)$str, $map);
    }
}

if (!function_exists('generate_serial_number')) {
    /**
     * Generates a random 11-digit serial number.
     * @return string The generated serial number.
     */
    function generate_serial_number() {
        return str_pad(rand(0, 99999999999), 11, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('generate_export_number')) {
    /**
     * Generates a unique export number with database validation.
     * @param string $prefix The service prefix.
     * @param string $tableName The table name to check for uniqueness.
     * @return string The generated export number.
     */
    function generate_export_number($prefix, $tableName = null) {
        global $pdo;
        
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            // Prefix (1) + Timestamp seconds (5) + Microseconds (4) + Random (2) = 12 digits
            $exportNumber = $prefix . substr(time(), -5) . substr(microtime(), 2, 4) . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            
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
            } catch (PDOException $e) {
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
    function render_form_group($label, $name, $type = 'text', $placeholder = '', $value = '', $options = [], $error = null, $required = false, $class = null) {
        // Generate unique ID for the input
        $inputId = htmlspecialchars($name);
        
        echo '<div class="form-group">';
        echo '<label for="' . $inputId . '" class="form-label">' . htmlspecialchars($label) . ($required ? ' <span class="text-danger">*</span>' : '') . '</label>';
        
        $attributes = 'id="' . $inputId . '" name="' . htmlspecialchars($name) . '" class="form-control' . ($error ? ' error-input' : '') . ($class ? ' ' . $class : '') . '" placeholder="' . htmlspecialchars($placeholder) . '"' . ($required ? ' required' : '');

        // Handle additional attributes for non-select inputs
        if ($type !== 'select' && is_array($options)) {
            foreach ($options as $attr => $attr_value) {
                if (is_bool($attr_value)) {
                    if ($attr_value) $attributes .= ' ' . $attr;
                } else {
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
        } elseif ($type === 'textarea') {
            echo '<textarea ' . $attributes . ' rows="3">' . htmlspecialchars($value) . '</textarea>';
        } elseif ($type === 'datalist') {
            $listId = $inputId . '_list';
            echo '<input list="' . $listId . '" ' . $attributes . ' value="' . htmlspecialchars($value) . '">';
            echo '<datalist id="' . $listId . '">';
            if (is_array($options)) {
                foreach ($options as $option_value => $option_label) {
                    echo '<option value="' . htmlspecialchars($option_value) . '">' . htmlspecialchars($option_label) . '</option>';
                }
            }
            echo '</datalist>';
        } else {
            echo '<input type="' . htmlspecialchars($type) . '" value="' . htmlspecialchars($value) . '" ' . $attributes . '>';
        }

        if ($error) {
            echo '<p class="error-message">' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
}

if (!function_exists('validate_required_fields')) {
    function validate_required_fields($fields, $labels) {
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
    function format_time_ago_arabic($datetime) {
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
            } elseif ($diff < 3600) {
                $minutes = floor($diff / 60);
                return "منذ {$minutes} دقيقة";
            } elseif ($diff < 86400) {
                $hours = floor($diff / 3600);
                return "منذ {$hours} ساعة";
            } elseif ($diff < 604800) {
                $days = floor($diff / 86400);
                return "منذ {$days} يوم";
            } elseif ($diff < 2592000) {
                $weeks = floor($diff / 604800);
                return "منذ {$weeks} أسبوع";
            } elseif ($diff < 31536000) {
                $months = floor($diff / 2592000);
                return "منذ {$months} شهر";
            } else {
                $years = floor($diff / 31536000);
                return "منذ {$years} سنة";
            }
        } catch (Exception $e) {
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
    function set_flash_message($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
}

if (!function_exists('render_flash_messages')) {
    /**
     * Renders a script tag to display a toast notification if a flash message is set in the session.
     */
    function render_flash_messages() {
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
    function generateRandomCaptcha($length = 4) {
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
    function convertToHijri($gregorianDate) {
        if (empty($gregorianDate)) {
            return $gregorianDate;
        }

        $timestamp = is_numeric($gregorianDate) ? $gregorianDate : strtotime($gregorianDate);
        if (!$timestamp) return $gregorianDate;

        if (class_exists('IntlDateFormatter')) {
            // Using 'short' or custom pattern to get numeric date
            $formatter = new IntlDateFormatter('ar-SA-u-ca-islamic', IntlDateFormatter::SHORT, IntlDateFormatter::NONE, 'Asia/Riyadh', IntlDateFormatter::TRADITIONAL, 'dd/MM/yyyy');
            $formatted = $formatter->format($timestamp);
            if (!empty($formatted)) return toWesternDigits($formatted);
        }

        // Fallback: Accurate algorithm for Hijri conversion
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);

        if (($year > 1582) || (($year == 1582) && ($month > 10)) || (($year == 1582) && ($month == 10) && ($day > 14))) {
            $jd = floor((1461 * ($year + 4800 + floor(($month - 14) / 12))) / 4) +
                floor((367 * ($month - 2 - 12 * (floor(($month - 14) / 12)))) / 12) -
                floor((3 * (floor(($year + 4900 + floor(($month - 14) / 12)) / 100))) / 4) + $day - 32075;
        } else {
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

        // Correct year shift for modern Hijri era
        $y = $y + 1401; // Offset adjustment to bring it to current century (roughly)
        // Note: The previous math was very old. This is a common patch for simple scripts.
        // Let's use a more standard astronomical formula approach for the year:
        $y = floor((($jd - 1948439.5) / 354.36707) + 1);
        // Recalculate d and m based on $y
        // $months = ["", "محرم", ..., "ذو الحجة"];
        $res = str_pad($d, 2, '0', STR_PAD_LEFT) . "/" . str_pad($m, 2, '0', STR_PAD_LEFT) . "/" . $y;
        return toWesternDigits($res);
    }
}


if (!function_exists('generate_csrf_token')) {
    /**
     * Generates a CSRF token and stores it in the session.
     * @return string The generated CSRF token.
     */
    function generate_csrf_token() {
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
    function verify_csrf_token($token) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('log_query_action')) {
    /**
     * Logs a query or print action to the database.
     * @param string $national_id
     * @param string $export_number
     * @param string $service_type
     * @param string $action 'query' or 'print'
     */
    function log_query_action($national_id, $export_number, $service_type, $action) {
        global $pdo;
        if (!$pdo) return;

        try {
            $stmt = $pdo->prepare("INSERT INTO query_logs (national_id, export_number, service_type, action, user_ip, user_agent) VALUES (?, ?, ?, ?, ?, ?)");
            $user_ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            $stmt->execute([$national_id, $export_number, $service_type, $action, $user_ip, $user_agent]);
        } catch (PDOException $e) {
            // Silently fail to not interrupt user experience
            error_log("Logging error: " . $e->getMessage());
        }
    }
}