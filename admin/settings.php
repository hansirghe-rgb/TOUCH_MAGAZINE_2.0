<?php
// admin/settings.php
require_once '../config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize partner_logos from DB or fallback
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'partner_logos'");
    $stmt->execute();
    $existing_json = $stmt->fetchColumn();
    
    $partner_logos = [];
    if ($existing_json) {
        $partner_logos = json_decode($existing_json, true) ?: [];
    } else {
        // Fallback to old single logo
        $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'rghe_logo'");
        $stmt->execute();
        $old_logo = $stmt->fetchColumn();
        if ($old_logo) $partner_logos = [$old_logo];
    }

    // Handle delete logo action via POST (using hidden input)
    if (isset($_POST['delete_logo_index'])) {
        $del_idx = (int)$_POST['delete_logo_index'];
        if (isset($partner_logos[$del_idx])) {
            array_splice($partner_logos, $del_idx, 1);
            $msg = "Logo deleted. ";
            $message .= $msg;
        }
    }

    // Handling Multiple Logo Uploads
    $uploaded_any = false;
    if (isset($_FILES['logos']) && is_array($_FILES['logos']['name'])) {
        $allowed = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
        
        foreach ($_FILES['logos']['name'] as $key => $name) {
            if ($_FILES['logos']['error'][$key] === UPLOAD_ERR_OK) {
                $file_array = [
                    'name' => $_FILES['logos']['name'][$key],
                    'type' => $_FILES['logos']['type'][$key],
                    'tmp_name' => $_FILES['logos']['tmp_name'][$key],
                    'error' => $_FILES['logos']['error'][$key],
                    'size' => $_FILES['logos']['size'][$key]
                ];
                $upload = upload_secure_file($file_array, '../uploads/images', $allowed);
                if ($upload['success']) {
                    $path = str_replace('../', '', $upload['path']);
                    $partner_logos[] = $path; // Append new logo
                    $uploaded_any = true;
                } else {
                    $message .= "File $name: " . $upload['error'] . " ";
                }
            }
        }
    }

    // Save updated logos back to DB
    if ($uploaded_any || isset($_POST['delete_logo_index'])) {
        $json_val = json_encode(array_values($partner_logos));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM site_settings WHERE setting_key = 'partner_logos'");
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'partner_logos'");
            $stmt->execute([$json_val]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, description) VALUES ('partner_logos', ?, 'JSON Array of partner logos')");
            $stmt->execute([$json_val]);
        }
        if ($uploaded_any) $message .= "New logos added securely. ";
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
        <h3 class="font-serif text-xl font-bold text-navy mb-4">Educational Partner Logos (Footer)</h3>
        <p class="text-xs text-navy/60 font-medium mb-6">Upload multiple logos to display your partners.</p>
        
        <?php
        $partner_logos = [];
        if (isset($settings['partner_logos']['setting_value'])) {
            $partner_logos = json_decode($settings['partner_logos']['setting_value'], true) ?: [];
        } elseif (isset($settings['rghe_logo']['setting_value']) && !empty($settings['rghe_logo']['setting_value'])) {
            $partner_logos = [$settings['rghe_logo']['setting_value']];
        }
        ?>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <?php foreach ($partner_logos as $idx => $plogo): ?>
                <div class="relative bg-light border border-gray-300 p-2 h-24 flex items-center justify-center group">
                    <img src="../<?= htmlspecialchars($plogo) ?>" alt="Partner Logo" class="max-w-full max-h-full object-contain mix-blend-multiply">
                    <button type="submit" name="delete_logo_index" value="<?= $idx ?>" onclick="return confirm('Remove this logo?');" class="absolute top-0 right-0 bg-red text-paper p-1 text-[9px] uppercase font-bold opacity-0 group-hover:opacity-100 transition-opacity" title="Delete Logo">X</button>
                </div>
            <?php endforeach; ?>
            <?php if (empty($partner_logos)): ?>
                <div class="col-span-full text-[10px] uppercase font-bold text-navy/40 mb-2">No Logos added yet</div>
            <?php endif; ?>
        </div>
            
        <div>
            <label class="block text-xs font-bold tracking-widest uppercase text-navy mb-2">Add New Logos (.png, .jpg, .svg)</label>
            <input type="file" name="logos[]" accept=".png,.jpg,.jpeg,.svg,.webp" multiple class="block w-full text-sm text-navy file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-widest file:bg-navy file:text-paper hover:file:bg-red file:transition-colors bg-light border border-gray-300 p-2 cursor-pointer">
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
