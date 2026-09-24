<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'token' => ['required'],
                'email' => [
                    'required',
                    'email:rfc,strict',
                    'regex:/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9][a-zA-Z0-9.-]*\.[a-zA-Z]{2,}$/',
                    'max:255',
                ],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'token.required'      => 'رمز إعادة التعيين مطلوب.',
                'email.required'      => 'حقل البريد الإلكتروني مطلوب.',
                'email.email'         => 'يجب إدخال بريد إلكتروني صحيح.',
                'email.regex'         => 'يجب إدخال بريد إلكتروني بصيغة صحيحة (مثل example@gmail.com).',
                'email.max'           => 'البريد الإلكتروني طويل جداً.',
                'password.required'   => 'حقل كلمة المرور مطلوب.',
                'password.confirmed'  => 'تأكيد كلمة المرور لا يطابق.',
                'password.min'        => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}