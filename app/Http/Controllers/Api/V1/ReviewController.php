<?php
// app/Http/Controllers/Api/V1/ReviewController.php
namespace App\Http\Controllers\Api\V1;

use App\Enums\ReviewStatus;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return response()->json(
            Review::approved()->latest('approved_at')->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:150'],
            'body' => ['required', 'string'],
            'order_id' => ['nullable', 'exists:orders,id'],
        ]);

        $data['user_id'] = auth('sanctum')->id();
        $data['status'] = ReviewStatus::PENDING->value;
        $data['source'] = 'site';

        $review = Review::create($data);

        return response()->json($review, 201);
    }
}
