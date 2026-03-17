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
            <a href="index.html" class="font-serif text-3xl font-black tracking-tighter text-navy uppercase flex items-center">
                The Touch <span class="text-red ml-2 relative -top-2 text-4xl leading-none">.</span>
            </a>
            <div class="hidden lg:flex space-x-8 items-center text-xs font-bold uppercase tracking-widest text-navy">
                <a href="index.html" class="hover:text-red transition-colors py-1">Home</a>
                <a href="latest-issue.html" class="hover:text-red transition-colors py-1">Latest Issue</a>
                <a href="columns.html" class="hover:text-red transition-colors py-1">Columns</a>
                <a href="podcasts.html" class="text-red border-b-2 border-red transition-colors py-1">Podcasts</a>
                <a href="about.html" class="hover:text-red transition-colors py-1">About Us</a>
                <a href="contact.html" class="bg-navy text-paper px-4 py-2 hover:bg-red transition-colors">Contact</a>
            </div>
            <button id="mobile-menu-btn" class="lg:hidden text-navy focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </nav>
    <div id="mobile-menu" class="fixed inset-0 z-[100] bg-paper transform translate-x-full transition-transform duration-500 flex flex-col items-center justify-center border-l border-gray-300">
        <button id="close-menu-btn" class="absolute top-8 right-8 text-navy hover:text-red"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        <div class="flex flex-col space-y-6 text-center font-serif text-2xl font-bold uppercase tracking-widest">
            <a href="index.html" class="hover:text-red">Home</a><a href="latest-issue.html" class="hover:text-red">Latest Issue</a><a href="columns.html" class="hover:text-red">Columns</a><a href="podcasts.html" class="text-red">Podcasts</a><a href="about.html" class="hover:text-red">About Us</a><a href="contact.html" class="hover:text-red">Contact</a>
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
                        <label for="podcast-upload" class="cursor-pointer border border-navy text-navy px-4 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-navy hover:text-paper transition-colors">
                            Upload Video File
                        </label>
                        <input type="file" id="podcast-upload" accept="video/*,image/*" class="hidden">
                    </div>

                    <div class="w-full aspect-video bg-light relative border border-gray-300 overflow-hidden shadow-lg flex items-center justify-center p-1">
                        <!-- Actual Video Player hidden by default until upload -->
                        <video id="main-podcast-video" class="w-full h-full object-cover hidden" controls></video>
                        
                        <!-- Thumbnail state -->
                        <img id="main-podcast-thumb" src="images/politics.png" alt="Broadcast Thumbnail" class="w-full h-full object-cover opacity-90 transition-opacity">
                        <div class="absolute inset-0 bg-gradient-to-t from-navy/80 via-transparent to-transparent pointer-events-none"></div>

                        <!-- Play Overlay Icon -->
                        <div id="podcast-play-btn" class="absolute z-20 w-16 h-16 bg-paper shadow-xl flex items-center justify-center cursor-pointer hover:scale-110 transition-transform text-navy">
                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                        </div>

                        <!-- Info tag -->
                        <div class="absolute bottom-6 left-6 z-10 pointer-events-none text-paper">
                            <h3 class="font-serif text-2xl font-bold mb-1">Live: The Economic Restructuring</h3>
                            <p class="text-[10px] font-bold tracking-widest uppercase opacity-80">Ep 01 &bull; 45 Mins</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Episodes Sidebar -->
                <div class="lg:col-span-4 fade-up">
                    <h2 class="font-serif text-3xl font-black text-navy mb-6 border-b-[3px] border-navy pb-2">Recent Episodes</h2>
                    <div class="flex flex-col gap-6">
                        
                        <!-- Card 1 -->
                        <div class="flex gap-4 border border-gray-300 bg-light p-3 hover:shadow-md transition-shadow group cursor-pointer">
                            <div class="w-32 h-24 flex-shrink-0 bg-gray-200 relative border border-gray-300 p-1">
                                <img src="images/travel.png" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-paper p-2 text-navy opacity-80 group-hover:opacity-100 transition-opacity"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg></div>
                                </div>
                            </div>
                            <div class="flex flex-col justify-center">
                                <h4 class="font-bold text-sm text-navy mb-1 leading-tight group-hover:text-red transition-colors">Tourism Sector Recovery Tactics</h4>
                                <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest">Ep 23 &bull; 32 Mins</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="flex gap-4 border border-gray-300 bg-light p-3 hover:shadow-md transition-shadow group cursor-pointer">
                            <div class="w-32 h-24 flex-shrink-0 bg-gray-200 relative border border-gray-300 p-1">
                                <img src="images/culture.png" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="bg-paper p-2 text-navy opacity-80 group-hover:opacity-100 transition-opacity"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg></div>
                                </div>
                            </div>
                            <div class="flex flex-col justify-center">
                                <h4 class="font-bold text-sm text-navy mb-1 leading-tight group-hover:text-red transition-colors">Preserving Ancient Sites</h4>
                                <p class="text-[10px] font-bold text-navy/50 uppercase tracking-widest">Ep 22 &bull; 50 Mins</p>
                            </div>
                        </div>

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
