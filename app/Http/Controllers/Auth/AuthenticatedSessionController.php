<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Notifications\LoginNotification;
class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

   public function store(LoginRequest $request): RedirectResponse
{
    $email = $request->input('email');
    $ip    = $request->ip();
    $start = microtime(true);

    try {
    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();

    // ✅ 1. سجّل في audit log
    Log::channel('audit')->info('User logged in successfully', [
        'event'    => 'login.success',
        'user_id'  => $user->id,
        'email'    => $email,
        'ip'       => $ip,
        'user_agent' => $request->userAgent(),
        'execution_time_ms' => round((microtime(true) - $start) * 1000, 2),
    ]);

    // ✅ 2. أرسل إشعار (DB + Email)
    $user->notify(new LoginNotification($ip, $request->userAgent() ?? 'Unknown'));

    return redirect()->intended(route('dashboard', absolute: false));

    } catch (\Illuminate\Validation\ValidationException $e) {
        // ✅ WARNING عند الفشل
        Log::channel('audit')->warning('Failed login attempt', [
            'event'    => 'login.failed',
            'email'    => $email,
            'ip'       => $ip,
            'user_agent' => $request->userAgent(),
        ]);
        throw $e;

    } catch (\Throwable $e) {
        // ✅ ERROR عند خطأ غير متوقع
        Log::channel('audit')->error('Login error occurred', [
            'event'   => 'login.error',
            'message' => $e->getMessage(),
            'email'   => $email,
            'ip'      => $ip,
            'user_id' => Auth::id(),
            'trace'   => $e->getTraceAsString(),
        ]);

        return back()->withErrors([
            'email' => 'حدث خطأ غير متوقع، الرجاء المحاولة لاحقًا.',
        ]);
    }
}

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}