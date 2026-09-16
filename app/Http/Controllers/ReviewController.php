<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $exists = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return $this->respond(
                $request,
                false,
                'لقد قمت بتقييم هذا المنتج مسبقاً'
            );
        }

        $review = Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        $review->load('user:id,name');

        return $this->respond($request, true, 'تم إضافة تقييمك بنجاح، شكراً لك', [
            'review' => [
                'id'           => $review->id,
                'rating'       => $review->rating,
                'comment'      => $review->comment,
                'user_name'    => $review->user->name,
                'user_initial' => mb_substr($review->user->name, 0, 1, 'UTF-8'),
                'created_at'   => $review->created_at->format('Y-m-d'),
            ],
        ]);
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'تم حذف التقييم');
    }

    protected function respond(Request $request, bool $success, string $message, array $extra = [])
    {
        if ($request->wantsJson()) {
            return response()->json(array_merge([
                'status'  => $success ? 'success' : 'error',
                'message' => $message,
            ], $extra), $success ? 200 : 422);
        }

        return back()->with($success ? 'success' : 'error', $message);
    }
}