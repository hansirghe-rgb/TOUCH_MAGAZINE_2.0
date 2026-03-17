<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politics & Economy | THE TOUCH MAGAZINE</title>
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
            <a href="index.html" class="font-serif text-3xl font-black tracking-tighter text-navy uppercase flex items-center">
                The Touch <span class="text-red ml-2 relative -top-2 text-4xl leading-none">.</span>
            </a>
            <div class="hidden lg:flex space-x-8 items-center text-xs font-bold uppercase tracking-widest text-navy">
                <a href="index.html" class="hover:text-red transition-colors py-1">Home</a>
                <a href="latest-issue.html" class="hover:text-red transition-colors py-1">Latest Issue</a>
                <a href="columns.html" class="text-red border-b-2 border-red transition-colors py-1">Columns</a>
                <a href="podcasts.html" class="hover:text-red transition-colors py-1">Podcasts</a>
                <a href="about.html" class="hover:text-red transition-colors py-1">About Us</a>
                <a href="contact.html" class="bg-navy text-paper px-4 py-2 hover:bg-red transition-colors">Contact</a>
            </div>
            <button id="mobile-menu-btn" class="lg:hidden text-navy focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-4 sm:px-8 py-16 fade-up">
        <a href="columns.html" class="text-[10px] font-bold uppercase tracking-widest text-navy hover:text-red mb-8 inline-block">&larr; Back to Columns</a>
        <h1 class="font-serif text-5xl md:text-7xl font-black text-navy mb-6">Politics, Economics & Current Issues</h1>
    </header>

    <main class="bg-paper border-t border-gray-300 py-20 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-8">
            <article class="fade-up">
                <div class="aspect-[21/9] w-full bg-light overflow-hidden mb-10 shadow-sm border border-gray-300 p-1">
                    <img src="images/politics.png" class="w-full h-full object-cover">
                </div>
                <div class="news-line-thick w-16 mb-6"></div>
                <h2 class="font-serif text-4xl md:text-5xl font-bold leading-tight mb-6">Decoding the National Economic Policy Restructuring</h2>
                <div class="flex items-center text-xs font-bold text-navy/50 uppercase tracking-widest mb-10 pb-6 border-b border-gray-300">
                    <span class="text-navy mr-4">By The Touch Editorial Board</span>
                    <span>12 Min Read</span>
                </div>
                
                <div class="prose prose-lg text-navy/80 font-medium leading-relaxed">
                    <p class="mb-6 font-bold text-xl text-navy">As Colombo gears up for unprecedented fiscal policies, an exclusive deep-dive into the strategies aimed at stabilization and long-term exponential growth.</p>
                    <p class="mb-6">The corridors of power in Colombo have been abuzz for the past month as sweeping new economic frameworks enter the legislative pipeline. Designed to address structural imbalances while fostering an environment attractive to foreign direct investment, the proposed restructuring is being touted as the most significant pivot in decades.</p>
                    <p class="mb-6">Wait until the full article unfolds in our print edition, or subscribe to gain access to our premium digital archive.</p>
                </div>
            </article>
        </div>
    </main>

    <footer class="bg-navy border-t-[8px] border-red text-paper py-12 px-4 text-center">
        <p class="text-[10px] font-bold uppercase tracking-widest text-paper/50">Educational Partner RGHE &bull; &copy; 2026 THE TOUCH MAGAZINE. Colombo, Sri Lanka.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
