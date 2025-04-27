<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        if(!auth()->user()->hasPermissionTo('show_users'))abort(401);
        $query = User::select('*');
        $query->when($request->keywords, 
        fn($q)=> $q->where("name", "like", "%$request->keywords%"));
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {
    	try {
    		$this->validate($request, [
	        'name' => ['required', 'string', 'min:5'],
	        'email' => ['required', 'email', 'unique:users'],
	        'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
	    	]);
    	}
    	catch(\Exception $e) {
    		return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
    	}

    	try {
	    	// Create the user first
	    	$user = User::create([
	            'name' => $request->name,
	            'email' => $request->email,
	            'password' => bcrypt($request->password),
	        ]);

	    	// Now we can use the $user variable
	    	$title = "Verification Link";
	        $token = Crypt::encryptString(json_encode(['id' => $user->id, 'email' => $user->email]));
	        $link = route("verify", ['token' => $token]);
	        
	        // Log the email attempt with more details
	        \Log::info('Attempting to send verification email', [
	            'to' => $user->email,
	            'from' => config('mail.from.address'),
	            'host' => config('mail.mailers.smtp.host'),
	            'port' => config('mail.mailers.smtp.port'),
	            'encryption' => config('mail.mailers.smtp.encryption'),
	            'username' => config('mail.mailers.smtp.username')
	        ]);
	        
	        // Try to send the email with error handling
	        try {
	            $mail = new VerificationEmail($link, $user->name);
	            Mail::to($user->email)->send($mail);
	            \Log::info('Verification email sent successfully to: ' . $user->email);
	            return redirect('/')->with('success', 'Registration successful! Please check your email for verification.');
	        } catch (\Swift_TransportException $e) {
	            \Log::error('Swift Transport Error: ' . $e->getMessage());
	            \Log::error('Error details: ' . $e->getTraceAsString());
	            return redirect()->back()->withInput($request->input())->withErrors('Could not connect to mail server. Please try again later.');
	        } catch (\Exception $e) {
	            \Log::error('General Email Error: ' . $e->getMessage());
	            \Log::error('Error details: ' . $e->getTraceAsString());
	            return redirect()->back()->withInput($request->input())->withErrors('Email could not be sent. Please try again later.');
	        }
    	} catch (\Exception $e) {
    		\Log::error('Registration Error: ' . $e->getMessage());
    		\Log::error('Error details: ' . $e->getTraceAsString());
    		return redirect()->back()->withInput($request->input())->withErrors('Registration failed. Please try again.');
    	}
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        if(!$user->email_verified_at)
            return redirect()->back()->withInput($request->input())
                ->withErrors('Your email is not verified.');

        // Check if the user is using a temporary password
        if (session('using_temporary_password')) {
            session()->forget('using_temporary_password'); // Clear the session variable
            return redirect()->route('edit_password');
        }

        return redirect('/');
    }

    public function doLogout(Request $request) {
    	
    	Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $permissions = [];
        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null) {
   
        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }
    
        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }      

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {

        if(auth()->id()!=$user->id) {
            if(!auth()->user()->hasPermissionTo('show_users')) abort(401);
        }

        $user->name = $request->name;
        $user->save();

        if(auth()->user()->hasPermissionTo('admin_users')) {

            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);

            Artisan::call('cache:clear');
        }

        //$user->syncRoles([1]);
        //Artisan::call('cache:clear');

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function delete(Request $request, User $user) {

        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);

        //$user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function verify(Request $request) {

        $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        $user = User::find($decryptedData['id']);
        if(!$user) abort(401);
        $user->email_verified_at = Carbon::now();
        $user->save();
        return view('users.verified', compact('user'));
       }

    public function forgotPassword(Request $request) {
        return view('users.forgot_password');
    }

    public function sendResetPassword(Request $request) {
        try {
            $this->validate($request, [
                'email' => ['required', 'email', 'exists:users,email'],
            ]);
        } catch(\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid email address.');
        }

        try {
            $user = User::where('email', $request->email)->first();
            
            // Generate token
            $token = Str::random(64);
            
            // Store token in database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]
            );

            // Create reset link
            $resetLink = route('reset_password', ['token' => $token]);

            // Send email with reset link
            $mail = new ResetPasswordEmail($resetLink, $user->name);
            Mail::to($user->email)->send($mail);

            return redirect()->back()->with('success', 'Password reset link has been sent to your email address.');
        } catch (\Exception $e) {
            \Log::error('Password Reset Error: ' . $e->getMessage());
            return redirect()->back()->withInput($request->input())->withErrors('Failed to send reset password email. Please try again later.');
        }
    }

    public function showResetPassword(Request $request, $token) {
        $resetToken = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('created_at', '>', Carbon::now()->subMinutes(60))
            ->first();

        if (!$resetToken) {
            return redirect()->route('login')->withErrors('Invalid or expired password reset link.');
        }

        return view('users.reset_password', [
            'token' => $token,
            'email' => $resetToken->email
        ]);
    }

    public function updatePassword(Request $request) {
        try {
            $this->validate($request, [
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            $resetToken = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->where('created_at', '>', Carbon::now()->subMinutes(60))
                ->first();

            if (!$resetToken) {
                return redirect()->route('login')->withErrors('Invalid or expired password reset link.');
            }

            // Update password
            $user = User::where('email', $request->email)->first();
            $user->password = bcrypt($request->password);
            $user->save();

            // Delete the token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login')->with('success', 'Your password has been reset successfully. Please login with your new password.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->input())->withErrors('Failed to reset password. Please try again.');
        }

    }

    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->scopes(['email', 'profile'])
                ->stateless()
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Failed to connect to Google. Please try again.');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            if (!$googleUser->email) {
                return redirect()->route('login')->with('error', 'Could not retrieve email from Google.');
            }
            
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                ]);
            }
            
            Auth::login($user);
            
            return redirect()->intended('/');
        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong with Google login. Please try again.');
        }
    }

    public function redirectToLinkedin()
    {
        try {
            return Socialite::driver('linkedin')->redirect();
        } catch (\Exception $e) {
            \Log::error('LinkedIn Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Failed to connect to LinkedIn. Please try again.');
        }
    }

    public function handleLinkedinCallback()
    {
        try {
            $linkedinUser = Socialite::driver('linkedin')->user();
            
            if (!$linkedinUser->email) {
                return redirect()->route('login')->with('error', 'Could not retrieve email from LinkedIn.');
            }
            
            $user = User::where('email', $linkedinUser->email)->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $linkedinUser->name,
                    'email' => $linkedinUser->email,
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                ]);
            }
            
            Auth::login($user);
            
            return redirect()->intended('/');
        } catch (\Exception $e) {
            \Log::error('LinkedIn Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong with LinkedIn login. Please try again.');
        }
    }
}   
