<?php
$dirs = ['api', 'admin/serves'];
$root = __DIR__;

foreach ($dirs as $dir) {
    $path = $root . '/' . $dir;
    if (!is_dir($path)) continue;
    
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            $modified = false;
            
            // Pattern to match $response['message'] = ... . $e->getMessage();
            // or $response['error'] = ... $e->getMessage();
            $pattern = "/(\\\$response\['(?:message|error)'\]\s*=\s*)(.*?)\\$e->getMessage\(\)(.*?);/is";
            
            if (preg_match($pattern, $content)) {
                // Replace with generic message securely
                $content = preg_replace($pattern, "$1'عذراً، حدث خطأ غير متوقع بالخادم، يرجى المحاولة لاحقاً.';", $content);
                $modified = true;
            }
            
            // Look for throw new Exception(..." . $e->getMessage()); 
            $pattern2 = "/throw\s+new\s+Exception\((.*?)\\$e->getMessage\(\)(.*?)\);/is";
            if (preg_match($pattern2, $content)) {
                $content = preg_replace($pattern2, "throw new Exception('عذراً، حدث خطأ غير متوقع بالخادم.');", $content);
                $modified = true;
            }

            if ($modified) {
                file_put_contents($file->getPathname(), $content);
                echo "Fixed errors in: " . $file->getPathname() . "\n";
            }
        }
    }
}
echo "Done.\n";
