<?php
// admin/issues.php
require_once '../config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'new') {
    $title = $_POST['title'];
    $month = $_POST['issue_month'];
    $year = $_POST['issue_year'];
    $description = $_POST['description'];
    $is_latest = isset($_POST['is_latest']) ? 1 : 0;
    
    $cover_path = '';
    $pdf_path = '';

    // Handle Uploads
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $upload = upload_secure_file($_FILES['cover'], '../uploads/issues', ['image/jpeg', 'image/png']);
        if ($upload['success']) $cover_path = str_replace('../', '', $upload['path']);
    }
    
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $upload = upload_secure_file($_FILES['pdf'], '../uploads/issues', ['application/pdf', 'text/html', 'text/htm', 'application/zip', 'application/x-zip-compressed']);
        if ($upload['success']) $pdf_path = str_replace('../', '', $upload['path']);
    }

    if ($cover_path && $pdf_path && !empty($title)) {
        // If this is set as latest, un-latest others
        if ($is_latest) {
             $pdo->query("UPDATE issues SET is_latest = 0");
        }
        
        $stmt = $pdo->prepare("INSERT INTO issues (title, issue_month, issue_year, cover_image, pdf_file, description, is_latest) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $month, $year, $cover_path, $pdf_path, $description, $is_latest]);
        $message = "Issue uploaded securely.";
        $action = 'list'; // return to list view
    } else {
        $message = "Please fill all required fields and upload both valid cover image and Digital File (.html/.pdf).";
    }
}

// Fetch issue if editing
$edit_issue = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM issues WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $edit_issue = $stmt->fetch();
    if (!$edit_issue) $action = 'list';
}

// Handle delete action
if ($action === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM issues WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $message = "Issue deleted successfully.";
    $action = 'list';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $month = $_POST['issue_month'];
    $year = $_POST['issue_year'];
    $description = $_POST['description'];
    $is_latest = isset($_POST['is_latest']) ? 1 : 0;
    
    // Default to existing paths
    $stmt = $pdo->prepare("SELECT cover_image, pdf_file FROM issues WHERE id = ?");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    $cover_path = $existing['cover_image'];
    $pdf_path = $existing['pdf_file'];

    // Handle Uploads if new ones are provided
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $upload = upload_secure_file($_FILES['cover'], '../uploads/issues', ['image/jpeg', 'image/png']);
        if ($upload['success']) $cover_path = str_replace('../', '', $upload['path']);
    }
    
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $upload = upload_secure_file($_FILES['pdf'], '../uploads/issues', ['application/pdf', 'text/html', 'text/htm', 'application/zip', 'application/x-zip-compressed']);
        if ($upload['success']) $pdf_path = str_replace('../', '', $upload['path']);
    }

    if (!empty($title)) {
        // If this is set as latest, un-latest others
        if ($is_latest) {
             $pdo->query("UPDATE issues SET is_latest = 0");
        }
        
        $stmt = $pdo->prepare("UPDATE issues SET title = ?, issue_month = ?, issue_year = ?, cover_image = ?, pdf_file = ?, description = ?, is_latest = ? WHERE id = ?");
        $stmt->execute([$title, $month, $year, $cover_path, $pdf_path, $description, $is_latest, $id]);
        $message = "Issue updated successfully.";
        $action = 'list';
    } else {
        $message = "Title is required to save changes.";
    }
}

// Fetch all issues
$stmt = $pdo->query("SELECT * FROM issues ORDER BY issue_year DESC, issue_month DESC");
$issues = $stmt->fetchAll();

?>

<div class="flex justify-between items-center mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-serif font-bold text-navy">Digital Archives & Issues</h2>
    <?php if ($action === 'list'): ?>
        <a href="?action=new" class="bg-navy text-paper px-6 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-red transition-colors border border-navy">
            Upload New Issue
        </a>
    <?php else: ?>
        <a href="issues.php" class="text-[10px] font-bold uppercase tracking-widest text-navy hover:text-red transition-colors">
            &larr; Back to Archives
        </a>
    <?php endif; ?>
</div>

