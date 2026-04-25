<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::where('username', 'superadmin')->first();
if ($user) {
    $user->password = Hash::make('admin123');
    $user->save();
    echo "Password for superadmin has been reset to: admin123\n";
} else {
    echo "User superadmin not found.\n";
}
