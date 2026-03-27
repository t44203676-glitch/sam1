<?php
$file = 'includes/functions.php';
$content = file_get_contents($file);
$extra = "

if (!function_exists('isValidQuadrupleName')) {
    /**
     * Checks if a name consists of exactly four words.
     */
    function isValidQuadrupleName(\$name)
    {
        \$name = trim((string)\$name);
        if (empty(\$name))
            return false;
        // Split by any number of spaces
        \$parts = preg_split('/\s+/', \$name, -1, PREG_SPLIT_NO_EMPTY);
        return count(\$parts) === 4;
    }
}

if (!function_exists('isValid10Digit')) {
    /**
     * Checks if a value consists of exactly 10 digits.
     */
    function isValid10Digit(\$val)
    {
        if (\$val === null || \$val === '---' || \$val === '')
            return false;
        \$val = toWesternDigits(trim((string)\$val));
        return preg_match('/^\d{10}$/', \$val);
    }
}
";

if (strpos($content, 'isValidQuadrupleName') === false) {
    file_put_contents($file, $extra, FILE_APPEND);
    echo "Appended functions successfully.\n";
} else {
    echo "Functions already exist.\n";
}
