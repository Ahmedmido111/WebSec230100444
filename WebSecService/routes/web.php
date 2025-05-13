<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Models\User;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('forgot-password', [UsersController::class, 'forgotPassword'])->name('forgot_password');
Route::post('forgot-password', [UsersController::class, 'sendResetPassword'])->name('send_reset_password');
Route::get('reset-password/{token}', [UsersController::class, 'showResetPassword'])->name('reset_password');
Route::post('reset-password', [UsersController::class, 'updatePassword'])->name('update_password');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');



Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');


Route::get('/auth/google', [UsersController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback', [UsersController::class, 'handleGoogleCallback']);

Route::get('/auth/linkedin', [UsersController::class, 'redirectToLinkedin'])->name('login_with_linkedin');
Route::get('/auth/linkedin/callback', [UsersController::class, 'handleLinkedinCallback']);

Route::get('verify', [UsersController::class, 'verify'])->name('verify');


Route::get('/', function () {

    $email = emailFromLoginCertificate();
    if($email && !auth()->user()) {
        $user = User::where('email', $email)->first();
        if($user) Auth::login($user);
    }
    return view('welcome');
   });

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});



Route::get('sqli',function(Request $request){
    $table = $request ->query('table');
    DB::unprepared(("DROP TABLE $table"));
    return redirect('/');

});

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');



Route::get('/collect', function (Request $request){
    $name = $$request ->query('name');
    $credit = $$request ->query('credit');


    return response('data collect',200)
        ->header('Access-Control-Allow-Origin','*')
        ->header('Access-Control-Allow-Methods','GET, POST, OPTIONS')
        ->header('Access-Control-Allow-Headers','Content-Type,X-Requested-With');

});

Route::get('/cryptography', function (Request $request) {
    $data = $request->data ?? "Welcome to Cryptography";
    $action = $request->action ?? "Encrypt";
    $result = "";
    $status = "Failed";

    if ($action == "Encrypt") {
        $temp = openssl_encrypt($data, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
        if ($temp) {
            $status = 'Encrypted Successfully';
            $result = base64_encode($temp);
        }
    } elseif ($action == "Decrypt") {
        $temp = base64_decode($data);
        $result = openssl_decrypt($temp, 'aes-128-ecb', 'thisisasecretkey', OPENSSL_RAW_DATA, '');
        if ($result) {
            $status = 'Decrypted Successfully';
        }
    } elseif ($action == "Hash") {
        $temp = hash('sha256', $request->data);
        $result = base64_encode($temp);
        $status = 'Hashed Successfully';
    } elseif ($action == "Sign") {
        $path = storage_path('app/private/useremail@domain.com.pfx');
        $password = '123456789';
        $certificates = [];
        $pfx = file_get_contents($path);
        openssl_pkcs12_read($pfx, $certificates, $password);
        $privateKey = $certificates['pkey'];
        $signature = '';
        if (openssl_sign($request->data, $signature, $privateKey, 'sha256')) {
            $result = base64_encode($signature);
            $status = 'Signed Successfully';
        }
    } elseif ($action == "Verify") {
    $signature = base64_decode($request->result);
    $path = storage_path('app/public/useremail@domain.com.crt');

    if (!file_exists($path)) {
        $status = 'Public certificate not found.';
    } else {
        $publicKey = file_get_contents($path);
        if (!$publicKey) {
            $status = 'Failed to read public key.';
        } elseif (openssl_verify($request->data, $signature, $publicKey, OPENSSL_ALGO_SHA256)) {
            $status = 'Verified Successfully';
        } else {
            $status = 'Verification Failed.';
        }
    }
    } elseif ($action == "KeySend") {
        $path = storage_path('app/public/useremail@domain.com.crt');
        $publicKey = file_get_contents($path);
        $temp = '';
        if (openssl_public_encrypt($request->data, $temp, $publicKey)) {
            $result = base64_encode($temp);
            $status = 'Key is Encrypted Successfully';
        }
    } elseif ($action == "KeyRecive") {
    $path = storage_path('app/private/useremail@domain.com.pfx');
    $password = '12345678';

    if (!file_exists($path)) {
        $status = 'PFX file not found.';
    } else {
        $certificates = [];
        $pfx = file_get_contents($path);

        if (!openssl_pkcs12_read($pfx, $certificates, $password)) {
            $status = 'Failed to read PFX. Check password.';
        } else {
            $privateKey = $certificates['pkey'];
            $encryptedKey = base64_decode($request->data);
            $result = '';

            if (openssl_private_decrypt($encryptedKey, $result, $privateKey)) {
                $status = 'Key is Decrypted Successfully';
            } else {
                $status = 'Key Decryption Failed';
            }
        }
    }
}

    return view('cryptography', compact('data', 'result', 'action', 'status'));
})->name('cryptography');







