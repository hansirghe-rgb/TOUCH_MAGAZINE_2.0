<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | THE TOUCH MAGAZINE</title>
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
                <img src="images/touch_logo.png" alt="The Touch Magazine" class="h-8 md:h-10 w-auto object-contain">
            </a>
            <div class="hidden lg:flex space-x-8 items-center text-xs font-bold uppercase tracking-widest text-navy">
                <a href="index.php" class="hover:text-red transition-colors py-1">Home</a>
                <a href="latest-issue.php" class="hover:text-red transition-colors py-1">Latest Issue</a>
                <a href="columns.php" class="hover:text-red transition-colors py-1">Columns</a>
                <a href="podcasts.php" class="hover:text-red transition-colors py-1">Podcasts</a>
                <a href="about.php" class="text-red border-b-2 border-red transition-colors py-1">About Us</a>
                <a href="contact.php" class="bg-navy text-paper px-4 py-2 hover:bg-red transition-colors">Contact</a>
            </div>
            <button id="mobile-menu-btn" class="lg:hidden text-navy focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-4 sm:px-8 pt-20 pb-16 fade-up">
        <div class="max-w-3xl">
            <span class="text-red font-bold uppercase tracking-widest text-xs mb-6 block">Our Origin</span>
            <h1 class="font-serif text-5xl md:text-7xl font-black text-navy mb-10 leading-tight">Insightful Journalism Born Through <i class="font-normal text-red">Humility</i>.</h1>
        </div>
    </header>

    <main class="bg-paper border-t border-gray-300 py-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            
            <div class="fade-up order-2 lg:order-1 prose prose-lg font-medium text-navy/80 leading-relaxed">
                <p class="font-black text-2xl text-navy">THE TOUCH MAGAZINE is a premium monthly news publication.</p>
                <p>Designed and edited in Colombo, Sri Lanka, our publication is built on a foundation of rigorous reporting and sophisticated visual storytelling. In an era of fragmented media consumption, we believe there is still an unparalleled value to curated, in-depth columns.</p>
                <p>We traverse politics, economic shifts, cultural heritage, culinary arts, and wellness. It's a comprehensive dossier designed for the intelligent reader seeking credible narratives.</p>
                
                <div class="mt-12 p-8 border border-gray-300 bg-light shadow-sm">
                    <h3 class="font-sans font-black text-navy uppercase tracking-widest text-sm mb-4">Editorial Integrity</h3>
                    <p class="text-sm font-medium">Our editorial board is committed to fact-based reporting. We do not aggregate; we investigate, interview, and interpret.</p>
                </div>
            </div>

            <div class="fade-up order-1 lg:order-2">
                <div class="aspect-[3/4] w-full bg-light overflow-hidden shadow-lg border border-gray-300 p-2">
                    <img src="images/politics.png" class="w-full h-full object-cover grayscale mix-blend-multiply opacity-80 pt-10">
                </div>
            </div>

        </div>
    </main>

    <footer class="bg-navy border-t-[8px] border-red text-paper py-12 px-4 shadow-inner text-center">
        <h4 class="font-serif text-2xl font-black tracking-tighter uppercase mb-4">The Touch.</h4>
        <p class="text-[10px] font-bold uppercase tracking-widest text-paper/50">Educational Partner RGHE &bull; &copy; 2026 THE TOUCH MAGAZINE. Colombo, Sri Lanka.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>