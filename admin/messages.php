<?php
// admin/messages.php
require_once '../config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$message = '';

if ($action === 'mark_read' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE contact_messages SET status = 'read' WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $message = "Submission marked as read.";
    $action = 'list';
}

$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();

?>

<div class="flex justify-between items-center mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-serif font-bold text-navy">Secure Submissions</h2>
</div>

<?php if ($message): ?>
    <div class="bg-paper border border-navy text-navy p-4 mb-8 text-sm font-bold uppercase tracking-widest shadow-sm">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div class="bg-white border border-gray-300 shadow-sm overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-light border-b border-gray-300 text-[10px] uppercase tracking-widest text-navy/60">
                <th class="p-4 font-bold">Source Name</th>
                <th class="p-4 font-bold">Contact Channel</th>
                <th class="p-4 font-bold">Encrypted Message Snapshot</th>
                <th class="p-4 font-bold">Log Date</th>
                <th class="p-4 font-bold">Status</th>
                <th class="p-4 font-bold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            <?php foreach($messages as $msg): ?>
            <tr class="border-b border-gray-200 <?= $msg['status'] === 'unread' ? 'bg-[#9B2C2C]/5' : 'hover:bg-light transition-colors' ?>">
                <td class="p-4 font-serif font-bold text-navy">
                    <?= htmlspecialchars($msg['name']) ?>
                </td>
                <td class="p-4 font-medium text-navy/70"><?= htmlspecialchars($msg['email']) ?></td>
                <td class="p-4 font-medium text-navy/70 max-w-sm truncate">
                    <?= htmlspecialchars($msg['message']) ?>
                </td>
                <td class="p-4 font-medium text-navy/70 text-xs"><?= date('M j, Y H:i', strtotime($msg['created_at'])) ?></td>
                <td class="p-4">
                    <span class="text-[9px] px-2 py-1 uppercase tracking-widest font-bold <?= $msg['status'] === 'unread' ? 'bg-red text-paper' : 'bg-gray-200 text-gray-600' ?>">
                        <?= htmlspecialchars($msg['status']) ?>
                    </span>
                </td>
                <td class="p-4 text-right space-x-2">
                    <?php if($msg['status'] === 'unread'): ?>
                        <a href="?action=mark_read&id=<?= $msg['id'] ?>" class="text-[10px] uppercase tracking-widest text-navy hover:text-red font-bold">Acknowledge</a>
                    <?php else: ?>
                        <span class="text-[10px] uppercase tracking-widest text-navy/30 font-bold">Logged</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($messages) === 0): ?>
            <tr>
                <td colspan="6" class="p-8 text-center text-xs font-bold uppercase tracking-widest text-navy/50">No secure transmissions found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
