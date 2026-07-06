<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Services\UserDataService;
use RealRashid\SweetAlert\Facades\Alert;

class AuthenticationController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function loginPrev(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return view('auth.login');
        }
        return back()->withErrors([
            'login' => 'Invalid credentials. Please try again.',
        ])->onlyInput('login');
    }

    public function login(Request $request)
    {
        // try {
            $request->validate([
                'login'    => 'required|string',
                'password' => 'required|string',
            ]);

            $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::where($loginType, $request->login)->first();

            // 🔴 User not found
            if (!$user) {
                return back()->withErrors(['login' => 'Invalid credentials.'])->onlyInput('login');
            }

            // 🔴 Account locked
            if ($user->locked_until && Carbon::parse($user->locked_until)->isFuture()) {
                $remaining = Carbon::parse($user->locked_until)->diffInMinutes(now());
                return back()->withErrors([
                    'login' => "Account is locked. Try again in {$remaining} minute(s).",
                ]);
            }

            // 🔴 Wrong password
            if (!Hash::check($request->password, $user->password)) {
                $user->increment('failed_login_attempts');

                if ($user->failed_login_attempts >= 5) {
                    $user->update(['locked_until' => now()->addMinutes(15)]);
                    return back()->withErrors([
                        'login' => 'Account locked due to too many failed attempts. Try again after 15 minutes.',
                    ]);
                }

                return back()->withErrors(['login' => 'Invalid credentials.'])->onlyInput('login');
            }

            // ✅ Reset failed attempts
            $user->update([
                'failed_login_attempts' => 0,
                'locked_until'          => null,
            ]);

            // 🔴 Inactive account
            if ($user->Status !== 'Active') {
                return back()->withErrors([
                    'login' => 'Your account is inactive. Contact admin.',
                ]);
            }

            // 🔴 Default password — force change
            if (Hash::check('123456', $user->password)) {
                Auth::login($user);
                session(['last_activity_time' => now()]);

                return redirect()->route('password.change')->with(
                    'warning', 'Please change your default password.'
                );
            }

            // 🔴 Multiple login check — skip for Admin role
            $isAdmin = $user->Role === 'Admin'; // adjust to your role field/relation

            // if ($user->is_loged && !$isAdmin) {
            //     return back()->withErrors([
            //         'login' => 'This account is already logged in from another session.',
            //     ]);
            // }

            // ✅ Login
            Auth::login($user, $request->filled('remember'));

            $user->update([
                'is_loged'      => 1,
                'last_login_at' => now(),
            ]);

            $request->session()->regenerate();

            session([
                'last_activity_time' => now(),
            ]);

            UserDataService::load(
                Auth::user()->fresh([
                    'permissionUsers.permission'
                ])
            );
            
            Alert::success( 'Wow!' . ' ' .  Auth()->user()->name, 'Welcome to ArBif Information Hub');
            return redirect()->intended('home');

        // } catch (\Throwable $th) {
        //     \Log::error('Login error: ' . $th->getMessage());
        //     return back()->withErrors(['login' => 'Something went wrong. Please try again.']);
        // }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->update([
                'is_loged'      => 0,
                'last_login_at' => now(),
            ]);
        }

        // 🔹 Clear user data from session
        UserDataService::clear();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out.');
    }

    public function settings(Request $request)
    {
        $module = $request->module;

        switch ($module) {

            case 'configuration':
                return redirect()->route('configurationside');

            case 'working':
                return redirect()->route('workingside');

            case 'reports':
                return redirect()->route('reportingside');

            default:
                return back()->withErrors(['error' => 'Invalid selection']);
        }
    }
    public function home()
    {
        return view('layouts.home');
    }

    public function deleteActvSession(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->deleteActiveSession($user);
        }
    }
    
    protected function deleteActiveSession($user)
    {
        if ($user) {
            $db = $user->getConnectionName();
            ActiveSession::on($db)->where('user_id', $user->id)->delete();
        }
    }
}
