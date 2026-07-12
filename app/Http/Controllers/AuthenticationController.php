<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use App\Models\User;
use App\Services\UserDataService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            
            Alert::success( 'Hello!' . ' ' .  Auth()->user()->name, 'Welcome to IFL SOLIDARITY GROUP Information Hub');
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
        $user = Auth::user();

        if (in_array($user->Role, [
            User::ROLE_MEMBER,
            User::ROLE_USER
        ])) {

            return redirect()->route('memberdashboard');

        }

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


    public function showregisterForm()
    {
        $roles = User::getRoles();
        try {
            return view('auth.register', compact('roles'));
        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }
    public function storeregisterdata(Request $request)
        {
            // Start transaction since you are using DB::rollBack() in catch block
            DB::beginTransaction(); 

            try {
                $validated = $request->validate([
                    'FirstName'  => ['required', 'string', 'max:50', 'regex:/^[A-Za-z]+$/'],
                    'MiddleName' => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-z]+$/'],
                    'LastName'   => ['required', 'string', 'max:50', 'regex:/^[A-Za-z]+$/'],
                    'email'      => ['required', 'email', 'max:100', 'unique:users,email'],
                    'phone'      => ['required', 'regex:/^(0|\+255)[67][0-9]{8}$/', 'unique:users,phone'],
                    'password'   => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                ], [
                    'FirstName.regex'    => 'First Name must contain letters only.',
                    'MiddleName.regex'   => 'Middle Name must contain letters only.',
                    'LastName.regex'     => 'Last Name must contain letters only.',
                    'phone.regex'        => 'Phone number must be in a valid Tanzanian format (e.g. 0712345678 or +255712345678).',
                    'email.unique'       => 'This email address already exists.',
                    'phone.unique'       => 'This phone number is already registered.',
                    'password.confirmed' => 'Password confirmation does not match.',
                ]);

                // Generate Username
                $userCount     = User::count() + 1;
                $year          = date('Y');
                $firstInitial  = strtoupper(substr($validated['FirstName'], 0, 1));
                $middleInitial = !empty($validated['MiddleName']) ? strtoupper(substr($validated['MiddleName'], 0, 1)) : '';
                $lastInitial   = strtoupper(substr($validated['LastName'], 0, 1));
                $initials      = $firstInitial . $middleInitial . $lastInitial;
                $username      = 'IFLSG/' . $initials . '/' . $year . '/' . str_pad($userCount, 4, '0', STR_PAD_LEFT);

                // Full Name formatting
                $validated['name']     = trim($validated['LastName'] . ', ' . $validated['FirstName'] . ' ' . ($validated['MiddleName'] ?? ''));
                $validated['username'] = $username;
                $validated['Role']     = 'User';
                $validated['password'] = Hash::make($validated['password']);
                $validated['User_id']  = Auth::id();

                // Store the created user model instance to reference it safely below
                $user = User::create($validated);

                DB::commit();

                // Success Pop-Up Alert
                Alert::success(
                    'Welcome, ' . $user->FirstName . '!',
                    'Your account has been created successfully. You can now log in using your email and password.'
                );

                return redirect()->route('login');

        } catch (\Illuminate\Database\QueryException $th) {

            DB::rollBack();

            \Log::error('Registration failed (DB)', [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ]);

            // MySQL error code 1062 = duplicate entry
            if ($th->errorInfo[1] == 1062) {
                Alert::error(
                    'Duplicate entry',
                    'An account with this email or phone number already exists.'
                );
            } else {
                Alert::error(
                    'Sorry!',
                    'A technical error occurred while creating your account. Please contact support: +255 657 856 790.'
                );
            }

            return back()->withInput();

        } catch (\Throwable $th) {

            DB::rollBack();

            \Log::error('Registration failed', [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ]);

            Alert::error(
                'Sorry!',
                'A technical error occurred while creating your account. Please contact support: +255 657 856 790.'
            );

            return back()->withInput();
        }
    }
}
