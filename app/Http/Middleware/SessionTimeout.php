<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Session timeout in seconds (15 minutes).
     */
    protected int $timeoutSeconds = 900;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow guests and login routes
        if (!Auth::check() || $request->routeIs('login')) {
            return $next($request);
        }

        // Handle AJAX ping requests
        if ($request->is('session/ping')) {
            session(['last_activity_time' => time()]);

            return response()->json([
                'status' => 'ok',
            ]);
        }

        $lastActivity = session('last_activity_time');

        if ($lastActivity instanceof \Carbon\Carbon) {
            $lastActivity = $lastActivity->timestamp;
        }

        if ($lastActivity && (time() - $lastActivity) >= $this->timeoutSeconds) {

            $this->logoutUser($request);

            if ($request->expectsJson()) {
                return response()->json([
                    'timeout' => true,
                    'message' => 'Session expired due to inactivity.',
                ], 401);
            }

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Your session has expired due to inactivity. Please login again.',
                ]);
        }

        // Update activity timestamp
        session([
            'last_activity_time' => time(),
        ]);

        return $next($request);
    }

    /**
     * Logout user and destroy session.
     */
    protected function logoutUser(Request $request): void
    {
        $user = Auth::user();

        if ($user) {

            $updates = [];

            if (isset($user->is_loged)) {
                $updates['is_loged'] = 0;
            }

            if (isset($user->last_login_at)) {
                $updates['last_login_at'] = now();
            }

            if (!empty($updates)) {
                $user->update($updates);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}