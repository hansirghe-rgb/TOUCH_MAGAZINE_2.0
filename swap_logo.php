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
            $content = preg_replace($pattern_header, 'The Touch <span class="text-red ml-2 relative -top-2 text-4xl leading-none">.</span>', $content);
            $modified = true;
        }

        // Replace the Footer Logo
        $pattern_footer = '/The Touch <span class="text-red[^>]*?-top-1[^>]*?>\.<\/span>/m';
        if (preg_match($pattern_footer, $content)) {
            $content = preg_replace($pattern_footer, 'The Touch <span class="text-red ml-1 relative -top-1 text-3xl leading-none">.</span>', $content);
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
