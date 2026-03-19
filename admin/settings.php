<?php
// admin/settings.php
require_once '../config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handling Logo Upload if provided
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/svg+xml'];
        $upload = upload_secure_file($_FILES['logo'], '../uploads/images', $allowed);
        if ($upload['success']) {
            $path = str_replace('../', '', $upload['path']); // relative to frontend
            $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'rghe_logo'");
            $stmt->execute([$path]);
            $message .= "Logo updated successfully. ";
        } else {
            $message .= $upload['error'] . " ";
        }
    }

    // Handling Background Image Upload
    if (isset($_FILES['hero_bg_image']) && $_FILES['hero_bg_image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $upload = upload_secure_file($_FILES['hero_bg_image'], '../uploads/images', $allowed);
        if ($upload['success']) {
            $path = str_replace('../', '', $upload['path']); // relative to frontend
            
            // Check if setting exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_key = 'hero_bg_image'");
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'hero_bg_image'");
                $stmt->execute([$path]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, description) VALUES ('hero_bg_image', ?, 'Homepage Hero Background Image')");
                $stmt->execute([$path]);
            }
            $message .= "Background Image updated successfully. ";
        } else {
            $message .= $upload['error'] . " ";
        }
    }


    // Handle string settings
    $settings_to_update = [
        'hero_headline', 'hero_subheading', 'hero_text', 'footer_partner_text'
    ];
    
    foreach ($settings_to_update as $key) {
        if (isset($_POST[$key])) {
            $val = $_POST[$key];
            // Only update if not empty to prevent clearing by accident, or allow clearing if needed
            $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$val, $key]);
        }
    }
    
    $message .= "Settings saved securely.";
}

// Fetch current settings
$stmt = $pdo->query("SELECT setting_key, setting_value, description FROM site_settings");
$settings = [];
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row;
}
?>

<div class="flex justify-between items-center mb-8 border-b border-gray-300 pb-4">
    <h2 class="text-3xl font-serif font-bold text-navy">Partner Settings & Homepage</h2>
</div>

<?php if ($message): ?>
    <div class="bg-paper border border-navy text-navy p-4 mb-8 text-sm font-bold uppercase tracking-widest shadow-sm">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" class="bg-white p-8 border border-gray-300 shadow-sm max-w-4xl">
    
    <div class="mb-10 pb-8 border-b border-gray-200">
        <h3 class="font-serif text-xl font-bold text-navy mb-4">Educational Partner Logo (Footer)</h3>
        <p class="text-xs text-navy/60 font-medium mb-6"><?= htmlspecialchars($settings['rghe_logo']['description']) ?></p>
        
        <div class="flex items-start gap-8">
            <!-- Current Logo Preview -->
            <div class="w-48 h-32 bg-light border border-gray-300 flex items-center justify-center p-4">
                <?php if (!empty($settings['rghe_logo']['setting_value'])): ?>
                    <img src="../<?= htmlspecialchars($settings['rghe_logo']['setting_value']) ?>" alt="Partner Logo" class="max-w-full max-h-full object-contain">
                <?php else: ?>
                    <span class="text-[10px] uppercase font-bold text-navy/40">No Logo</span>
                <?php endif; ?>
            </div>
            
            <div class="flex-grow">
                <label class="block text-xs font-bold tracking-widest uppercase text-navy mb-2">Upload New Logo (.png, .jpg, .svg)</label>
                <input type="file" name="logo" accept=".png,.jpg,.jpeg,.svg" class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-navy file:text-paper hover:file:bg-red file:transition-colors bg-light border border-gray-300 p-2 cursor-pointer">
            </div>
        </div>
    </div>

    <div class="mb-10 pb-8 border-b border-gray-200">
         <h3 class="font-serif text-xl font-bold text-navy mb-4">Homepage Hero Content</h3>
         
         <div class="space-y-6">
             <div>
                 <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Hero Headline</label>
                 <input type="text" name="hero_headline" value="<?= htmlspecialchars($settings['hero_headline']['setting_value'] ?? '') ?>" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
                 <p class="text-[10px] text-navy/50 mt-1 uppercase tracking-widest">You may use basic HTML tags like &lt;br&gt; or &lt;span class="italic font-normal"&gt; for styling.</p>
             </div>
             <div>
                 <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Hero Subheading</label>
                 <input type="text" name="hero_subheading" value="<?= htmlspecialchars($settings['hero_subheading']['setting_value'] ?? '') ?>" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
             </div>
             <div>
                 <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Hero Descriptive Text</label>
                 <textarea name="hero_text" rows="3" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy resize-none"><?= htmlspecialchars($settings['hero_text']['setting_value'] ?? '') ?></textarea>
             </div>
             <div>
                 <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Background Image (.png, .jpg, .webp)</label>
                 <?php if (!empty($settings['hero_bg_image']['setting_value'])): ?>
                    <div class="mb-2 w-48 h-24 bg-light border border-gray-300 flex items-center justify-center">
                        <img src="../<?= htmlspecialchars($settings['hero_bg_image']['setting_value']) ?>" alt="Background Preview" class="max-w-full max-h-full object-cover">
                    </div>
                 <?php endif; ?>
                 <input type="file" name="hero_bg_image" accept=".png,.jpg,.jpeg,.webp" class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-navy file:text-paper hover:file:bg-red file:transition-colors bg-light border border-gray-300 p-2 cursor-pointer">
             </div>
         </div>
    </div>

    <div class="mb-10">
         <h3 class="font-serif text-xl font-bold text-navy mb-4">Footer Text</h3>
         <div>
             <label class="block text-xs font-bold tracking-widest uppercase text-navy/70 mb-2">Partner Text Line</label>
             <input type="text" name="footer_partner_text" value="<?= htmlspecialchars($settings['footer_partner_text']['setting_value']) ?>" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-navy transition-colors font-medium text-navy">
         </div>
    </div>

    <button type="submit" class="bg-navy text-paper px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-red transition-colors shadow-none border border-navy">
        Update Settings
    </button>
</form>

<?php require_once 'includes/footer.php'; ?>
