<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use PDF;
use Auth;
use App\Models\Inquiry;
use App\Models\Comments;
use App\Models\User;
use App\Models\Vehicle;
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
        // Validate the request
        $request->validate([
            'inqu_name' => 'required|string|max:255',
            'inqu_email' => 'required|email|max:255',
            'inqu_mobile' => 'required|string|max:255',
            'inqu_country' => 'required|string|max:255',
            'vehicle_name' => 'required|string',
            'total_price' => 'nullable|string',
        ]);

        // Clean price values - remove $ and commas
        $fobPrice = $this->cleanPrice($request->get('fob_price'));
        $totalPrice = $this->cleanPrice($request->get('total_price'));
        $inspection = $this->cleanPrice($request->get('inspection'));
        $insurance = $this->cleanPrice($request->get('insurance'));

        $emails = ['rajika@sakuramotors.com', 'nalaka@sakuramotors.com'];

        // Send email to admin
        // Mail::send('mail', array(
        //     'is_contact'  =>'off',
        //     'vehicle_name' => $request->get('vehicle_name'),
        //     'fob_price' => $request->get('fob_price'),
        //     'inspection' => $request->get('inspection'),
        //     'insurance' => $request->get('insurance'),
        //     'inqu_port' => $request->get('inqu_port'),
        //     'total_price' => $request->get('total_price') ?: 'ASK',
        //     'site_url' => $request->get('site_url'),
        //     'inqu_name' => $request->get('inqu_name'),
        //     'inqu_country' => $request->get('inqu_country'),
        //     'inqu_email' => $request->get('inqu_email'),
        //     'inqu_address' => $request->get('inqu_address'),
        //     'inqu_mobile' => $request->get('inqu_mobile'),
        //     'inqu_city' => $request->get('inqu_city'),
        //     'inqu_comment' => $request->get('inqu_comment'),
        // ), function($message) use ($request, $emails){
        //     foreach($emails as $email){
        //         $message->to($email)
        //                 ->replyTo($request->get('inqu_email'))
        //                 ->from('inquiry@sakuramotors.com', $request->get('inqu_name'))
        //                 ->subject('Inquiry - Sakura');
        //     }
        // });

        // Create inquiry record
        $inquery = new Inquiry;
        $inquery->vehicle_name = $request->get('vehicle_name');
        $inquery->fob_price = $fobPrice;
        $inquery->inspection = $inspection;
        $inquery->insurance = $insurance;
        $inquery->inqu_port = $request->get('inqu_port');
        $inquery->total_price = $totalPrice === 'ASK' ? null : $totalPrice;
        $inquery->site_url = $request->get('site_url');
        $inquery->inqu_name = $request->get('inqu_name');
        $inquery->inqu_country = $request->get('inqu_country');
        $inquery->inqu_email = $request->get('inqu_email');
        $inquery->inqu_address = $request->get('inqu_address');
        $inquery->inqu_mobile = $request->get('inqu_mobile');
        $inquery->inqu_city = $request->get('inqu_city');
        $inquery->inqu_comment = $request->get('inqu_comment');
        $inquery->stock_no = $request->get('stock_no');
        $inquery->vehicle_id = $request->get('vehicle_id');

        // Add customer_id if customer is logged in
        if (Auth::guard('customer')->check()) {
            $inquery->customer_id = Auth::guard('customer')->id();
        }

        // if($request->user_id){
        //     $inquery->user_id =  $request->get('user_id');
        //     $order_status = config('config.order_status');
        //     $comments = new Comments;
        //     $comments->user_id = $request->user_id;
        //     $comments->stock_id = $request->stock_no;
        //     $comments->vehicle_id = $request->vehicle_id;
        //     $comments->order_status = $order_status[0];
        //     $comments->comments = $request->get('inqu_comment');
        //     $comments->site_url =  $request->get('site_url');
        //     $comments->save();
        //     //insert order status
        //     $order_status = new OrderStatus;
        //     $order_status->user_id = $request->user_id;
        //     $order_status->vehicle_id = $request->vehicle_id;
        //     $order_status->status = 1;
        //     $order_status->save();

        //     User::where('id', $request->user_id)->update(['comment_status' => 1]);
        //     //send notification
        //     $admins = User::where('role', 1)->get();
        //     Notification::send($admins, new NewUserNotification($comments));
        // }

        // $inquery->save();

        // Generate PDF and send email to customer
        try {
            // Load inquiry with vehicle relationship for PDF
            $inquiry = Inquiry::with(['vehicle'])->find($inquery->id);

            // Generate PDF
            $pdf = PDF::loadView('admin.pages.inquiry.pdf', compact('inquiry'));
            $pdfContent = $pdf->output();
            // dd($pdfContent);
            // Send email to customer with PDF attachment
            // Mail::send('mail', array(
            //     'is_contact' => 'customer_inquiry',
            //     'customer_name' => $request->get('inqu_name'),
            //     'vehicle_name' => $request->get('vehicle_name'),
            //     'stock_no' => $request->get('stock_no'),
            //     'total_price' => $request->get('total_price') ?: 'ASK',
            //     'inquiry_id' => $inquiry->id,
            // ), function($message) use ($request, $pdfContent, $inquiry){
            //     $message->to($request->get('inqu_email'), $request->get('inqu_name'))
            //             ->from('inquiry@sakuramotors.com', 'Sakura Motors')
            //             ->subject('Your Vehicle Inquiry Quotation - Sakura Motors')
            //             ->attachData($pdfContent, 'quotation-SM-' . $inquiry->id . '.pdf', [
            //                 'mime' => 'application/pdf',
            //             ]);
            // });

        } catch (\Exception $e) {
            // Log the error but don't fail the inquiry submission
            \Log::error('PDF generation or email sending failed: ' . $e->getMessage());
        }

   
        return response()->json([
            'success' => true,
            'message' => '123 asd Thank you for your inquiry! We have sent you a quotation via email.',
            'redirect_url' => route('front.customer.login')
        ]);

        // Comment out the back() response temporarily
        // return back()->with('success', 'Thank you for your inquiry! We have sent you a quotation via email.');
    }
    public function company(Request $request){

        return view('front.pages.about.company', [

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

    /**
     * Clean price string by removing $ and commas
     */
    private function cleanPrice($price)
    {
        if (!$price || $price === 'ASK') {
            return null;
        }
        
        // Remove $, commas, and any other non-numeric characters except decimal point
        $cleaned = preg_replace('/[^\d.]/', '', $price);
        
        return is_numeric($cleaned) ? (float)$cleaned : null;
    }
}



