<?php
require_once 'config/database.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT a.*, c.name as category_name, c.slug as category_slug FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.id = ? AND a.status = 'published'");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> | THE TOUCH MAGAZINE</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { navy: '#15202B', red: '#9B2C2C', gold: '#C5A059', light: '#EBE7E0', paper: '#F4F1EA', }, fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Playfair Display', 'serif'], } } } }
    </script>
    <link rel="stylesheet" href="style.css">
    <!-- Add simple prose base styling -->
    <style>
        .prose p { margin-bottom: 1.5rem; }
        .prose h2 { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; background-color: transparent !important; margin-top: 2rem; margin-bottom: 1rem; color: #15202B; }
        .prose strong { font-weight: 700; color: #15202B; }
    </style>
</head>
<body class="bg-light text-navy font-sans antialiased">
    
    <nav class="bg-paper border-b border-gray-300 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex justify-between items-center h-20">
            <a href="index.php" class="font-serif text-3xl font-black tracking-tighter text-navy uppercase flex items-center">
                <img src="images/touch_logo.png" alt="The Touch Magazine" class="h-8 md:h-10 w-auto object-contain">
            </a>
            <div class="hidden lg:flex space-x-8 items-center text-xs font-bold uppercase tracking-widest text-navy">
                <a href="index.php" class="hover:text-red transition-colors py-1">Home</a>
                <a href="latest-issue.php" class="hover:text-red transition-colors py-1">Latest Issue</a>
                <a href="columns.php" class="hover:text-red transition-colors py-1">Columns</a>
                <a href="podcasts.php" class="hover:text-red transition-colors py-1">Podcasts</a>
                <a href="about.php" class="hover:text-red transition-colors py-1">About Us</a>
                <a href="contact.php" class="bg-navy text-paper px-4 py-2 hover:bg-red transition-colors">Contact</a>
            </div>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-4 sm:px-8 py-16 fade-up">
        <a href="category.php?slug=<?= htmlspecialchars($article['category_slug']) ?>" class="text-[10px] font-bold uppercase tracking-widest text-navy hover:text-red mb-8 inline-block">&larr; Back to <?= htmlspecialchars($article['category_name']) ?></a>
        <h1 class="font-serif text-5xl md:text-7xl font-black text-navy mb-6"><?= htmlspecialchars($article['title']) ?></h1>
        <?php if ($article['subtitle']): ?>
            <p class="text-navy/60 font-medium mb-4 text-xl border-l-[3px] border-red pl-4 py-2 italic mt-6"><?= htmlspecialchars($article['subtitle']) ?></p>
        <?php endif; ?>
    </header>

    <main class="bg-paper border-t border-gray-300 py-20 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-8">
            <article class="fade-up">
                <?php if ($article['image_path']): ?>
                <div class="aspect-[21/9] w-full bg-light overflow-hidden mb-10 shadow-sm border border-gray-300 p-1">
                    <img src="<?= htmlspecialchars($article['image_path']) ?>" class="w-full h-full object-cover">
                </div>
                <?php endif; ?>
                <div class="news-line-thick w-16 mb-6"></div>
                
                <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-navy/50 uppercase tracking-widest mb-10 pb-6 border-b border-gray-300">
                    <span class="text-navy">By <?= htmlspecialchars($article['author_name']) ?></span>
                    <span>&bull;</span>
                    <span><?= date('F d, Y', strtotime($article['publish_date'])) ?></span>
                    <?php if ($article['is_editor_pick']): ?>
                        <span>&bull;</span>
                        <span class="bg-gold text-paper px-2 py-1 tracking-widest text-[9px]">Editor's Pick</span>
                    <?php endif; ?>
                </div>
                
                <div class="prose prose-lg text-navy/80 font-medium leading-relaxed">
                    <?= $article['content'] // Content is HTML from wysiwyg or textarea ?>
                </div>
            </article>
        </div>
    </main>

    <footer class="bg-navy border-t-[8px] border-red text-paper py-12 px-4 shadow-inner mt-auto">
        <div class="max-w-7xl mx-auto text-center flex flex-col items-center">
            <h4 class="font-serif text-2xl font-black tracking-tighter uppercase mb-4">The Touch.</h4>
            <p class="text-[10px] font-bold uppercase tracking-widest text-paper/50">Educational Partner RGHE &bull; &copy; <?= date('Y') ?> THE TOUCH MAGAZINE. Colombo, Sri Lanka.</p>
        </div>
    </footer>
</body>
</html>
