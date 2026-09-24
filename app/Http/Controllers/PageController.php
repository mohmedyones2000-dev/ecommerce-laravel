<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Faq;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::active()->where('slug', $slug)->firstOrFail();

        return view('pages.show', compact('page'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
    }

    public function faq()
    {
        $faqs = Cache::remember('faqs_active', now()->addHours(6), function () {
            return Faq::active()
                ->orderBy('sort_order')
                ->get()
                ->map(fn ($faq) => [
                    'id'       => $faq->id,
                    'question' => $faq->question,
                    'answer'   => $faq->answer,
                ])
                ->values()
                ->toArray();
        });

        return view('pages.faq', compact('faqs'));
    }
}