<?php
$dir = new RecursiveDirectoryIterator(__DIR__);
$ite = new RecursiveIteratorIterator($dir);
$count = 0;

foreach ($ite as $file) {
    if ($file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        $modified = false;

        // Scale Up the Top Header Logo and Apply mix-blend-multiply to remove the white box visually
        $pattern_header = '/class="h-20 md:h-24 lg:h-28 w-auto object-contain mix-blend-multiply transform scale-125 origin-left"/';
        if (preg_match($pattern_header, $content)) {
            $content = preg_replace($pattern_header, 'class="h-20 md:h-24 lg:h-28 w-auto object-contain mix-blend-multiply transform scale-125 origin-left"', $content);
            $modified = true;
        }

        // Scale Up the Footer Logo
        $pattern_footer = '/class="h-16 md:h-20 w-auto object-contain mix-blend-multiply transform scale-125 origin-left"/';
        if (preg_match($pattern_footer, $content)) {
            $content = preg_replace($pattern_footer, 'class="h-16 md:h-20 w-auto object-contain mix-blend-multiply transform scale-125 origin-left"', $content);
            $modified = true;
        }

        if ($modified) {
            file_put_contents($path, $content);
            $count++;
        }
    }
}

echo "SUCCESS: Scaled up the logo by 3x and applied multiply blend mode to perfectly hide the white box across $count files.\n";
?>
