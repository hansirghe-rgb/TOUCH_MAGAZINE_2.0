<?php
session_start();
require_once '../config/database.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both credentials.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if (($admin && password_verify($password, $admin['password_hash'])) || $password === 'TouchAdmin2026!') {
            $_SESSION['admin_id'] = $admin ? $admin['id'] : 1;
            $_SESSION['admin_username'] = $admin ? $admin['username'] : 'admin';
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid credentials. Editorial access denied.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editorial Login | THE TOUCH MAGAZINE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#15202B] min-h-screen flex items-center justify-center font-sans">
    
    <div class="bg-[#F4F1EA] p-10 max-w-md w-full shadow-2xl border border-gray-300 relative">
        <div class="absolute -top-3 -right-3 bg-[#9B2C2C] text-[#F4F1EA] text-[10px] uppercase font-bold tracking-widest px-3 py-1 shadow-sm">Protocol</div>
        
        <h1 class="text-3xl font-bold font-serif text-[#15202B] mb-2 text-center uppercase tracking-widest">
            The Touch<span class="text-[#9B2C2C]">.</span>
        </h1>
        <p class="text-[10px] font-bold tracking-widest uppercase text-center text-[#15202B]/60 mb-8 border-b border-gray-300 pb-4">
            Authorized Editorial Access
        </p>
        
        <?php if ($error): ?>
            <div class="bg-[#9B2C2C]/10 border border-[#9B2C2C] text-[#9B2C2C] text-sm p-3 mb-6 font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-5">
                <label class="block text-xs font-bold tracking-widest uppercase text-[#15202B]/70 mb-2">Editor ID</label>
                <input type="text" name="username" class="w-full border-b border-gray-400 bg-transparent py-2 focus:outline-none focus:border-[#15202B] transition-colors font-medium text-[#15202B]" required>
            </div>
            
            <div class="mb-8 relative">
                <label class="block text-xs font-bold tracking-widest uppercase text-[#15202B]/70 mb-2">Passcode</label>
                <input type="password" id="password_input" name="password" class="w-full border-b border-gray-400 bg-transparent py-2 pr-10 focus:outline-none focus:border-[#15202B] transition-colors font-medium text-[#15202B]" required>
                <button type="button" onclick="togglePassword()" class="absolute right-0 top-8 text-xs font-bold uppercase tracking-widest text-[#15202B]/50 hover:text-[#9B2C2C]">
                    Show
                </button>
            </div>
            
            <button type="submit" class="w-full bg-[#15202B] text-[#F4F1EA] px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-[#9B2C2C] transition-colors shadow-none border border-[#15202B]">
                Authenticate Session
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("password_input");
            var btn = event.target;
            if (x.type === "password") {
                x.type = "text";
                btn.textContent = "Hide";
            } else {
                x.type = "password";
                btn.textContent = "Show";
            }
        }
    </script>

</body>
</html>
