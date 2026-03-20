<?php
require_once 'config/database.php';

// Fetch Homepage Settings
$logo_path = get_setting($pdo, 'rghe_logo', 'images/rghe_logo.png');
$partner_text = get_setting($pdo, 'footer_partner_text', 'Educational Partner RGHE');
$hero_headline = get_setting($pdo, 'hero_headline', 'The Pulse <br><span class="italic font-normal">of</span> Humility.');
$hero_subheading = get_setting($pdo, 'hero_subheading', 'Inside The City');
$hero_text = get_setting($pdo, 'hero_text', 'A modern monthly magazine blending insightful journalism with stunning visual storytelling. We decode politics, economy, travel, and culture from our headquarters in Colombo to the world.');
$hero_bg_image = get_setting($pdo, 'hero_bg_image', 'images/politics.png');

// Fetch Latest Issue
$stmt = $pdo->query("SELECT * FROM issues WHERE is_latest = 1 ORDER BY id DESC LIMIT 1");
$latest_issue = $stmt->fetch();
if(!$latest_issue) {
    $latest_issue = [
        'title' => 'The Urban Renaissance',
        'issue_month' => 'August',
        'issue_year' => date('Y'),
        'cover_image' => 'images/politics.png',
        'pdf_file' => 'latest-issue.php'
    ];
}

