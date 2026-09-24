<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'email' => [
                    'required',
                    'email:rfc,strict',
                    'regex:/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@[a-zA-Z0-9][a-zA-Z0-9.-]*\.[a-zA-Z]{2,}$/',
                    'max:255',
                ],
            ],
            [
                'email.required' => 'حقل البريد الإلكتروني مطلوب.',
                'email.email'    => 'يجب إدخال بريد إلكتروني صحيح.',
                'email.regex'    => 'يجب إدخال بريد إلكتروني بصيغة صحيحة (مثل example@gmail.com).',
                'email.max'      => 'البريد الإلكتروني طويل جداً.',
            ]
        );

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}