<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReviewStatus;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::query()
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'status' => ReviewStatus::APPROVED->value,
            'approved_at' => now(),
        ]);

        return back()->with('status', 'Avis approuvé.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => ReviewStatus::REJECTED->value]);

        return back()->with('status', 'Avis rejeté.');
    }
}