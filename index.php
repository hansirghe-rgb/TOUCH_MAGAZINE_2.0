<?php
require_once 'config/database.php';

// Fetch Homepage Settings
$partner_text = get_setting($pdo, 'footer_partner_text', 'Educational Partners');
$logos_json = get_setting($pdo, 'partner_logos');
$partner_logos = $logos_json ? json_decode($logos_json, true) : [];
if (empty($partner_logos)) {
    $legacy = get_setting($pdo, 'rghe_logo');
    $partner_logos = $legacy ? [$legacy] : ['images/rghe_logo.png'];
}
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

// Fetch Top Stories from Articles (Main Article)
$stmt = $pdo->query("SELECT a.*, c.name as category_name, c.slug as category_slug FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.status = 'published' AND a.is_featured = 1 ORDER BY a.publish_date DESC, a.id DESC LIMIT 1");
$top_stories = $stmt->fetchAll();

// Fetch Editor's Picks for simple headlines
$stmt = $pdo->query("SELECT a.*, c.name as category_name, c.slug as category_slug FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.status = 'published' AND a.is_editor_pick = 1 ORDER BY a.publish_date DESC, a.id DESC LIMIT 5");
$editor_picks = $stmt->fetchAll();

// Fetch Columns (Categories) for Showcase
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC LIMIT 5");
$homepage_categories = $stmt->fetchAll();
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
                    <img src="images/touch_logo.png" alt="The Touch Magazine" class="h-20 md:h-24 lg:h-28 w-auto object-contain mix-blend-multiply transform scale-125 origin-left">
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
            </div>

            <!-- Podcast Video Placed in Hero (Right side) -->
            <div class="lg:col-span-5 fade-up flex flex-col justify-center">
                <div class="bg-paper border border-gray-300 p-4 shadow-lg relative">
                    <div class="flex items-start justify-between mb-4 border-b border-gray-300 pb-3 gap-2">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-red flex items-center mb-1"><span class="w-4 h-px bg-red mr-2"></span>The Touch Studios</span>
                            <h3 class="font-serif text-2xl font-bold text-navy leading-tight"><?= $latest_podcast ? htmlspecialchars($latest_podcast['title']) : 'Latest Broadcast' ?></h3>
                        </div>
                        <div class="bg-navy text-paper text-[9px] uppercase font-bold tracking-widest px-2 py-1 shadow-sm shrink-0">Live</div>
                    </div>
                    
                    <div class="w-full aspect-video bg-navy relative border border-gray-200 group shadow-sm z-10 hover-zoom">
                        <?php if ($latest_podcast && $latest_podcast['video_url']): ?>
                            <?php
                                $vid_url = $latest_podcast['video_url'];
                                if (strpos($vid_url, 'youtube.com') !== false || strpos($vid_url, 'vimeo.com') !== false || strpos($vid_url, 'youtu.be') !== false): 
                            ?>
                                <iframe src="<?= htmlspecialchars($vid_url) ?>" class="w-full h-full border-0 pointer-events-auto" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            <?php else: ?>
                                <video controls class="w-full h-full pointer-events-auto object-cover" poster="<?= htmlspecialchars($latest_podcast['thumbnail_path'] ?: 'images/politics.png') ?>">
                                    <source src="<?= htmlspecialchars($vid_url) ?>" type="video/mp4">
                                </video>
                            <?php endif; ?>
                        <?php else: ?>
                            <img src="<?= $latest_podcast && $latest_podcast['thumbnail_path'] ? htmlspecialchars($latest_podcast['thumbnail_path']) : 'images/politics.png' ?>" alt="Podcast Preview" class="w-full h-full object-cover opacity-80 mix-blend-luminosity">
                            <a href="podcasts.php" class="absolute inset-0 flex items-center justify-center">
                                <div class="w-16 h-16 bg-paper text-navy flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                </div>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4 border-t border-gray-300 pt-3 flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
                        <span class="text-navy/50">Runtime: <?= $latest_podcast ? htmlspecialchars($latest_podcast['duration']) : '--:--' ?></span>
                        <a href="podcasts.php" class="text-navy hover:text-red">View All Episodes &rarr;</a>
                    </div>
                </div>
            </div>
            
        </div>
    </header>

    <main class="bg-light relative">

        <!-- MAGAZINE PORTAL SECTION -->
        <section class="max-w-7xl mx-auto px-4 sm:px-8 py-20 fade-up border-b border-gray-300">
            <div class="flex items-center mb-8 news-line pt-4 border-gray-300">
                <h2 class="font-serif text-4xl font-black text-navy mr-6">Latest Digital Edition</h2>
            </div>
            
            <div class="bg-paper border border-gray-300 shadow-md p-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative overflow-hidden">
                <div class="absolute -right-40 -bottom-40 opacity-[0.03] pointer-events-none">
                    <h3 class="font-sans font-black text-[20rem] leading-none text-navy">ISSUE</h3>
                </div>
                
                <div class="relative z-10 w-full sm:w-2/3 mx-auto md:w-full max-w-[300px]">
                    <a href="<?= htmlspecialchars($latest_issue['pdf_file']) ?>" target="_blank" class="block aspect-[3/4] overflow-hidden bg-light hover-zoom border border-gray-200 shadow-xl group">
                        <img id="featured-issue-cover" src="<?= htmlspecialchars($latest_issue['cover_image']) ?>" alt="Current Issue Cover" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700">
                    </a>
                </div>
                
                <div class="relative z-10">
                    <span class="text-red font-bold uppercase tracking-widest text-xs mb-4 block inline-block border-b-2 border-red pb-1">Vol. <?= htmlspecialchars($latest_issue['id']) ?></span>
                    <h3 class="font-serif text-3xl md:text-5xl font-bold text-navy mb-4 leading-tight group-hover:text-red transition-colors"><?= htmlspecialchars($latest_issue['title']) ?></h3>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-navy/60 mb-6 flex items-center">
                        <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                        Published <?= htmlspecialchars($latest_issue['issue_month']) ?> <?= htmlspecialchars($latest_issue['issue_year']) ?>
                    </p>
                    <p class="text-navy/80 font-medium mb-8 leading-relaxed max-w-lg"><?= nl2br(htmlspecialchars($latest_issue['description'] ?? 'Discover our latest features, exclusive columns, and in-depth photography. Click to open the fully immersive HTML flipbook edition.')) ?></p>
                    <div class="flex gap-4 items-center">
                        <a href="<?= htmlspecialchars($latest_issue['pdf_file']) ?>" target="_blank" class="inline-block bg-navy text-paper px-6 py-3 text-[10px] font-bold uppercase tracking-widest hover:bg-red transition-colors shadow-md">Open Flipbook</a>
                        <a href="latest-issue.php" class="text-[10px] font-bold uppercase tracking-widest text-navy hover:text-red transition-colors">Digital Archive &rarr;</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- LATEST ARTICLES GRID (News look) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-8 py-20 fade-up">
            <h2 class="font-serif text-4xl font-black text-navy mb-8 news-line-thick pt-4">Top Stories</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <?php if (!empty($top_stories)): 
                    $lead_story = $top_stories[0];
                ?>
                <!-- Lead Story -->
                <div class="md:col-span-7 lg:col-span-8 group hover-zoom">
                    <a href="article.php?id=<?= $lead_story['id'] ?>" class="block">
                        <div class="w-full aspect-video bg-paper border border-gray-300 mb-6 overflow-hidden">
                            <img src="<?= $lead_story['image_path'] ? htmlspecialchars($lead_story['image_path']) : 'images/politics.png' ?>" alt="Lead Story" class="w-full h-full object-cover p-1">
                        </div>
                        <span class="text-red font-bold text-[10px] uppercase tracking-widest"><?= htmlspecialchars($lead_story['category_name']) ?></span>
                        <h3 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-navy mt-2 mb-4 group-hover:text-red transition-colors leading-tight"><?= htmlspecialchars($lead_story['title']) ?></h3>
                        <?php if ($lead_story['subtitle']): ?>
                            <p class="text-navy/80 mb-4 font-medium leading-relaxed"><?= htmlspecialchars($lead_story['subtitle']) ?></p>
                        <?php endif; ?>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-navy/50">By <?= htmlspecialchars($lead_story['author_name']) ?> &bull; <?= date('M d, Y', strtotime($lead_story['publish_date'])) ?></span>
                    </a>
                </div>

                <!-- Editor's Picks Area (Clickable Headlines) -->
                <div class="md:col-span-5 lg:col-span-4 flex flex-col md:pl-8 md:border-l border-gray-300">
                    <h3 class="font-sans font-black text-xs uppercase tracking-widest text-red mb-6 border-b border-gray-300 pb-2">Editor's Picks</h3>
                    <div class="flex flex-col gap-6">
                        <?php if (!empty($editor_picks)): ?>
                            <?php foreach($editor_picks as $ep): ?>
                                <div class="group border-b border-gray-200 pb-4 last:border-0 hover:bg-paper/50 transition-colors p-2 -mx-2 rounded">
                                    <a href="article.php?id=<?= $ep['id'] ?>" class="block">
                                        <span class="text-red font-bold text-[9px] uppercase tracking-widest block mb-1"><?= htmlspecialchars($ep['category_name']) ?></span>
                                        <h4 class="font-serif text-xl font-bold text-navy group-hover:text-red transition-colors leading-tight"><?= htmlspecialchars($ep['title']) ?></h4>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-[10px] uppercase font-bold text-navy/40 border border-gray-200 p-4 bg-paper text-center">No Editor's Picks Available</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                    <div class="col-span-full text-center py-10 text-navy/50 font-bold uppercase tracking-widest text-sm border border-gray-300 bg-paper">
                        No top stories currently featured. Note: New articles must be marked as "Featured" via the portal.
                    </div>
                <?php endif; ?>
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
                    <?php 
                    $col_images = ['images/politics.png', 'images/travel.png', 'images/culture.png', 'images/lifestyle.png', 'images/hero.png'];
                    foreach($homepage_categories as $index => $cat): 
                        $img = isset($col_images[$index]) ? $col_images[$index] : 'images/politics.png';
                        
                        // Fetch articles for this category
                        $cat_stmt = $pdo->prepare("SELECT id, title FROM articles WHERE category_id = ? AND status = 'published' ORDER BY publish_date DESC, id DESC LIMIT 4");
                        $cat_stmt->execute([$cat['id']]);
                        $cat_articles = $cat_stmt->fetchAll();
                    ?>
                    <div class="relative group">
                        <a href="category.php?slug=<?= htmlspecialchars($cat['slug']) ?>" class="block relative mb-4">
                            <?php if ($index === 3): ?>
                            <div class="absolute top-2 left-2 bg-gold text-paper text-[9px] uppercase font-bold px-2 py-1 z-10 shadow-sm tracking-widest">Monthly Special</div>
                            <?php endif; ?>
                            <div class="aspect-[4/3] w-full bg-light overflow-hidden border border-gray-300 shadow-sm">
                                <img src="<?= $img ?>" alt="Column Image" class="w-full h-full object-cover p-1 transform group-hover:scale-105 transition duration-500">
                            </div>
                        </a>
                        <a href="category.php?slug=<?= htmlspecialchars($cat['slug']) ?>" class="block">
                            <h4 class="font-sans text-sm font-black uppercase tracking-widest text-navy hover:text-red transition-colors mb-3"><?= htmlspecialchars($cat['name']) ?></h4>
                        </a>
                        
                        <ul class="border-t border-gray-300 pt-3 space-y-3">
                            <?php foreach($cat_articles as $ca): ?>
                            <li>
                                <a href="article.php?id=<?= $ca['id'] ?>" class="text-[11px] font-bold font-serif leading-[1.3] text-navy/80 hover:text-red transition-colors block"><?= htmlspecialchars($ca['title']) ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endforeach; ?>
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
                    <img src="images/touch_logo.png" alt="The Touch Magazine" class="h-16 md:h-20 w-auto object-contain mix-blend-multiply transform scale-125 origin-left">
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

            <!-- Partner Logos Portal -->
            <div class="col-span-1 flex flex-col items-center sm:items-start text-center sm:text-left">
                <span class="text-[10px] items-center sm:items-start font-bold tracking-widest uppercase text-paper/50 mb-4 block"><?= htmlspecialchars($partner_text) ?></span>
                <div class="flex flex-wrap gap-4 items-center justify-center sm:justify-start">
                    <?php foreach($partner_logos as $plogo): ?>
                        <img src="<?= htmlspecialchars($plogo) ?>" alt="Partner Logo" class="h-12 w-auto object-contain opacity-90 transition-opacity duration-300 hover:opacity-100">
                    <?php endforeach; ?>
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