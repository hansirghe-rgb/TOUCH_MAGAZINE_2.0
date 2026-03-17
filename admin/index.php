<?php
// admin/index.php
session_start();
require_once '../config/database.php';
require_once 'includes/header.php'; // Will enforce login

// Fetch statistics
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM articles");
$articles_count = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM podcasts");
$podcasts_count = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM issues");
$issues_count = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM contact_messages WHERE status = 'unread'");
$messages_count = $stmt->fetch()['total'];

?>

<h2 class="text-3xl font-serif font-bold text-navy mb-8 border-b border-gray-300 pb-4">Editorial Desk Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Stat Cards -->
    <div class="bg-white p-6 border border-gray-300 shadow-sm relative group overflow-hidden">
        <div class="absolute w-1 h-full bg-navy left-0 top-0 group-hover:w-full group-hover:opacity-5 transition-all duration-500"></div>
        <h3 class="text-xs font-bold tracking-widest uppercase text-navy/60 mb-2">Total Articles</h3>
        <p class="text-4xl font-serif font-black text-navy"><?= $articles_count ?></p>
        <a href="articles.php" class="text-[10px] font-bold uppercase tracking-widest text-red hover:text-navy mt-4 inline-block">Manage Desk &rarr;</a>
    </div>

    <div class="bg-white p-6 border border-gray-300 shadow-sm relative group overflow-hidden">
        <div class="absolute w-1 h-full bg-navy left-0 top-0 group-hover:w-full group-hover:opacity-5 transition-all duration-500"></div>
        <h3 class="text-xs font-bold tracking-widest uppercase text-navy/60 mb-2">Digital Issues</h3>
        <p class="text-4xl font-serif font-black text-navy"><?= $issues_count ?></p>
        <a href="issues.php" class="text-[10px] font-bold uppercase tracking-widest text-red hover:text-navy mt-4 inline-block">Manage Archives &rarr;</a>
    </div>

    <div class="bg-white p-6 border border-gray-300 shadow-sm relative group overflow-hidden">
        <div class="absolute w-1 h-full bg-navy left-0 top-0 group-hover:w-full group-hover:opacity-5 transition-all duration-500"></div>
        <h3 class="text-xs font-bold tracking-widest uppercase text-navy/60 mb-2">Studio Podcasts</h3>
        <p class="text-4xl font-serif font-black text-navy"><?= $podcasts_count ?></p>
        <a href="podcasts.php" class="text-[10px] font-bold uppercase tracking-widest text-red hover:text-navy mt-4 inline-block">Manage Studios &rarr;</a>
    </div>

    <div class="bg-white p-6 border border-gray-300 shadow-sm relative group overflow-hidden">
        <div class="absolute w-1 h-full bg-red left-0 top-0"></div>
        <h3 class="text-xs font-bold tracking-widest uppercase text-navy/60 mb-2">Secure Submissions (Unread)</h3>
        <p class="text-4xl font-serif font-black text-red"><?= $messages_count ?></p>
        <a href="messages.php" class="text-[10px] font-bold uppercase tracking-widest text-red hover:text-navy mt-4 inline-block">Read Mail &rarr;</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white border border-gray-300 p-6">
        <h3 class="font-serif text-xl font-bold text-navy mb-4">Quick Protocols</h3>
        <div class="space-y-3 font-medium text-sm text-navy/80">
            <a href="articles.php?action=new" class="block bg-light p-3 border border-gray-300 hover:bg-navy hover:text-white transition-colors">&plus; Compose New Article</a>
            <a href="issues.php?action=new" class="block bg-light p-3 border border-gray-300 hover:bg-navy hover:text-white transition-colors">&plus; Publish Monthly Digital Issue</a>
            <a href="settings.php" class="block bg-light p-3 border border-gray-300 hover:bg-navy hover:text-white transition-colors">&plus; Update Campus Partner Logo</a>
        </div>
    </div>
    
    <div class="bg-white border border-gray-300 p-6">
         <h3 class="font-serif text-xl font-bold text-navy mb-4">System Status</h3>
         <div class="text-[10px] font-bold uppercase tracking-widest text-navy bg-light p-4 border border-gray-300">
             <div class="flex justify-between border-b border-gray-300 pb-2 mb-2"><span>Connection:</span> <span class="text-green-600">Secure PDO</span></div>
             <div class="flex justify-between border-b border-gray-300 pb-2 mb-2"><span>Auth Level:</span> <span>Privileged Read/Write</span></div>
             <div class="flex justify-between border-b border-gray-300 pb-2 mb-2"><span>PHP System V:</span> <span><?= phpversion() ?></span></div>
         </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
