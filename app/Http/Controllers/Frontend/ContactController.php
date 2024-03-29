<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Models\Inquiry;
use App\Models\Comments;
use App\Models\User;
use App\Models\OrderStatus;
use Notification;
use App\Notifications\NewUserNotification;

class ContactController extends Controller
{
    public function index(Request $request){
        
        $country= config('config.country');
        return view('front.pages.contact.index', [
            'country' => $country,
        ]);
    }
    public function contactEmail(Request $request){
        $emails = ['rajika@sakuramotors.com', 'nalaka@sakuramotors.com'];
        // dd($request->email);
        Mail::send('mail', array(
        'is_contact'  =>'on',
            'subject' => $request->get('subject'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'country' => $request->get('country'),
            'city' => $request->get('city'),
            'comment' => $request->get('comment'),
        ), function($message) use ($request, $emails){
            foreach($emails as $email){
                $message->to($email)
                        ->replyTo($request->get('email'))
                        ->from('inquiry@sakuramotors.com', $request->get('name'))
                        ->subject($request->subject);
            }
        });      

        return back()->with('success', 'Thanks for contacting!');
    }
    public function inquiryEmail(Request $request){
        $emails = ['rajika@sakuramotors.com', 'nalaka@sakuramotors.com'];
        Mail::send('mail', array(
            'is_contact'  =>'off',
            'vehicle_name' => $request->get('vehicle_name'),
            'fob_price' => $request->get('fob_price'),
            'inspection' => $request->get('inspection'),
            'insurance' => $request->get('insurance'),
            'inqu_port' => $request->get('inqu_port'),
            'total_price' => $request->get('total_price'),
            'site_url' => $request->get('site_url'),
            'inqu_name' => $request->get('inqu_name'),
            'inqu_country' => $request->get('inqu_country'),
            'inqu_email' => $request->get('inqu_email'),
            'inqu_address' => $request->get('inqu_address'),
            'inqu_mobile' => $request->get('inqu_mobile'),
            'inqu_city' => $request->get('inqu_city'),
            'inqu_comment' => $request->get('inqu_comment'),
        ), function($message) use ($request, $emails){
            foreach($emails as $email){
                $message->to($email)
                        ->replyTo($request->get('inqu_email'))
                        ->from('inquiry@sakuramotors.com', $request->get('inqu_name'))
                        ->subject('Inquiry - Sakura');
            }
        });      

        $inquery = new Inquiry;
        $inquery->vehicle_name =  $request->get('vehicle_name');
        $inquery->fob_price =  $request->get('fob_price');
        $inquery->inspection =  $request->get('inspection');
        $inquery->insurance =  $request->get('insurance');
        $inquery->inqu_port =  $request->get('inqu_port');
        $inquery->total_price =  $request->get('total_price');
        $inquery->site_url =  $request->get('site_url');
        $inquery->inqu_name =  $request->get('inqu_name');
        $inquery->inqu_country =  $request->get('inqu_country');
        $inquery->inqu_email =  $request->get('inqu_email');
        $inquery->inqu_address =  $request->get('inqu_address');
        $inquery->inqu_mobile =  $request->get('inqu_mobile');
        $inquery->inqu_city =  $request->get('inqu_city');
        $inquery->inqu_comment =  $request->get('inqu_comment');
        $inquery->stock_no =  $request->get('stock_no');
        $inquery->vehicle_id =  $request->get('vehicle_id');
        if($request->user_id){
            $inquery->user_id =  $request->get('user_id');
            $order_status = config('config.order_status');
            $comments = new Comments;
            $comments->user_id = $request->user_id;
            $comments->stock_id = $request->stock_no;
            $comments->vehicle_id = $request->vehicle_id;
            $comments->order_status = $order_status[0];
            $comments->comments = $request->get('inqu_comment');
            $comments->site_url =  $request->get('site_url');
            $comments->save();
            //insert order status
            $order_status = new OrderStatus;
            $order_status->user_id = $request->user_id;
            $order_status->vehicle_id = $request->vehicle_id;
            $order_status->status = 1;
            $order_status->save();

            User::where('id', $request->user_id)->update(['comment_status' => 1]);
            //send notification
            $admins = User::where('role', 1)->get();
            Notification::send($admins, new NewUserNotification($comments));
        }
        $inquery->save();
        return back()->with('success', 'Thanks for contacting!');
    }
    public function company(Request $request){
        
        return view('front.pages.company.index', [

        ]);
    }
    public function agents(Request $request){
        
        return view('front.pages.agents.index', [

        ]);
    }

    public function tanzania(Request $request){
        
        return view('front.pages.agents.tanzania', [

        ]);
    }
    public function gallery(Request $request){
        
        return view('front.pages.gallery.index', [

        ]);
    }
    public function payment(Request $request){
        
        return view('front.pages.payment.index', [

        ]);
    }
}
