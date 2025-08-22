<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\News;
use App\Models\NewsImage;

class NewsController extends Controller
{
    /**
     * Display the news index page with hero layout
     */
    public function index(Request $request)
    {
        $news = News::leftJoin('news_image', 'news_image.news_id', '=', 'news.id')
                    ->groupBy('news.id')
                    ->orderBy('news.created_at', 'desc')
                    ->select('news.*', 'news_image.image')
                    ->get();
                    
        return view('front.pages.news.index', [
            'news' => $news,
        ]);
    }

    /**
     * Display the news detail page
     */
    public function detail(Request $request, $id)
    {
        $news = News::leftJoin('news_image', 'news.id', '=', 'news_image.news_id')
                    ->groupBy('news.id')
                    ->where('news.id', $id)
                    ->select('news.*', 'news_image.image')
                    ->first();
                    
        if (!$news) {
            abort(404, 'News article not found');
        }
                    
        return view('front.pages.news.detail', [
            'news' => $news,
        ]);
    }
}
