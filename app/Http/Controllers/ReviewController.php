<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CateringReview;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $review = new CateringReview();
        $review->name = $request->name;
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        return redirect(url()->previous() . '#reviews-section')->with('success', 'Thank you for your review!');
    }
}
