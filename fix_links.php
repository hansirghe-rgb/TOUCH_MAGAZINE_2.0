<?php
// Fix HTML links in all PHP files
$dir = new RecursiveDirectoryIterator(__DIR__);
$ite = new RecursiveIteratorIterator($dir);
$count = 0;
foreach ($ite as $file) {
    if ($file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        // Replace .html to .php in local relative links
        $new_content = preg_replace('/href="([a-zA-Z0-9_\-]+)\.html"/', 'href="$1.php"', $content);
        if ($new_content !== $content) {
            file_put_contents($path, $new_content);
            $count++;
        }
    }
}

// Update DB
require_once 'config/database.php';
$stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'hero_headline'");
$stmt->execute(['The Pulse <br><span class="italic font-normal">of</span> Humility.']);

echo "<h3>SUCCESS: Updated navigation links across $count files securely.</h3>";
echo "<h3>SUCCESS: Overrode Database Hero text globally.</h3>";
echo '<p><a href="index.php" style="color: blue; text-decoration: underline;">Click here to return to the Live Site &rarr;</a></p>';
?>