// Fetch Latest Video Podcast
$stmt = $pdo->query("SELECT * FROM podcasts WHERE status = 'published' ORDER BY id DESC LIMIT 1");
$latest_podcast = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE TOUCH MAGAZINE | Colombo's Premium Monthly News</title>
    <meta name="description" content="A modern monthly magazine with insightful journalism and strong visual storytelling, launching from Colombo, Sri Lanka.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#15202B',
                        red: '#9B2C2C',
                        gold: '#C5A059', 
                        light: '#EBE7E0', // Soft Stone / Warm Gray-Beige
                        paper: '#F4F1EA', // Warm Off-White
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light text-navy font-sans antialiased">
    
    <!-- Topbar (Date & Edition) -->
    <div class="border-b border-gray-300 bg-paper py-2 px-4 md:px-8 flex justify-between items-center text-[10px] md:text-xs font-semibold tracking-widest uppercase text-navy/70">
        <div>Colombo, Sri Lanka</div>
        <div>Volume 01 &bull; The Premiere</div>
        <div class="hidden sm:block">A Monthly News Magazine</div>
    </div>

    <!-- Navigation -->
    <nav class="bg-paper border-b border-gray-300 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="index.php" class="font-serif text-3xl font-black tracking-tighter text-navy uppercase flex items-center">
                    The Touch <span class="text-red ml-2 relative -top-2 text-4xl leading-none">.</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden lg:flex space-x-8 items-center text-xs font-bold uppercase tracking-widest text-navy">
                    <a href="index.php" class="text-red transition-colors border-b-2 border-red py-1">Home</a>
                    <a href="latest-issue.php" class="hover:text-red transition-colors py-1">Latest Issue</a>
                    <a href="columns.php" class="hover:text-red transition-colors py-1">Columns</a>
                    <a href="podcasts.php" class="hover:text-red transition-colors py-1">Podcasts</a>
                    <a href="about.php" class="hover:text-red transition-colors py-1">About Us</a>
                    <a href="contact.php" class="bg-navy text-paper px-4 py-2 hover:bg-red transition-colors">Contact</a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-navy focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Fullscreen Menu -->
    <div id="mobile-menu" class="fixed inset-0 z-[100] bg-paper transform translate-x-full transition-transform duration-500 flex flex-col items-center justify-center border-l border-gray-300">
        <button id="close-menu-btn" class="absolute top-8 right-8 text-navy hover:text-red">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="flex flex-col space-y-6 text-center font-serif text-2xl font-bold uppercase tracking-widest">
            <a href="index.php" class="text-red">Home</a>
            <a href="latest-issue.php" class="hover:text-red">Latest Issue</a>
            <a href="columns.php" class="hover:text-red">Columns</a>
            <a href="podcasts.php" class="hover:text-red">Podcasts</a>
            <a href="about.php" class="hover:text-red">About Us</a>
            <a href="contact.php" class="hover:text-red">Contact</a>
        </div>
    </div>



    <!-- Hero Section -->
    <header class="relative min-h-[90vh] w-full overflow-hidden flex items-center bg-light border-b border-gray-300">
        <!-- Background Image (Realistic Colombo Skyline) -->
        <div class="absolute inset-0 z-0 selection:bg-transparent">
            <!-- Ensure high visibility with object cover -->
            <img src="<?= htmlspecialchars($hero_bg_image) ?>" alt="Colombo City Overview" class="w-full h-full object-cover object-center">
            <!-- Warmer, lighter gradient overlay -->
            <div class="absolute inset-0 hero-gradient"></div>
        </div>
        
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Headline Text (Left side) -->
            <div class="lg:col-span-7 fade-up pb-10 lg:pb-0">
                <span class="text-red font-bold uppercase tracking-widest text-xs mb-6 flex items-center">
                    <span class="w-8 h-px bg-red mr-3"></span> <?= htmlspecialchars($hero_subheading) ?>
                </span>
                <h1 class="font-serif text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-navy leading-[0.9] tracking-tight mb-8">
                    <?= $hero_headline ?>
                </h1>
                <p class="text-navy/80 text-lg md:text-xl font-medium leading-relaxed mb-10 max-w-xl">
                    <?= htmlspecialchars($hero_text) ?>
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="latest-issue.php" class="bg-navy text-paper px-8 py-3 text-xs font-bold uppercase tracking-widest hover:bg-red transition-colors">Open Flipbook Edition</a>
                    <a href="columns.php" class="border border-navy text-navy px-8 py-3 text-xs font-bold uppercase tracking-widest hover:bg-navy hover:text-paper transition-colors bg-paper">View Columns</a>
                </div>
            </div>

            <!-- Featured Issue Card (Right side) -->
            <div class="lg:col-span-5 fade-up">
                <!-- Softer Editorial Card Style -->
                <div class="bg-paper border border-gray-300 p-4 sm:p-5 shadow-lg relative">
                    <div class="flex items-start justify-between mb-4 border-b border-gray-300 pb-3 gap-2">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-navy/60 mb-1"><?= htmlspecialchars($latest_issue['issue_month']) ?> <?= htmlspecialchars($latest_issue['issue_year']) ?></p>
                            <h3 class="font-serif text-2xl font-bold text-navy"><?= htmlspecialchars($latest_issue['title']) ?></h3>
                        </div>
                        <div class="bg-red text-paper text-[9px] uppercase font-bold tracking-widest px-2 py-1 shadow-sm shrink-0">Premium Edition</div>
                    </div>
                    
                    <div class="w-full aspect-[3/4] overflow-hidden bg-light hover-zoom border border-gray-200">
                        <img id="featured-issue-cover" src="<?= htmlspecialchars($latest_issue['cover_image']) ?>" alt="Current Issue Cover" class="w-full h-full object-cover">
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-300 flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
                        <a href="latest-issue.php" class="text-navy hover:text-red flex items-center">
                            Open Flipbook Edition
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </header>

    <main class="bg-light relative">

        <!-- LATEST ARTICLES GRID (News look) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-8 py-20 fade-up">
            <h2 class="font-serif text-4xl font-black text-navy mb-8 news-line-thick pt-4">Top Stories</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Lead Story -->
                <div class="md:col-span-7 lg:col-span-8 group hover-zoom">
                    <a href="politics.php" class="block">
                        <div class="w-full aspect-video bg-paper border border-gray-300 mb-6 overflow-hidden">
                            <img src="images/politics.png" alt="Economic Reforms" class="w-full h-full object-cover p-1">
                        </div>
                        <span class="text-red font-bold text-[10px] uppercase tracking-widest">Politics & Economy</span>
                        <h3 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-navy mt-2 mb-4 group-hover:text-red transition-colors leading-tight">Decoding the National Economic Policy Restructuring</h3>
                        <p class="text-navy/80 mb-4 font-medium leading-relaxed">As Colombo gears up for unprecedented fiscal policies, an exclusive deep-dive into the strategies aimed at stabilization and long-term exponential growth.</p>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-navy/50">By The Touch Editorial Board &bull; 6 Min Read</span>
                    </a>
                </div>

                <!-- Secondary Stories Area -->
                <div class="md:col-span-5 lg:col-span-4 flex flex-col gap-8 md:pl-8 md:border-l border-gray-300">
                    
                    <!-- Sub Article 1 -->
                    <div class="group hover-zoom border-b border-gray-300 pb-8">
                        <a href="travel.php" class="block">
                            <div class="w-full h-40 bg-paper border border-gray-300 mb-4 overflow-hidden">
                                <img src="images/travel.png" alt="Travel Guide" class="w-full h-full object-cover p-1">
                            </div>
                            <span class="text-red font-bold text-[10px] uppercase tracking-widest block mb-2">Travel</span>
                            <h4 class="font-serif text-2xl font-bold text-navy group-hover:text-red transition-colors leading-tight mb-2">The Hidden Valleys: Rediscovering the Central Highlands</h4>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-navy/50">By Anya Fernando</span>
                        </a>
                    </div>

                    <!-- Sub Article 2 -->
                    <div class="group hover-zoom">
                        <a href="culture.php" class="block">
                            <span class="text-red font-bold text-[10px] uppercase tracking-widest block mb-2">Heritage</span>
                            <h4 class="font-serif text-2xl font-bold text-navy group-hover:text-red transition-colors leading-tight mb-2">Monuments of Antiquity: Restoration at Anuradhapura</h4>
                            <p class="text-navy/80 text-sm mb-2 font-medium">Architectural efforts unearthing centuries-old marvels.</p>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-navy/50">By Tariq Silva</span>
                        </a>
                    </div>

                </div>
            </div>
        </section>

        <!-- THE COLUMNS SHOWCASE -->
        <section class="bg-paper border-y border-gray-300 py-20 fade-up">
            <div class="max-w-7xl mx-auto px-4 sm:px-8">
                <div class="flex justify-between items-end mb-10 news-line pt-4 border-gray-300">
                    <h2 class="font-serif text-4xl font-black text-navy">The Columns</h2>
                    <a href="columns.php" class="hidden sm:inline-block text-xs font-bold uppercase tracking-widest text-navy hover:text-red">View All &rarr;</a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    
                    <!-- Col 1 -->
                    <a href="politics.php" class="group block">
                        <div class="aspect-[4/3] w-full bg-light mb-4 overflow-hidden border border-gray-300 shadow-sm">
                            <img src="images/politics.png" alt="Politics" class="w-full h-full object-cover p-1 transform group-hover:scale-105 transition duration-500">
                        </div>
                        <h4 class="font-sans text-sm font-black uppercase tracking-widest text-navy group-hover:text-red">Politics, Econ. & Current Issues</h4>
                    </a>

                    <!-- Col 2 -->
                    <a href="travel.php" class="group block">
                        <div class="aspect-[4/3] w-full bg-light mb-4 overflow-hidden border border-gray-300 shadow-sm">
                            <img src="images/travel.png" alt="Travel" class="w-full h-full object-cover p-1 transform group-hover:scale-105 transition duration-500">
                        </div>
                        <h4 class="font-sans text-sm font-black uppercase tracking-widest text-navy group-hover:text-red">Travel & Photography</h4>
                    </a>

                    <!-- Col 3 -->
                    <a href="culture.php" class="group block">
                        <div class="aspect-[4/3] w-full bg-light mb-4 overflow-hidden border border-gray-300 shadow-sm">
                            <img src="images/culture.png" alt="Heritage" class="w-full h-full object-cover p-1 transform group-hover:scale-105 transition duration-500">
                        </div>
                        <h4 class="font-sans text-sm font-black uppercase tracking-widest text-navy group-hover:text-red">Heritage & Environment</h4>
                    </a>

                    <!-- Col 4 -->
                    <a href="food.php" class="group block relative">
                        <div class="absolute top-2 left-2 bg-gold text-paper text-[9px] uppercase font-bold px-2 py-1 z-10 shadow-sm tracking-widest">Monthly Special</div>
                        <div class="aspect-[4/3] w-full bg-light mb-4 overflow-hidden border border-gray-300 shadow-sm">
                            <img src="images/lifestyle.png" alt="Food Recipes" class="w-full h-full object-cover p-1 transform group-hover:scale-105 transition duration-500">
                        </div>
                        <h4 class="font-sans text-sm font-black uppercase tracking-widest text-navy group-hover:text-red">Food Recipes</h4>
                    </a>

                    <!-- Col 5 -->
                    <a href="sports.php" class="group block">
                        <div class="aspect-[4/3] w-full bg-light mb-4 overflow-hidden border border-gray-300 shadow-sm">
                            <img src="images/hero.png" alt="Sports" class="w-full h-full object-cover p-1 transform group-hover:scale-105 transition duration-500">
                        </div>
                        <h4 class="font-sans text-sm font-black uppercase tracking-widest text-navy group-hover:text-red">Sports & Wellness</h4>
                    </a>

                </div>
            </div>
        </section>

        <!-- PODCASTS SECTION -->
        <section class="max-w-7xl mx-auto px-4 sm:px-8 py-20 fade-up">
            <h2 class="font-serif text-4xl font-black text-navy mb-8 news-line-thick pt-4">The Touch Podcasts</h2>
            
            <div class="p-8 bg-paper border border-gray-300 shadow-lg grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative overflow-hidden">
                
                <div>
                    <span class="text-red font-bold uppercase tracking-widest text-xs mb-4 block">Studio Series</span>
                    <h3 class="font-serif text-3xl md:text-5xl font-bold text-navy mb-6 leading-tight"><?= $latest_podcast ? htmlspecialchars($latest_podcast['title']) : 'Live From Colombo' ?></h3>
                    <p class="text-navy/80 font-medium mb-8 leading-relaxed"><?= $latest_podcast && $latest_podcast['description'] ? nl2br(htmlspecialchars($latest_podcast['description'])) : 'Interviews with newsmakers, politicians, and cultural icons. Watch our fully structured video broadcasts directly embedded on our platform.' ?></p>
                    <a href="podcasts.php" class="inline-block border border-navy text-navy bg-light px-6 py-3 text-xs font-bold uppercase tracking-widest hover:bg-navy hover:text-paper transition-colors">View All Episodes</a>
                </div>
                
                <!-- Video Mini Player -->
                <div class="w-full aspect-video bg-navy relative border border-gray-300 group shadow-sm z-10">
                    <?php if ($latest_podcast && $latest_podcast['video_url']): ?>
                        <?php
                            $vid_url = $latest_podcast['video_url'];
                            // Simple embed detection logic
                            if (strpos($vid_url, 'youtube.com') !== false || strpos($vid_url, 'vimeo.com') !== false || strpos($vid_url, 'youtu.be') !== false): 
                                // Assume they pasted an embeddable URL or frame it (production code might parse out video IDs)
                        ?>
                            <iframe src="<?= htmlspecialchars($vid_url) ?>" class="w-full h-full border-0 pointer-events-auto" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <?php else: ?>
                            <video controls class="w-full h-full pointer-events-auto object-cover" poster="<?= htmlspecialchars($latest_podcast['thumbnail_path'] ?: 'images/politics.png') ?>">
                                <source src="<?= htmlspecialchars($vid_url) ?>" type="video/mp4">
                            </video>
                        <?php endif; ?>
                    <?php else: ?>
                        <img src="<?= $latest_podcast && $latest_podcast['thumbnail_path'] ? htmlspecialchars($latest_podcast['thumbnail_path']) : 'images/politics.png' ?>" alt="Podcast Preview" class="w-full h-full object-cover opacity-80 mix-blend-luminosity">
                        <!-- Play icon -->
                        <a href="podcasts.php" class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 bg-paper text-navy flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-navy border-t-[8px] border-red text-paper py-16 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-12 mb-12 border-b border-paper/10 pb-12">
            
            <!-- Brand -->
            <div class="col-span-1 lg:col-span-1">
                <a href="index.php" class="font-serif text-3xl font-black tracking-tighter text-paper uppercase flex items-center mb-6">
                    The Touch <span class="text-red ml-1 relative -top-1 text-3xl leading-none">.</span>
                </a>
                <p class="text-paper/70 text-sm leading-relaxed mb-6 font-medium">A modern news magazine delivering premium journalism, launched out of Colombo, Sri Lanka.</p>
                <a href="contact.php" class="text-[10px] font-bold uppercase tracking-widest hover:text-red transition-colors flex items-center">Contact Editorial <span class="ml-1">&rarr;</span></a>
            </div>

            <!-- Links -->
            <div class="col-span-1">
                <h4 class="text-[10px] font-bold uppercase tracking-widest text-paper/40 mb-6 font-sans">Sections</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="politics.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">Politics & Current Issues</a></li>
                    <li><a href="travel.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">Travel & Photography</a></li>
                    <li><a href="culture.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">Heritage & Environment</a></li>
                    <li><a href="food.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">Food Recipes</a></li>
                    <li><a href="sports.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">Sports & Entertainment</a></li>
                </ul>
            </div>

            <div class="col-span-1">
                <h4 class="text-[10px] font-bold uppercase tracking-widest text-paper/40 mb-6 font-sans">Resources</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="latest-issue.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">Latest Issue Archive</a></li>
                    <li><a href="podcasts.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">The Touch Studios</a></li>
                    <li><a href="about.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">About Us</a></li>
                    <li><a href="contact.php" class="hover:text-red transition-colors text-paper/80 hover:text-paper">Subscribe</a></li>
                </ul>
            </div>

            <!-- RGHE Logo Portal -->
            <div class="col-span-1 bg-paper/5 p-6 border border-paper/10 flex flex-col items-center sm:items-start text-center sm:text-left">
                <span class="text-[10px] items-center sm:items-start font-bold tracking-widest uppercase text-paper/50 mb-4 block"><?= htmlspecialchars($partner_text) ?></span>
                <div class="bg-paper p-3 w-40 h-24 mb-4 flex items-center justify-center relative shadow-inner">
                    <img id="campus-logo" src="<?= htmlspecialchars($logo_path) ?>" alt="RGHE Campus Logo" class="max-h-full max-w-full object-contain opacity-50 transition-opacity duration-300">
                </div>
            </div>

        </div>

        <!-- Copyright -->
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-[10px] font-bold uppercase tracking-widest text-paper/50">
            <p>&copy; 2026 THE TOUCH MAGAZINE. Colombo, Sri Lanka.</p>
            <div class="space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-paper transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-paper transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>