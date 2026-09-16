<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower(trim($request->email));

        $existing = NewsletterSubscriber::where('email', $email)->first();

        // مشترك موجود ونشط
        if ($existing && $existing->is_active) {
            return response()->json([
                'success' => false,
                'type'    => 'info',
                'message' => 'هذا البريد مشترك بالفعل في النشرة البريدية.',
            ]);
        }

        // مشترك موجود لكن غير نشط → إعادة التنشيط
        if ($existing) {
            $existing->update([
                'is_active'       => true,
                'subscribed_at'   => now(),
                'unsubscribed_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'type'    => 'success',
                'message' => 'تم إعادة تفعيل اشتراكك بنجاح.',
            ]);
        }

        // مشترك جديد
        NewsletterSubscriber::create([
            'email' => $email,
            'name'  => auth()->user()->name ?? null,
            'token' => Str::random(32),
        ]);

        return response()->json([
            'success' => true,
            'type'    => 'success',
            'message' => 'تم تسجيل اشتراكك بنجاح. شكراً لك.',
        ]);
    }

    public function unsubscribe($token)
    {
        $subscriber = NewsletterSubscriber::where('token', $token)->first();

        if (!$subscriber) {
            abort(404, 'رابط إلغاء الاشتراك غير صحيح');
        }

        if (!$subscriber->is_active) {
            return view('newsletter.unsubscribed', ['already' => true]);
        }

        $subscriber->update([
            'is_active'       => false,
            'unsubscribed_at' => now(),
        ]);

        return view('newsletter.unsubscribed', ['already' => false]);
    }
}