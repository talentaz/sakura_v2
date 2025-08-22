<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserReview;

class TestimonialController extends Controller
{
    /**
     * Display the testimonials page
     */
    public function index(Request $request)
    {
        $customer = UserReview::leftJoin('user_review_image', 'user_review.id', '=', 'user_review_image.user_review_id')
                              ->groupBy('user_review.id')
                              ->orderBy('user_review.id', 'desc')
                              ->select('user_review.*', 'user_review_image.image')
                              ->get();
                              
        return view('front.pages.testimonials.index', [
            'customer' => $customer,
        ]);
    }
}
