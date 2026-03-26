<?php
$dir = new RecursiveDirectoryIterator(__DIR__);
$ite = new RecursiveIteratorIterator($dir);
$count_logos = 0;

$header_text_logo = 'The Touch <span class="text-red ml-2 relative -top-2 text-4xl leading-none">.</span>';
$footer_text_logo = 'The Touch <span class="text-red ml-1 relative -top-1 text-3xl leading-none">.</span>';

foreach ($ite as $file) {
    if ($file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        $modified = false;

        // 1. REVERT LOGOS
        // Match the img tag we injected earlier
        $pattern_header_img = '/<img src="images\/touch_logo.png"[^>]*h-20 md:h-24[^>]*>/i';
        if (preg_match($pattern_header_img, $content)) {
            $content = preg_replace($pattern_header_img, $header_text_logo, $content);
            $modified = true;
        }

        $pattern_footer_img = '/<img src="images\/touch_logo.png"[^>]*h-16 md:h-20[^>]*>/i';
        if (preg_match($pattern_footer_img, $content)) {
            $content = preg_replace($pattern_footer_img, $footer_text_logo, $content);
            $modified = true;
        }
        
        // Also catch any unscaled ones just in case
        $pattern_old_hdr = '/<img src="images\/touch_logo.png"[^>]*class="h-8 md:h-10[^>]*>/i';
        if (preg_match($pattern_old_hdr, $content)) {
            $content = preg_replace($pattern_old_hdr, $header_text_logo, $content);
            $modified = true;
        }
        $pattern_old_ftr = '/<img src="images\/touch_logo.png"[^>]*class="h-6 md:h-8[^>]*>/i';
        if (preg_match($pattern_old_ftr, $content)) {
             $content = preg_replace($pattern_old_ftr, $footer_text_logo, $content);
             $modified = true;
        }

        // 2. FIX VIDEO EMBEDS
        // Match the block fetching $vid_url
        $video_logic_search = '$vid_url = $latest_podcast[\'video_url\'];';
        // OR in podcasts.php it might be $vid_url = $pod['video_url'];
        // Let's do a smart regex replacement for iframe setup
        $video_fix_code = '
                                $vid_url = $latest_podcast[\'video_url\'];
                                if (strpos($vid_url, \'youtube.com/watch?v=\') !== false) {
                                    parse_str(parse_url($vid_url, PHP_URL_QUERY), $vars);
                                    if(isset($vars[\'v\'])) $vid_url = "https://www.youtube.com/embed/" . $vars[\'v\'];
                                } elseif (strpos($vid_url, \'youtu.be/\') !== false) {
                                    $vid_url = "https://www.youtube.com/embed/" . ltrim(parse_url($vid_url, PHP_URL_PATH), \'/\');
                                } elseif (strpos($vid_url, \'vimeo.com/\') !== false && strpos($vid_url, \'player.vimeo\') === false) {
                                    $vid_url = "https://player.vimeo.com/video/" . ltrim(parse_url($vid_url, PHP_URL_PATH), \'/\');
                                }
        ';
        
        $pod_fix_code = '
                            $vid_url = $pod[\'video_url\'];
                            if (strpos($vid_url, \'youtube.com/watch?v=\') !== false) {
                                parse_str(parse_url($vid_url, PHP_URL_QUERY), $vars);
                                if(isset($vars[\'v\'])) $vid_url = "https://www.youtube.com/embed/" . $vars[\'v\'];
                            } elseif (strpos($vid_url, \'youtu.be/\') !== false) {
                                $vid_url = "https://www.youtube.com/embed/" . ltrim(parse_url($vid_url, PHP_URL_PATH), \'/\');
                            } elseif (strpos($vid_url, \'vimeo.com/\') !== false && strpos($vid_url, \'player.vimeo\') === false) {
                                $vid_url = "https://player.vimeo.com/video/" . ltrim(parse_url($vid_url, PHP_URL_PATH), \'/\');
                            }
        ';

        if (basename($path) === 'index.php') {
            if (strpos($content, $video_logic_search) !== false && strpos($content, 'parse_url') === false) {
                $content = str_replace('                                $vid_url = $latest_podcast[\'video_url\'];', $video_fix_code, $content);
                $modified = true;
            }
        }
        
        if (basename($path) === 'podcasts.php') {
            if (strpos($content, '$vid_url = $pod[\'video_url\'];') !== false && strpos($content, 'parse_url') === false) {
                $content = str_replace('                            $vid_url = $pod[\'video_url\'];', $pod_fix_code, $content);
                $modified = true;
            }
        }

        if ($modified) {
            file_put_contents($path, $content);
            $count_logos++;
        }
    }
}

echo "SUCCESS: Reverted logos and patched video parsing logic globally in $count_logos files.\n";
?>
