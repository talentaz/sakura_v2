<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB, Validator, Exception, Image, URL, ZipArchive, File;
use Illuminate\Support\Str;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\Rate;
use App\Models\UserReview;
use App\Models\News;
use App\Models\UserReviewImage;
use Location;


class FrontController extends Controller
{
    public function index(Request $request)
    {
        // $ip = $request->ip();
        // $data = \Location::get('80.237.47.16');
        $rate = Rate::first()->rate;
        $vehicle_data = DB::select('SELECT a.*,
                                        b.image_length,
                                        b.*
                                    FROM 	 vehicle	a,
                                    (
                                        SELECT vehicle_id,
                                            COUNT(*)	AS image_length,
                                            MIN(image)	AS image
                                        FROM vehicle_image
                                        GROUP BY vehicle_id
                                        ) b
                                    WHERE a.id = b.vehicle_id
                                    AND a.deleted_at IS NULL
                                    AND a.status IN (NULL, "", "Inquiry", "Invoice Issued")
                                    ORDER BY a.id DESC
                                    LIMIT 5');
                                    
        $best_vehicle_data = DB::select('SELECT a.*,
                                            b.image_length,
                                            b.*
                                        FROM 	 vehicle	a,
                                        (
                                            SELECT vehicle_id,
                                                COUNT(*)	AS image_length,
                                                MIN(image)	AS image
                                            FROM vehicle_image
                                            GROUP BY vehicle_id
                                            ) b
                                        WHERE a.id = b.vehicle_id
                                        AND a.deleted_at IS NULL
                                        AND a.sale_price IS NOT NULL
                                        AND a.status IN (NULL, "", "Inquiry", "Invoice Issued")
                                        ORDER BY a.id DESC
                                        LIMIT 10');
                                        // dd($best_vehicle_data);
        $vehicle_type = DB::table('vehicle_types as a')
                    ->leftJoin(DB::raw('(SELECT body_type, COUNT(*) cnt FROM vehicle WHERE deleted_at IS NULL GROUP BY body_type) as b'), 'a.vehicle_type', '=', 'b.body_type')
                    ->select('a.*', DB::raw('IFNULL(b.cnt, 0) AS cnt'))
                    ->orderBy('a.order_id')
                    ->get();
        // $make_type = DB::select('SELECT  a.*,
        //                     IFNULL(b.cnt, 0) AS cnt
        //                 FROM maker_types a
        //                 LEFT OUTER JOIN 
        //                 (
        //                     SELECT make_type,
        //                         COUNT(*) 	cnt
        //                     FROM vehicle
        //                     GROUP BY make_type
        //                 ) b ON a.maker_type = b.make_type
        //                     ORDER BY a.order_id'
        //                 );
        $make_type = DB::table('maker_types as a')
                            ->select('a.*', DB::raw('IFNULL(b.cnt, 0) as cnt'))
                            ->leftJoin(DB::raw('(SELECT make_type, COUNT(*) as cnt FROM vehicle WHERE deleted_at IS NULL GROUP BY make_type) b'), 'a.maker_type', '=', 'b.make_type')
                            ->orderBy('a.order_id')
                            ->whereNull('a.deleted_at')
                            ->get();
                            
        $vehicle_type = DB::table('vehicle_types as a')
                            ->select('a.*', DB::raw('IFNULL(b.cnt, 0) as cnt'))
                            ->leftJoin(
                                DB::raw('(
                                    SELECT body_type, COUNT(*) as cnt 
                                    FROM vehicle 
                                    WHERE deleted_at IS NULL 
                                    GROUP BY body_type
                                ) b'),
                                'a.vehicle_type',
                                '=',
                                'b.body_type'
                            )
                            ->whereNull('a.deleted_at')
                            ->orderBy('a.order_id')
                            ->get();

        //Customer voice
        $customer = UserReview::leftJoin('user_review_image', 'user_review.id', '=', 'user_review_image.user_review_id')
                              ->groupBy('user_review.id')
                              ->orderBy('user_review.id', 'desc')
                              ->select('user_review.*', 'user_review_image.image')
                              ->limit(3)  
                              ->get();    
        $news = News::leftJoin('news_image', 'news_image.news_id', '=', 'news.id')
                    ->groupBy('news.id')
                    ->orderBy('news.id', 'desc')
                    ->select('news.*', 'news_image.image')
                    ->limit(3)  
                    ->get(); 
        //config variable
        $models = config('config.model_catgory');
        $body_type= config('config.body_type');
        $fuel_type= config('config.fuel_type');
        $drive_type= config('config.drive_type');
        $transmission= config('config.transmission');
        $steering= config('config.steering');
        $doors= config('config.doors');
        $year = [];
        $price =[];
        for ($i=date('Y'); $i >= 1950 ; $i--) { 
            array_push($year, $i);
        }
        for ($i=1000; $i <= 14000; $i+= 2000) { 
            array_push($price, $i);
        }
                       
        // dd($customer);
        return view('front.pages.home.index', [
            'vehicle_data' => $vehicle_data,
            'best_vehicle_data' => $best_vehicle_data,
            'rate' => $rate,
            //config variable
            'models' => $models,
            'body_type' => $body_type,
            'fuel_type' => $fuel_type,
            'drive_type' => $drive_type,
            'transmission' => $transmission,
            'steering' => $steering,
            'doors' => $doors,
            'year' => $year,
            'price' => $price,
            //body type
            'vehicle_type' => $vehicle_type,

             //make type
             'make_type' => $make_type,

             //customer voice
             'customer' => $customer,
             'news' => $news,
             
        ]);
    }
    public function clear(Request $request)
    {
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        return "cleared cache";
    }
    public function light_gallery(Request $request){
        
        $data = VehicleImage::select('image')->where('vehicle_id', $request->id)->get();
        return response()->json(['result' => true, 'data' => $data]);
    }
}
