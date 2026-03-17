<?php
// admin/podcasts.php
require_once '../config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'new') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video_url = $_POST['video_url']; // This could be an embed link (YouTube/Vimeo) or raw url
    $duration = $_POST['duration'];
    $status = $_POST['status'];
    
    $thumbnail_path = '';
    
    // Allow direct file upload for video if video_url is empty, but for brevity/efficiency usually a youtube embed is better.
    // If we want a raw upload:
    if (empty($video_url) && isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $upload = upload_secure_file($_FILES['video'], '../uploads/podcasts', ['video/mp4', 'video/webm']);
        if ($upload['success']) $video_url = str_replace('../', '', $upload['path']);
    }

    if (isset($_FILES['thumb']) && $_FILES['thumb']['error'] === UPLOAD_ERR_OK) {
        $upload = upload_secure_file($_FILES['thumb'], '../uploads/podcasts', ['image/jpeg', 'image/png']);
        if ($upload['success']) $thumbnail_path = str_replace('../', '', $upload['path']);
    }

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO podcasts (title, description, thumbnail_path, video_url, duration, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $thumbnail_path, $video_url, $duration, $status]);
        $message = "Podcast studio feed updated securely.";
        $action = 'list';
    } else {
        $message = "Title is required.";
    }
}

// Fetch all podcasts
$stmt = $pdo->query("SELECT * FROM podcasts ORDER BY id DESC");
$podcasts = $stmt->fetchAll();

?>

<div class="flex justify-between items-center mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-serif font-bold text-navy">The Studios (AV)</h2>
    <?php if ($action === 'list'): ?>
        <a href="?action=new" class="bg-navy text-paper px-6 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-red transition-colors border border-navy">
            Publish New Broadcast
        </a>
    <?php else: ?>
        <a href="podcasts.php" class="text-[10px] font-bold uppercase tracking-widest text-navy hover:text-red transition-colors">
            &larr; Back to Studios
        </a>
    <?php endif; ?>
</div>

<?php if ($message): ?>
    <div class="bg-paper border border-navy text-navy p-4 mb-8 text-sm font-bold uppercase tracking-widest shadow-sm">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($podcasts as $podcast): ?>
        <div class="bg-white border border-gray-300 p-4 shadow-sm">
            <div class="aspect-video overflow-hidden bg-navy mb-4 border border-gray-200 relative flex items-center justify-center group">
                <?php if($podcast['thumbnail_path']): ?>
                    <img src="../<?= htmlspecialchars($podcast['thumbnail_path']) ?>" alt="Thumbnail" class="w-full h-full object-cover opacity-80 mix-blend-luminosity">
                <?php endif; ?>
                <div class="absolute w-12 h-12 bg-paper text-navy flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform cursor-pointer">
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                </div>
            </div>
            
            <div class="flex justify-between items-start mb-2">
                <h3 class="font-serif font-bold text-lg text-navy leading-tight"><?= htmlspecialchars($podcast['title']) ?></h3>
                <span class="text-[9px] px-2 py-1 uppercase tracking-widest font-bold <?= $podcast['status'] === 'published' ? 'bg-red text-paper' : 'bg-gray-200 text-gray-600' ?>">
                        <?= htmlspecialchars($podcast['status']) ?>
                </span>
            </div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-navy/50 mb-4">Duration: <?= htmlspecialchars($podcast['duration'] ?: '--:--') ?></p>
            
            <div class="border-t border-gray-200 pt-3 text-[10px] font-bold uppercase tracking-widest text-right">
                <a href="#" class="text-navy hover:text-red">Edit Broadcast</a>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (count($podcasts)===0): ?>
        <div class="col-span-full p-8 text-center text-navy/50 font-bold uppercase tracking-widest">
            No studio broadcasts recorded.
        </div>
    <?php endif; ?>
</div>

<?php elseif ($action === 'new'): ?>

<form method="POST" action="?action=new" enctype="multipart/form-data" class="bg-white p-8 border border-gray-300 shadow-sm max-w-4xl">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="col-span-1 md:col-span-2">
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Broadcast Title *</label>
            <input type="text" name="title" required class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy text-xl font-serif">
        </div>
        
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Duration (e.g. 45:00)</label>
            <input type="text" name="duration" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
        </div>
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Status</label>
            <select name="status" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
    </div>
    
    <div class="mb-8">
        <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Contextual Description</label>
        <textarea name="description" rows="3" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy resize-none"></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 pb-8 border-b border-gray-200">
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Thumbnail Cover (.jpg, .png)</label>
            <input type="file" name="thumb" accept="image/*" class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-light file:text-navy hover:file:bg-navy hover:file:text-paper file:border file:border-gray-300 file:transition-colors bg-white border border-gray-300 p-2 cursor-pointer mt-2">
        </div>
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Video Source</label>
            <input type="text" name="video_url" placeholder="Alternatively, paste YouTube/Vimeo Embed URL here" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy text-xs mb-4">
            
            <p class="text-[10px] text-navy/50 font-bold uppercase tracking-widest mb-1">OR UPLOAD RAW FILE (.mp4)</p>
            <input type="file" name="video" accept="video/mp4,video/webm" class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-widest file:bg-light file:text-navy hover:file:bg-navy hover:file:text-paper file:border file:border-gray-300 file:transition-colors bg-white border border-gray-300 p-1 cursor-pointer">
        </div>
    </div>

    <button type="submit" class="bg-navy text-paper px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-red transition-colors shadow-none border border-navy">
        Process Broadcast Update
    </button>
</form>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
