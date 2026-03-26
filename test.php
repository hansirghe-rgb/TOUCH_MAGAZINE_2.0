<?php
require 'config/database.php';
$stmt=$pdo->query("SELECT id FROM articles WHERE status='published' ORDER BY id DESC LIMIT 1");
$id=$stmt->fetchColumn();
if($id) {
    echo "Found article ID $id\n";
    $html = file_get_contents("http://localhost/TOUCH_MAGAZINE/article.php?id=$id");
    echo substr($html, 0, 300);
} else {
    echo "No articles.\n";
}
?>
