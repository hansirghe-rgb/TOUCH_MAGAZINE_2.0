<?php
// admin/includes/header.php
require_once 'auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touch Magazine - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#15202B',
                        red: '#9B2C2C',
                        light: '#EBE7E0',
                        paper: '#F4F1EA',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-light text-navy font-sans antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-navy text-paper flex-shrink-0 min-h-screen shadow-lg hidden md:flex flex-col">
        <div class="px-6 py-8 border-b border-paper/10">
            <h1 class="text-xl font-bold uppercase tracking-widest text-red">The Touch<span class="text-paper">.</span></h1>
            <p class="text-[10px] text-paper/50 uppercase tracking-widest mt-1">Editorial Protocol</p>
        </div>
        
        <nav class="flex-grow py-6 px-4 space-y-2 text-sm font-medium">
            <a href="index.php" class="block px-4 py-2 rounded hover:bg-red hover:text-white transition-colors">Dashboard</a>
            <a href="articles.php" class="block px-4 py-2 rounded hover:bg-red hover:text-white transition-colors">Articles / Desk</a>
            <a href="issues.php" class="block px-4 py-2 rounded hover:bg-red hover:text-white transition-colors">Digital Archives</a>
            <a href="podcasts.php" class="block px-4 py-2 rounded hover:bg-red hover:text-white transition-colors">The Studios (AV)</a>
            <a href="categories.php" class="block px-4 py-2 rounded hover:bg-red hover:text-white transition-colors">Columns</a>
            <a href="messages.php" class="block px-4 py-2 rounded hover:bg-red hover:text-white transition-colors">Secure Submissions</a>
            <a href="settings.php" class="block px-4 py-2 rounded hover:bg-red hover:text-white transition-colors">Partner Settings</a>
        </nav>
        
        <div class="p-4 border-t border-paper/10">
            <a href="logout.php" class="block text-center border border-paper/30 text-paper/70 hover:text-white hover:border-white px-4 py-2 rounded transition-colors text-xs uppercase tracking-widest">Logout System</a>
            <a href="../index.php" target="_blank" class="block text-center mt-3 text-[10px] text-paper/50 hover:text-white transition-colors uppercase tracking-widest">View Live Site &rarr;</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col bg-paper">
        <header class="bg-white border-b border-gray-200 py-4 px-8 shadow-sm flex items-center justify-between">
            <div class="flex items-center space-x-4 md:hidden">
                <h1 class="text-xl font-bold text-navy uppercase tracking-widest">The Touch<span class="text-red">.</span></h1>
            </div>
            <div class="text-sm font-bold text-navy/60 uppercase tracking-widest ms-auto">
                Admin Secure Session
            </div>
        </header>
        
        <div class="p-8 flex-grow">
