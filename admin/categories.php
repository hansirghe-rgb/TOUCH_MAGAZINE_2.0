<?php
// admin/categories.php
require_once '../config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'new') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    // Basic slugification if empty
    if (empty($slug) && !empty($name)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }

    if (!empty($name) && !empty($slug)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            $stmt->execute([$name, $slug]);
            $message = "Column / Category successfully added.";
            $action = 'list';
        } catch(PDOException $e) {
            $message = "Error: Could not add category. Slug might already exist.";
        }
    } else {
        $message = "Name and Slug are required.";
    }
}

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();

?>

<div class="flex justify-between items-center mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-serif font-bold text-navy">Columns / Categories</h2>
    <?php if ($action === 'list'): ?>
        <a href="?action=new" class="bg-navy text-paper px-6 py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-red transition-colors border border-navy">
            Add Column
        </a>
    <?php else: ?>
        <a href="categories.php" class="text-[10px] font-bold uppercase tracking-widest text-navy hover:text-red transition-colors">
            &larr; Back to Columns
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
                <th class="p-4 font-bold">Column ID</th>
                <th class="p-4 font-bold">Name</th>
                <th class="p-4 font-bold">Slug (URL endpoint)</th>
                <th class="p-4 font-bold hidden md:table-cell">Created At</th>
                <th class="p-4 font-bold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            <?php foreach($categories as $cat): ?>
            <tr class="border-b border-gray-200 hover:bg-light transition-colors">
                <td class="p-4 font-medium text-navy/70">#<?= htmlspecialchars($cat['id']) ?></td>
                <td class="p-4 font-serif font-bold text-navy">
                    <?= htmlspecialchars($cat['name']) ?>
                </td>
                <td class="p-4 font-medium text-navy/70"><?= htmlspecialchars($cat['slug']) ?></td>
                <td class="p-4 font-medium text-navy/70 hidden md:table-cell"><?= htmlspecialchars($cat['created_at']) ?></td>
                <td class="p-4 text-right space-x-2">
                    <a href="#" class="text-[10px] uppercase tracking-widest text-navy hover:text-red font-bold">Edit</a>
                    <a href="#" class="text-[10px] uppercase tracking-widest text-red hover:text-navy font-bold">Del</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($categories) === 0): ?>
            <tr>
                <td colspan="5" class="p-8 text-center text-xs font-bold uppercase tracking-widest text-navy/50">No columns/categories found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php elseif ($action === 'new'): ?>

<form method="POST" action="?action=new" class="bg-white p-8 border border-gray-300 shadow-sm max-w-2xl">
    <div class="grid grid-cols-1 gap-8 mb-8 mt-4">
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Column Name *</label>
            <input type="text" name="name" required class="w-full border-b border-gray-400 bg-transparent py-2 text-2xl font-serif focus:outline-none focus:border-navy transition-colors font-bold text-navy" placeholder="e.g. World News">
        </div>
        
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">URL Slug (Optional)</label>
            <p class="text-[10px] text-navy/50 mb-2 font-medium uppercase tracking-widest">Leave blank to auto-generate from name.</p>
            <input type="text" name="slug" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy" placeholder="e.g. world-news">
        </div>
    </div>
    
    <button type="submit" class="bg-navy text-paper px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-red transition-colors shadow-none border border-navy mt-4 mb-4">
        Add Column
    </button>
</form>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
