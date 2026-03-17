<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Columns | THE TOUCH MAGAZINE</title>
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
        <span class="text-red font-bold uppercase tracking-widest text-xs mb-4 block">Editorial Directory</span>
        <h1 class="font-serif text-5xl md:text-7xl font-black text-navy mb-6">The Columns</h1>
        <p class="text-navy/70 text-lg md:text-xl font-medium max-w-2xl">Insightful journalism categorized across our five core editorial focuses.</p>
    </header>

    <main class="bg-paper border-t border-gray-300 py-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                
                <a href="politics.html" class="group fade-up">
                    <div class="aspect-video w-full bg-light mb-6 overflow-hidden border border-gray-300 shadow-sm p-1">
                        <img src="images/politics.png" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <h3 class="font-serif text-3xl font-bold text-navy mb-3 group-hover:text-red transition-colors">Politics, Economics & Current Issues</h3>
                    <p class="text-navy/70 font-medium">Decoding regional instability and fiscal strategies.</p>
                </a>

                <a href="travel.html" class="group fade-up" style="transition-delay: 100ms">
                    <div class="aspect-video w-full bg-light mb-6 overflow-hidden border border-gray-300 shadow-sm p-1">
                        <img src="images/travel.png" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <h3 class="font-serif text-3xl font-bold text-navy mb-3 group-hover:text-red transition-colors">Travel & Photography</h3>
                    <p class="text-navy/70 font-medium">Unseen local destinations and vivid photo essays.</p>
                </a>

                <a href="culture.html" class="group fade-up" style="transition-delay: 200ms">
                    <div class="aspect-video w-full bg-light mb-6 overflow-hidden border border-gray-300 shadow-sm p-1">
                        <img src="images/culture.png" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <h3 class="font-serif text-3xl font-bold text-navy mb-3 group-hover:text-red transition-colors">Heritage, Culture & Environment</h3>
                    <p class="text-navy/70 font-medium">Preserving antiquity and analyzing ecological shifts.</p>
                </a>

                <a href="food.html" class="group fade-up">
                    <div class="aspect-video w-full bg-light mb-6 overflow-hidden border border-gray-300 shadow-sm relative p-1">
                        <div class="absolute top-2 left-2 bg-gold text-paper text-[9px] uppercase font-bold px-2 py-1 z-10 shadow-sm tracking-widest">Special Function</div>
                        <img src="images/lifestyle.png" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <h3 class="font-serif text-3xl font-bold text-navy mb-3 group-hover:text-red transition-colors">Food Recipes</h3>
                    <p class="text-navy/70 font-medium">Culinary excellence delivered from top island kitchens.</p>
                </a>

                <a href="sports.html" class="group fade-up" style="transition-delay: 100ms">
                    <div class="aspect-video w-full bg-light mb-6 overflow-hidden border border-gray-300 shadow-sm p-1">
                        <img src="images/hero.png" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <h3 class="font-serif text-3xl font-bold text-navy mb-3 group-hover:text-red transition-colors">Sports, Wellness & Entertainment</h3>
                    <p class="text-navy/70 font-medium">Peak performance analytics and cultural discourse.</p>
                </a>

            </div>
        </div>
    </main>

    <footer class="bg-navy border-t-[8px] border-red text-paper py-12 px-4 shadow-inner">
        <div class="max-w-7xl mx-auto text-center flex flex-col items-center">
            <h4 class="font-serif text-2xl font-black tracking-tighter uppercase mb-4">The Touch.</h4>
            <p class="text-[10px] font-bold uppercase tracking-widest text-paper/50">Educational Partner RGHE &bull; &copy; 2026 THE TOUCH MAGAZINE. Colombo, Sri Lanka.</p>
        </div>
    </footer>
    <script src="script.js"></script>
</body>
</html>
