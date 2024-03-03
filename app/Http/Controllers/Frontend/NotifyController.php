<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderStatus;
use App\Models\Vehicle;
use App\Models\User;
use Carbon\Carbon;
use Mail, DB;

class NotifyController extends Controller
{
    public function index(Request $request){
        $orderStatuses = OrderStatus::leftJoin('vehicle as v', 'order_statuses.vehicle_id', '=', 'v.id')
                                    ->leftJoin('users as u', 'order_statuses.user_id', '=', 'u.id')
                                    ->select('order_statuses.vehicle_id', 'order_statuses.user_id', 'u.name', 'u.email', 'v.count_time', 'v.notify_status', 'v.updated_at')
                                    ->whereNotNull('v.updated_at')
                                    ->whereNotNull('v.count_time')
                                    ->where('v.notify_status', 0)
                                    ->orderBy('order_statuses.vehicle_id', 'asc')
                                    ->get();
        $subject = "Available to Sell";
        foreach ($orderStatuses as $row){
            // check available time
            $now = Carbon::now();
            $countTime = $row->count_time;
            $updatedAt = Carbon::parse($row->updated_at);
            $diffTime = $now->diffInSeconds($updatedAt); 
            $available_time = $countTime*3600 - $diffTime;
            if ($available_time < 0) {
                $email = $row->email;
                $name = $row->name;
                Mail::send('mail', array(
                    'is_contact' => 'auto',
                    'subject' => $subject,
                    'name' => $name,
                ), function($message) use ($email, $subject){
                    $message->from('info@sakuramotors.com');
                    $message->to($email, $subject)
                            ->subject($subject);
                });
                // update vehicle notify status from 0 to 1
                Vehicle::where('id', $row->vehicle_id)->update(['notify_status' => 1]);
            }
        }     

        return response()->json(['result' => true]);
    }
}
