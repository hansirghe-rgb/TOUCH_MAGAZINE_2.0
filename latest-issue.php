<?php
require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM issues WHERE is_latest = 1 ORDER BY id DESC LIMIT 1");
$issue = $stmt->fetch();

if (!$issue) {
    $stmt = $pdo->query("SELECT * FROM issues ORDER BY id DESC LIMIT 1");
    $issue = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Issue | THE TOUCH MAGAZINE</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { navy: '#15202B', red: '#9B2C2C', gold: '#C5A059', light: '#EBE7E0', paper: '#F4F1EA', }, fontFamily: { sans: ['Inter', 'sans-serif'], serif: ['Playfair Display', 'serif'], } } } }
    </script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light text-navy font-sans antialiased">
    
    <nav class="bg-paper border-b border-gray-300 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 flex justify-between items-center h-20">
            <a href="index.php" class="font-serif text-3xl font-black tracking-tighter text-navy uppercase flex items-center">
                <img src="images/touch_logo.png" alt="The Touch Magazine" class="h-20 md:h-24 lg:h-28 w-auto object-contain mix-blend-multiply transform scale-125 origin-left">
            </a>
            <div class="hidden lg:flex space-x-8 items-center text-xs font-bold uppercase tracking-widest text-navy">
                <a href="index.php" class="hover:text-red transition-colors py-1">Home</a>
                <a href="latest-issue.php" class="text-red border-b-2 border-red transition-colors py-1">Latest Issue</a>
                <a href="columns.php" class="hover:text-red transition-colors py-1">Columns</a>
                <a href="podcasts.php" class="hover:text-red transition-colors py-1">Podcasts</a>
                <a href="about.php" class="hover:text-red transition-colors py-1">About Us</a>
                <a href="contact.php" class="bg-navy text-paper px-4 py-2 hover:bg-red transition-colors">Contact</a>
            </div>
            <button id="mobile-menu-btn" class="lg:hidden text-navy focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-4 sm:px-8 py-16 fade-up">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 border-b border-gray-300 pb-8">
            <div>
                <span class="text-red font-bold uppercase tracking-widest text-xs mb-4 block">Digital Archive</span>
                <h1 class="font-serif text-5xl md:text-7xl font-black text-navy">The Latest Issue</h1>
            </div>
        </div>
    </header>

    <main class="bg-paper border-t border-gray-300 py-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                
                <div class="fade-up relative">
                    <div class="aspect-[3/4] w-full max-w-md mx-auto bg-light overflow-hidden shadow-lg border border-gray-300 p-2 relative z-10">
                        <img id="featured-issue-cover" src="<?= $issue ? htmlspecialchars($issue['cover_image']) : 'images/politics.png' ?>" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="fade-up pt-10 md:pt-0">
                    <?php if ($issue): ?>
                        <span class="text-red font-bold uppercase tracking-widest text-xs mb-4 block">Edition <?= str_pad($issue['id'], 2, '0', STR_PAD_LEFT) ?> &bull; <?= htmlspecialchars($issue['issue_month']) ?> <?= htmlspecialchars($issue['issue_year']) ?></span>
                        <h2 class="font-serif text-4xl md:text-5xl font-black text-navy mb-6 leading-tight"><?= htmlspecialchars($issue['title']) ?></h2>
                        <p class="text-navy/70 text-lg font-medium leading-relaxed mb-8"><?= nl2br(htmlspecialchars($issue['description'] ?? '')) ?></p>
                        
                        <a href="<?= htmlspecialchars($issue['pdf_file']) ?>" target="_blank" class="bg-navy text-paper px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-red transition-colors shadow-none border border-navy w-full sm:w-auto inline-block text-center">Open Flipbook Edition</a>
                    <?php else: ?>
                        <span class="text-red font-bold uppercase tracking-widest text-xs mb-4 block">Notice</span>
                        <h2 class="font-serif text-4xl md:text-5xl font-black text-navy mb-6 leading-tight">No Edition Available</h2>
                        <p class="text-navy/70 text-lg font-medium leading-relaxed mb-8">Please check back later. Our editorial board is finalizing the upcoming issue.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-navy border-t-[8px] border-red text-paper py-12 px-4 shadow-inner mt-auto">
        <div class="max-w-7xl mx-auto text-center flex flex-col items-center">
            <h4 class="font-serif text-2xl font-black tracking-tighter uppercase mb-4">The Touch.</h4>
            <p class="text-[10px] font-bold uppercase tracking-widest text-paper/50">Educational Partner RGHE &bull; &copy; 2026 THE TOUCH MAGAZINE. Colombo, Sri Lanka.</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
