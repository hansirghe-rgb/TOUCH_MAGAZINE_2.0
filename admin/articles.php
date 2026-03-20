<?php
// admin/articles.php
require_once '../config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'new') {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $content = $_POST['content'];
    $author_name = $_POST['author_name'];
    $publish_date = $_POST['publish_date'] ?: date('Y-m-d');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_editor_pick = isset($_POST['is_editor_pick']) ? 1 : 0;
    $status = $_POST['status'];
    
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = upload_secure_file($_FILES['image'], '../uploads/images', ['image/jpeg', 'image/png']);
        if ($upload['success']) $image_path = str_replace('../', '', $upload['path']);
    }

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO articles (category_id, title, subtitle, content, image_path, author_name, publish_date, is_featured, is_editor_pick, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $title, $subtitle, $content, $image_path, $author_name, $publish_date, $is_featured, $is_editor_pick, $status]);
        $message = "Article published to desk.";
        $action = 'list';
    } else {
        $message = "Title and Content are required.";
    }
}

// Fetch article if editing
$edit_article = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $edit_article = $stmt->fetch();
    if (!$edit_article) $action = 'list';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $content = $_POST['content'];
    $author_name = $_POST['author_name'];
    $publish_date = $_POST['publish_date'] ?: date('Y-m-d');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_editor_pick = isset($_POST['is_editor_pick']) ? 1 : 0;
    $status = $_POST['status'];
    
    // Default to existing image
    $stmt = $pdo->prepare("SELECT image_path FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $image_path = $stmt->fetchColumn();

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = upload_secure_file($_FILES['image'], '../uploads/images', ['image/jpeg', 'image/png']);
        if ($upload['success']) $image_path = str_replace('../', '', $upload['path']);
    }

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("UPDATE articles SET category_id = ?, title = ?, subtitle = ?, content = ?, image_path = ?, author_name = ?, publish_date = ?, is_featured = ?, is_editor_pick = ?, status = ? WHERE id = ?");
        $stmt->execute([$category_id, $title, $subtitle, $content, $image_path, $author_name, $publish_date, $is_featured, $is_editor_pick, $status, $id]);
        $message = "Article updated successfully.";
        $action = 'list';
    } else {
        $message = "Title and Content are required to save changes.";
    }
}

// Fetch articles
$stmt = $pdo->query("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id ORDER BY publish_date DESC, id DESC");
$articles = $stmt->fetchAll();

// Fetch categories for form
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();

?>

<div class="flex justify-between items-center mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-serif font-bold text-navy">Article Desk</h2>
    <?php if ($action === 'list'): ?>
        <a href="?action=new" class="bg-navy text-paper px-6 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-red transition-colors border border-navy">
            Compose Article
        </a>
    <?php else: ?>
        <a href="articles.php" class="text-[10px] font-bold uppercase tracking-widest text-navy hover:text-red transition-colors">
            &larr; Back to Desk
        </a>
    <?php endif; ?>
</div>

<?php if ($message): ?>
    <div class="bg-paper border border-navy text-navy p-4 mb-8 text-sm font-bold uppercase tracking-widest shadow-sm">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if ($action === 'list'): ?>

<div class="bg-white border border-gray-300 shadow-sm overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-light border-b border-gray-300 text-[10px] uppercase tracking-widest text-navy/60">
                <th class="p-4 font-bold">Headline</th>
                <th class="p-4 font-bold hidden md:table-cell">Column</th>
                <th class="p-4 font-bold">Author</th>
                <th class="p-4 font-bold hidden lg:table-cell">Date</th>
                <th class="p-4 font-bold">Status</th>
                <th class="p-4 font-bold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            <?php foreach($articles as $article): ?>
            <tr class="border-b border-gray-200 hover:bg-light transition-colors">
                <td class="p-4 font-serif font-bold text-navy">
                    <?= htmlspecialchars($article['title']) ?>
                    <?php if($article['is_editor_pick']) echo '<span class="ml-2 text-[9px] bg-red text-paper px-1 uppercase tracking-widest">Ed Pick</span>'; ?>
                </td>
                <td class="p-4 font-medium text-navy/70 hidden md:table-cell"><?= htmlspecialchars($article['category_name']) ?></td>
                <td class="p-4 font-medium text-navy/70"><?= htmlspecialchars($article['author_name']) ?></td>
                <td class="p-4 font-medium text-navy/70 hidden lg:table-cell"><?= htmlspecialchars($article['publish_date']) ?></td>
                <td class="p-4">
                    <span class="text-[10px] px-2 py-1 uppercase tracking-widest font-bold <?= $article['status'] === 'published' ? 'bg-navy text-paper' : 'bg-gray-200 text-gray-600' ?>">
                        <?= htmlspecialchars($article['status']) ?>
                    </span>
                </td>
                <td class="p-4 text-right space-x-2">
                    <a href="?action=edit&id=<?= $article['id'] ?>" class="text-[10px] uppercase tracking-widest text-navy hover:text-red font-bold">Edit</a>
                    <!-- Del currently not wired robustly in this quick iteration, focused on Edit -->
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($articles) === 0): ?>
            <tr>
                <td colspan="6" class="p-8 text-center text-xs font-bold uppercase tracking-widest text-navy/50">No articles drafted or published.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php elseif ($action === 'new' || $action === 'edit'): ?>

