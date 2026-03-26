<?php
require_once 'config/database.php';

$stmt = $pdo->query("SELECT * FROM podcasts WHERE status = 'published' ORDER BY id DESC");
$podcasts = $stmt->fetchAll();

$current_podcast = count($podcasts) > 0 ? $podcasts[0] : null;
if (isset($_GET['id'])) {
    foreach($podcasts as $p) {
        if ($p['id'] == $_GET['id']) {
            $current_podcast = $p;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcasts | THE TOUCH MAGAZINE</title>
    <!-- Same Google Fonts and Tailwind config as index -->
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
                <img src="images/touch_logo.png" alt="The Touch Magazine" class="h-8 md:h-10 w-auto object-contain">
            </a>
            <div class="hidden lg:flex space-x-8 items-center text-xs font-bold uppercase tracking-widest text-navy">
                <a href="index.php" class="hover:text-red transition-colors py-1">Home</a>
                <a href="latest-issue.php" class="hover:text-red transition-colors py-1">Latest Issue</a>
                <a href="columns.php" class="hover:text-red transition-colors py-1">Columns</a>
                <a href="podcasts.php" class="text-red border-b-2 border-red transition-colors py-1">Podcasts</a>
                <a href="about.php" class="hover:text-red transition-colors py-1">About Us</a>
                <a href="contact.php" class="bg-navy text-paper px-4 py-2 hover:bg-red transition-colors">Contact</a>
            </div>
            <button id="mobile-menu-btn" class="lg:hidden text-navy focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </nav>
    <div id="mobile-menu" class="fixed inset-0 z-[100] bg-paper transform translate-x-full transition-transform duration-500 flex flex-col items-center justify-center border-l border-gray-300">
        <button id="close-menu-btn" class="absolute top-8 right-8 text-navy hover:text-red"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        <div class="flex flex-col space-y-6 text-center font-serif text-2xl font-bold uppercase tracking-widest">
            <a href="index.php" class="hover:text-red">Home</a><a href="latest-issue.php" class="hover:text-red">Latest Issue</a><a href="columns.php" class="hover:text-red">Columns</a><a href="podcasts.php" class="text-red">Podcasts</a><a href="about.php" class="hover:text-red">About Us</a><a href="contact.php" class="hover:text-red">Contact</a>
        </div>
    </div>

    <!-- Header -->
    <header class="max-w-7xl mx-auto px-4 sm:px-8 py-16 fade-up">
        <span class="text-red font-bold uppercase tracking-widest text-xs mb-4 block">The Touch Studios</span>
        <h1 class="font-serif text-5xl md:text-7xl font-black text-navy mb-6">Podcasts & Video</h1>
        <p class="text-navy/70 text-lg md:text-xl font-medium max-w-2xl">Tune in to critical conversations directly from our Colombo studio. Exclusive journalism, dynamic interviews, and deep insights.</p>
    </header>

    <main class="bg-paper border-t border-gray-300 py-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Main Featured Player + Upload Portal -->
                <div class="lg:col-span-8 fade-up">
                    <div class="flex justify-between items-end mb-6">
                        <h2 class="font-serif text-3xl font-black text-navy border-b-[3px] border-navy pb-2">Featured Broadcast</h2>
                    </div>

                    <div class="w-full aspect-video bg-light relative border border-gray-300 overflow-hidden shadow-lg flex items-center justify-center p-1">
                        <?php if ($current_podcast): ?>
                            <?php
                                $vid_url = $current_podcast['video_url'];
                                if (strpos($vid_url, 'youtube.com') !== false || strpos($vid_url, 'vimeo.com') !== false || strpos($vid_url, 'youtu.be') !== false): 
                            ?>
                                <iframe src="<?= htmlspecialchars($vid_url) ?>" class="w-full h-full border-0 pointer-events-auto z-10 relative" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <?php else: ?>
                                <video controls class="w-full h-full pointer-events-auto object-cover z-10 relative" poster="<?= htmlspecialchars($current_podcast['thumbnail_path'] ?: 'images/politics.png') ?>">
                                    <source src="<?= htmlspecialchars($vid_url) ?>" type="video/mp4">
                                </video>
                            <?php endif; ?>
                            
                            <div class="absolute bottom-6 left-6 z-20 pointer-events-none text-paper drop-shadow-lg p-2 bg-navy/50 backdrop-blur-sm">
                                <h3 class="font-serif text-2xl font-bold mb-1"><?= htmlspecialchars($current_podcast['title']) ?></h3>
                                <p class="text-[10px] font-bold tracking-widest uppercase opacity-80"><?= htmlspecialchars($current_podcast['duration'] ?: '00:00') ?></p>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-full bg-navy flex items-center justify-center text-paper font-bold uppercase tracking-widest text-xs z-10 relative">No podcasts available.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Episodes Sidebar -->
                <div class="lg:col-span-4 fade-up">
                    <h2 class="font-serif text-3xl font-black text-navy mb-6 border-b-[3px] border-navy pb-2">Recent Episodes</h2>
                    <div class="flex flex-col gap-6">
                        <?php foreach($podcasts as $p): if($current_podcast && $p['id'] == $current_podcast['id']) continue; ?>
                        <!-- Card -->
                        <div class="flex gap-4 border border-gray-300 bg-light p-3 hover:shadow-md transition-shadow group cursor-pointer" onclick="window.location.href='podcasts.php?id=<?= $p['id'] ?>'">
                            <div class="w-32 h-24 flex-shrink-0 bg-navy relative border border-gray-300 p-1">
                                <img src="<?= htmlspecialchars($p['thumbnail_path'] ?: 'images/politics.png') ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-paper p-2 text-navy opacity-80 group-hover:opacity-100 transition-opacity"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg></div>
                                </div>
                            </div>
                            <div class="flex flex-col justify-center">
                                <h4 class="font-bold text-sm text-navy mb-1 leading-tight group-hover:text-red transition-colors"><?= htmlspecialchars($p['title']) ?></h4>
                                <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest"><?= htmlspecialchars($p['duration'] ?: '00:00') ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (count($podcasts) <= 1): ?>
                            <div class="text-xs font-bold uppercase tracking-widest text-navy/50 p-4 text-center border border-gray-300 bg-light">No other recent episodes available.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer snippet -->
    <footer class="bg-navy border-t-[8px] border-red text-paper py-12 px-4 shadow-inner text-center">
        <h4 class="font-serif text-2xl font-black tracking-tighter text-paper uppercase mb-4">The Touch.</h4>
        <p class="text-[10px] font-bold uppercase tracking-widest text-paper/50">Educational Partner RGHE &bull; &copy; 2026 THE TOUCH MAGAZINE. Colombo, Sri Lanka.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
