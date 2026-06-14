<?php
/**
 * Script untuk reset password di server deployment
 * PENTING: Hapus file ini setelah selesai digunakan!
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$action = $_POST['action'] ?? null;
$message = '';

if ($action === 'change_password') {
    $account = $_POST['account'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (!empty($account) && !empty($new_password)) {
        try {
            $email = ($account === 'superadmin') ? 'superadmin@noc.smkn4malang.sch.id' : 'admin@noc.smkn4malang.sch.id';
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $user->password = Hash::make($new_password);
                $user->save();
                $message = "<p style='color: green; padding: 10px; background: #e8f5e9; border-radius: 5px; border: 1px solid #c8e6c9;'>✅ Password untuk <b>$email</b> berhasil diubah menjadi: <b>$new_password</b></p>";
            } else {
                // Buat jika belum ada
                $role = ($account === 'superadmin') ? 'Superadmin' : 'Admin';
                $name = ($account === 'superadmin') ? 'Super Admin NOC' : 'Admin NOC';
                $data = [
                    'name' => $name,
                    'username' => $account,
                    'email' => $email,
                    'password' => Hash::make($new_password),
                    'role' => $role,
                    'is_active' => true,
                ];
                if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'user_code')) {
                    $data['user_code'] = ($account === 'superadmin') ? 'USR-001' : 'USR-002';
                }
                User::create($data);
                $message = "<p style='color: green; padding: 10px; background: #e8f5e9; border-radius: 5px; border: 1px solid #c8e6c9;'>✅ Akun <b>$email</b> berhasil dibuat dengan password baru: <b>$new_password</b></p>";
            }
        } catch (\Exception $e) {
            $message = "<p style='color: red; padding: 10px; background: #ffebee; border-radius: 5px; border: 1px solid #ffcdd2;'><b>Terjadi Kesalahan:</b> " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p style='color: red; padding: 10px; background: #ffebee; border-radius: 5px; border: 1px solid #ffcdd2;'>Harap isi akun dan password baru!</p>";
    }
} elseif ($action === 'reset_default') {
    try {
        // 1. Reset atau Buat user Superadmin
        $superadmin = User::where('email', 'superadmin@noc.smkn4malang.sch.id')->first();
        if ($superadmin) {
            $superadmin->password = Hash::make('Superadmin2026');
            $superadmin->save();
            $message .= "<p style='color: green; margin: 5px 0;'>✅ Password <b>superadmin@noc.smkn4malang.sch.id</b> berhasil direset menjadi: <b>Superadmin2026</b></p>";
        } else {
            $data = [
                'name' => 'Super Admin NOC',
                'username' => 'superadmin',
                'email' => 'superadmin@noc.smkn4malang.sch.id',
                'password' => Hash::make('Superadmin2026'),
                'role' => 'Superadmin',
                'is_active' => true,
            ];
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'user_code')) {
                $data['user_code'] = 'USR-001';
            }
            User::create($data);
            $message .= "<p style='color: green; margin: 5px 0;'>✅ Akun <b>superadmin@noc.smkn4malang.sch.id</b> berhasil dibuat dengan password: <b>Superadmin2026</b></p>";
        }

        // 2. Reset atau Buat user Admin
        $admin = User::where('email', 'admin@noc.smkn4malang.sch.id')->first();
        if ($admin) {
            $admin->password = Hash::make('Admin2026');
            $admin->save();
            $message .= "<p style='color: green; margin: 5px 0;'>✅ Password <b>admin@noc.smkn4malang.sch.id</b> berhasil direset menjadi: <b>Admin2026</b></p>";
        } else {
            $dataAdmin = [
                'name' => 'Admin NOC',
                'username' => 'admin',
                'email' => 'admin@noc.smkn4malang.sch.id',
                'password' => Hash::make('Admin2026'),
                'role' => 'Admin',
                'is_active' => true,
            ];
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'user_code')) {
                $dataAdmin['user_code'] = 'USR-002';
            }
            User::create($dataAdmin);
            $message .= "<p style='color: green; margin: 5px 0;'>✅ Akun <b>admin@noc.smkn4malang.sch.id</b> berhasil dibuat dengan password: <b>Admin2026</b></p>";
        }
        
        $message = "<div style='padding: 10px; background: #e8f5e9; border-radius: 5px; border: 1px solid #c8e6c9;'>" . $message . "</div>";
    } catch (\Exception $e) {
        $message = "<p style='color: red; padding: 10px; background: #ffebee; border-radius: 5px; border: 1px solid #ffcdd2;'><b>Terjadi Kesalahan:</b> " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset & Ubah Password Tool</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { color: #2563eb; margin-top: 0; border-bottom: 2px solid #eff6ff; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px; color: #4b5563; }
        select, input[type="text"] { padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; width: 100%; box-sizing: border-box; font-size: 14px; transition: border-color 0.2s; }
        select:focus, input[type="text"]:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .btn { display: inline-block; padding: 10px 15px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 500; transition: background-color 0.2s; }
        .btn-warning { background: #d97706; }
        .btn:hover { opacity: 0.9; }
        .warning-text { color: #b91c1c; font-size: 13px; margin-top: 25px; padding: 12px; background: #fef2f2; border-radius: 6px; border: 1px solid #fecaca; }
        hr { border: 0; border-top: 1px solid #e5e7eb; margin: 25px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset & Ubah Password Tool</h2>
        
        <?php if ($message): ?>
            <div style="margin-bottom: 25px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="change_password">
            <h3 style="font-size: 18px; margin-bottom: 15px; color: #1f2937;">Ubah Password Manual</h3>
            <div class="form-group">
                <label>Pilih Akun:</label>
                <select name="account" required>
                    <option value="superadmin">Superadmin (superadmin@noc.smkn4malang.sch.id)</option>
                    <option value="admin">Admin (admin@noc.smkn4malang.sch.id)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Password Baru:</label>
                <input type="text" name="new_password" required placeholder="Masukkan password baru...">
            </div>
            <button type="submit" class="btn">Simpan Password Baru</button>
        </form>

        <hr>

        <form method="POST">
            <input type="hidden" name="action" value="reset_default">
            <h3 style="font-size: 18px; margin-bottom: 10px; color: #1f2937;">Reset ke Default</h3>
            <p style="font-size: 14px; color: #6b7280; margin-top: 0; margin-bottom: 15px;">Kembalikan password ke bawaan aplikasi (Superadmin2026 / Admin2026)</p>
            <button type="submit" class="btn btn-warning" onclick="return confirm('Yakin ingin reset ke password default?');">Reset ke Default</button>
        </form>

        <hr>
        <div style="text-align: center;">
            <a href="/" class="btn" style="background: #4b5563;">Kembali ke Halaman Login</a>
        </div>
        <div class="warning-text">
            ⚠️ <b>PENTING:</b> Demi keamanan, segera hapus file <b>public/fix_login.php</b> ini dari File Manager hosting Anda setelah selesai digunakan!
        </div>
    </div>
</body>
</html>
