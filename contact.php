<?php
require_once 'config/database.php';

$message_status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if($name && $email && $message) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        $message_status = 'Transmission secured and logged with the editorial desk.';
    } else {
        $message_status = 'Error: All fields are required to establish connection.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact & Submissions | THE TOUCH MAGAZINE</title>
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
                <a href="latest-issue.php" class="hover:text-red transition-colors py-1">Latest Issue</a>
                <a href="columns.php" class="hover:text-red transition-colors py-1">Columns</a>
                <a href="podcasts.php" class="hover:text-red transition-colors py-1">Podcasts</a>
                <a href="about.php" class="hover:text-red transition-colors py-1">About Us</a>
                <a href="contact.php" class="bg-navy text-paper px-4 py-2 hover:bg-red transition-colors border border-navy shadow-none">Contact</a>
            </div>
            <button id="mobile-menu-btn" class="lg:hidden text-navy focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>
    </nav>

    <main class="py-20 max-w-7xl mx-auto px-4 sm:px-8 min-h-screen">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
            
            <div class="fade-up">
                <span class="text-red font-bold uppercase tracking-widest text-xs mb-4 block">Headquarters</span>
                <h1 class="font-serif text-5xl md:text-6xl font-black text-navy mb-8 leading-tight">Connect with the <br>Editorial Desk.</h1>
                
                <div class="mt-12 space-y-8">
                    <div>
                        <h4 class="font-sans font-black text-navy uppercase tracking-widest text-xs mb-2">Office Address</h4>
                        <p class="text-navy/70 font-medium">The Touch Studios<br>102 Bauddhaloka Mawatha<br>Colombo 04</p>
                    </div>

                    <div class="news-line pt-8 border-gray-300">
                        <h4 class="font-sans font-black text-navy uppercase tracking-widest text-xs mb-2">Direct Lines</h4>
                        <p class="text-navy/70 font-medium mb-1"><span class="font-bold text-navy w-24 inline-block">General:</span> +94 11 233 3000</p>
                        <p class="text-navy/70 font-medium mb-1"><span class="font-bold text-navy w-24 inline-block">Editorial:</span> editor@thetouch.lk</p>
                        <p class="text-navy/70 font-medium"><span class="font-bold text-navy w-24 inline-block">Advertising:</span> ads@thetouch.lk</p>
                    </div>
                </div>
            </div>

            <div class="fade-up bg-paper p-8 sm:p-12 border border-gray-300 shadow-md">
                <h3 class="font-serif text-3xl font-bold text-navy mb-8">Submit a Story / Tip</h3>
                
                <?php if ($message_status): ?>
                    <div class="bg-navy border border-red text-paper p-4 mb-6 text-xs font-bold uppercase tracking-widest text-center shadow-inner">
                        <?= htmlspecialchars($message_status) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="contact.php" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Full Name</label>
                        <input type="text" name="name" required class="w-full border-b border-gray-300 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Email Address</label>
                        <input type="email" name="email" required class="w-full border-b border-gray-300 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Message / Tip Outline</label>
                        <textarea rows="5" name="message" required class="w-full border-b border-gray-300 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium resize-none"></textarea>
                    </div>
                    <button type="submit" name="submit_contact" class="bg-navy text-paper px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-red transition-colors w-full border border-navy shadow-none">Broadcast Message</button>
                    <p class="text-[10px] text-gray-500 font-medium mt-4 text-center">We respect journalistic sources and guarantee confidentiality upon request.</p>
                </form>
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