<?php if ($message): ?>
    <div class="bg-paper border border-navy text-navy p-4 mb-8 text-sm font-bold uppercase tracking-widest shadow-sm">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach($issues as $issue): ?>
        <div class="bg-white border border-gray-300 p-4 shadow-sm relative group">
            <?php if ($issue['is_latest']): ?>
                <div class="absolute -top-3 -right-3 bg-red text-paper text-[9px] uppercase font-bold px-2 py-1 shadow-sm z-10 tracking-widest">Live Edition</div>
            <?php endif; ?>
            
            <div class="aspect-[3/4] overflow-hidden bg-light mb-4 border border-gray-200">
                <img src="../<?= htmlspecialchars($issue['cover_image']) ?>" alt="Cover" class="w-full h-full object-cover">
            </div>
            
            <h3 class="font-serif font-bold text-lg text-navy leading-tight mb-1"><?= htmlspecialchars($issue['title']) ?></h3>
            <p class="text-[10px] font-bold uppercase tracking-widest text-navy/50 mb-4"><?= htmlspecialchars($issue['issue_month']) ?> <?= htmlspecialchars($issue['issue_year']) ?></p>
            
            <div class="border-t border-gray-200 pt-3 text-[10px] font-bold uppercase tracking-widest flex justify-between">
                <a href="../<?= htmlspecialchars($issue['pdf_file']) ?>" target="_blank" class="text-navy hover:text-red">View File</a>
                <div class="space-x-1 border-l border-gray-200 pl-2 ml-1 flex-shrink-0">
                    <a href="?action=edit&id=<?= $issue['id'] ?>" class="text-navy hover:text-red">Edit</a> | 
                    <a href="?action=delete&id=<?= $issue['id'] ?>" onclick="return confirm('Are you sure you want to delete this issue?');" class="text-red hover:text-navy">Del</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (count($issues)===0): ?>
        <div class="col-span-full p-8 text-center text-navy/50 font-bold uppercase tracking-widest">
            No issues archived.
        </div>
    <?php endif; ?>
</div>

<?php elseif ($action === 'new' || $action === 'edit'): ?>

<?php
$f_id = $edit_issue['id'] ?? '';
$f_title = $edit_issue['title'] ?? '';
$f_month = $edit_issue['issue_month'] ?? '';
$f_year = $edit_issue['issue_year'] ?? date('Y');
$f_desc = $edit_issue['description'] ?? '';
$f_is_latest = !empty($edit_issue['is_latest']);
$f_cover = $edit_issue['cover_image'] ?? '';
$f_pdf = $edit_issue['pdf_file'] ?? '';
?>

<form method="POST" action="?action=<?= $action ?>" enctype="multipart/form-data" class="bg-white p-8 border border-gray-300 shadow-sm max-w-4xl">
    <?php if ($action === 'edit'): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($f_id) ?>">
        <div class="text-red font-bold text-[10px] uppercase tracking-widest mb-4">Editing Issue ID <?= $f_id ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Issue Title *</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($f_title) ?>" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy uppercase" placeholder="e.g. The Urban Renaissance">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Month *</label>
                <select name="issue_month" required class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
                    <?php 
                    $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                    foreach($months as $m) echo "<option value='$m' " . ($f_month === $m ? 'selected' : '') . ">$m</option>";
                    ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Year *</label>
                <input type="number" name="issue_year" required value="<?= htmlspecialchars($f_year) ?>" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
            </div>
        </div>
    </div>
    
    <div class="mb-8">
        <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Issue Teaser Description *</label>
        <textarea name="description" rows="3" required class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy resize-none"><?= htmlspecialchars($f_desc) ?></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 pb-8 border-b border-gray-200">
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">1. Cover Image Upload (.jpg, .png)</label>
            <?php if ($f_cover): ?>
                <div class="w-24 h-32 bg-light mb-2 overflow-hidden border border-gray-300">
                    <img src="../<?= htmlspecialchars($f_cover) ?>" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            <input type="file" name="cover" accept="image/*" <?= $action === 'new' ? 'required' : '' ?> class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-light file:text-navy hover:file:bg-navy hover:file:text-paper file:border file:border-gray-300 file:transition-colors bg-white border border-gray-300 p-2 cursor-pointer mt-2">
            <?php if ($f_cover): ?>
                <span class="text-[10px] uppercase text-navy/50 tracking-widest">Leave empty to keep current cover</span>
            <?php endif; ?>
        </div>
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">2. Magazine Digital File (.html, .pdf, .zip)</label>
            <?php if ($f_pdf): ?>
                <div class="mb-2 text-xs font-bold text-navy">Current: <a href="../<?= htmlspecialchars($f_pdf) ?>" target="_blank" class="text-red hover:underline">View File</a></div>
            <?php endif; ?>
            <input type="file" name="pdf" accept=".html,.htm,.pdf,.zip" <?= $action === 'new' ? 'required' : '' ?> class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-light file:text-navy hover:file:bg-navy hover:file:text-paper file:border file:border-gray-300 file:transition-colors bg-white border border-gray-300 p-2 cursor-pointer mt-2">
            <?php if ($f_pdf): ?>
                <span class="text-[10px] uppercase text-navy/50 tracking-widest">Leave empty to keep current file</span>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mb-10 flex items-center">
        <input type="checkbox" id="is_latest" name="is_latest" value="1" <?= $f_is_latest ? 'checked' : '' ?> class="w-4 h-4 text-navy bg-light border-gray-300 rounded focus:ring-navy focus:ring-2">
        <label for="is_latest" class="ml-2 text-xs font-bold uppercase tracking-widest text-navy">Set as current latest issue (Overrides prev. latest)</label>
    </div>

    <button type="submit" class="bg-navy text-paper px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-red transition-colors shadow-none border border-navy">
        <?= $action === 'edit' ? 'Save Changes' : 'Process Issue Upload' ?>
    </button>
</form>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
