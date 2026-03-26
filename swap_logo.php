<?php
$dir = new RecursiveDirectoryIterator(__DIR__);
$ite = new RecursiveIteratorIterator($dir);
$count = 0;

foreach ($ite as $file) {
    if ($file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        $modified = false;

        // Replace the Top Header Logo (Regex fixed to just match the outer span block reliably)
        $pattern_header = '/The Touch <span class="text-red[^>]*?-top-2[^>]*?>\.<\/span>/m';
        if (preg_match($pattern_header, $content)) {
            $content = preg_replace($pattern_header, '<img src="images/touch_logo.png" alt="The Touch Magazine" class="h-8 md:h-10 w-auto object-contain">', $content);
            $modified = true;
        }

        // Replace the Footer Logo
        $pattern_footer = '/The Touch <span class="text-red[^>]*?-top-1[^>]*?>\.<\/span>/m';
        if (preg_match($pattern_footer, $content)) {
            $content = preg_replace($pattern_footer, '<img src="images/touch_logo.png" alt="The Touch Magazine" class="h-6 md:h-8 w-auto object-contain">', $content);
            $modified = true;
        }

        if ($modified) {
            file_put_contents($path, $content);
            $count++;
        }
    }
}

echo "SUCCESS: Replaced Text Logo with touch_logo.png in $count files.\n";
?>