<?php 
// Prepare form variables
$f_id = $edit_article['id'] ?? '';
$f_title = $edit_article['title'] ?? '';
$f_subtitle = $edit_article['subtitle'] ?? '';
$f_category_id = $edit_article['category_id'] ?? '';
$f_author_name = $edit_article['author_name'] ?? 'The Touch Editorial Board';
$f_content = $edit_article['content'] ?? '';
$f_is_featured = !empty($edit_article['is_featured']);
$f_is_editor_pick = !empty($edit_article['is_editor_pick']);
$f_status = $edit_article['status'] ?? 'draft';
$f_image = $edit_article['image_path'] ?? '';
?>

<form method="POST" action="?action=<?= $action ?>" enctype="multipart/form-data" class="bg-white p-8 border border-gray-300 shadow-sm max-w-4xl">
    <?php if ($action === 'edit'): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($f_id) ?>">
        <div class="text-red font-bold text-[10px] uppercase tracking-widest mb-4">Editing Article ID <?= $f_id ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="col-span-1 md:col-span-2">
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Headline / Title *</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($f_title) ?>" class="w-full border-b border-gray-400 bg-transparent py-2 text-2xl font-serif focus:outline-none focus:border-navy transition-colors font-bold text-navy" placeholder="Enter article headline...">
        </div>
        <div class="col-span-1 md:col-span-2">
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Subtitle / Summary Hook</label>
            <input type="text" name="subtitle" value="<?= htmlspecialchars($f_subtitle) ?>" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
        </div>
        
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Column / Category *</label>
            <select name="category_id" required class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $f_category_id == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Author Name</label>
            <input type="text" name="author_name" value="<?= htmlspecialchars($f_author_name) ?>" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
        </div>
    </div>
    
    <div class="mb-8">
        <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Article Body Content *</label>
        <p class="text-[10px] text-navy/50 mb-2 font-medium uppercase tracking-widest">Supports basic HTML (e.g. &lt;p&gt;, &lt;h2&gt;, &lt;strong&gt;, line breaks)</p>
        <textarea name="content" rows="15" required class="w-full border border-gray-300 bg-transparent p-4 focus:outline-none focus:border-navy transition-colors font-medium text-navy resize-y"><?= htmlspecialchars($f_content) ?></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 pb-8 border-b border-gray-200">
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Featured Image (.jpg, .png)</label>
            <?php if ($f_image): ?>
                <div class="w-32 h-16 bg-light mb-2 overflow-hidden border border-gray-300">
                    <img src="../<?= htmlspecialchars($f_image) ?>" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            <input type="file" name="image" accept="image/*" class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-light file:text-navy hover:file:bg-navy hover:file:text-paper file:border file:border-gray-300 file:transition-colors bg-white border border-gray-300 p-2 cursor-pointer mt-2">
            <?php if ($f_image): ?>
                <span class="text-[10px] uppercase text-navy/50 tracking-widest">Leave empty to keep current image</span>
            <?php endif; ?>
        </div>
        <div class="space-y-4 pt-4">
             <div class="flex items-center">
                <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= $f_is_featured ? 'checked' : '' ?> class="w-4 h-4 text-navy bg-light border-gray-300 rounded focus:ring-navy focus:ring-2">
                <label for="is_featured" class="ml-2 text-xs font-bold uppercase tracking-widest text-navy">Featured Story (Top Stories area)</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="is_editor_pick" name="is_editor_pick" value="1" <?= $f_is_editor_pick ? 'checked' : '' ?> class="w-4 h-4 text-navy bg-light border-gray-300 rounded focus:ring-navy focus:ring-2">
                <label for="is_editor_pick" class="ml-2 text-xs font-bold uppercase tracking-widest text-navy">Editor's Pick</label>
            </div>
            <div class="flex items-center">
                <label class="text-xs font-bold uppercase tracking-widest text-navy mr-4">Status:</label>
                <select name="status" class="border border-gray-300 bg-transparent py-1 px-4 focus:outline-none focus:border-navy transition-colors font-medium text-sm text-navy">
                    <option value="draft" <?= $f_status === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $f_status === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </div>
        </div>
    </div>

    <button type="submit" class="bg-navy text-paper px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-red transition-colors shadow-none border border-navy">
        <?= $action === 'edit' ? 'Save Changes' : 'Save & Publish Protocol' ?>
    </button>
</form>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
