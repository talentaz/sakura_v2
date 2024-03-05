<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderStatus;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Notify;
use Carbon\Carbon;
use Mail, DB;

class NotifyController extends Controller
{
    public function index(Request $request){
        $notify = Notify::Join('vehicle as v', 'notify.vehicle_id', '=', 'v.id')
                        ->leftJoin('users as u', 'notify.user_id', '=', 'u.id')
                        ->select('notify.id', 'notify.vehicle_id', 'notify.user_id', 'u.name', 'u.email', 'v.count_time', 'v.updated_at')
                        ->whereNotNull('v.updated_at')
                        ->whereNotNull('v.count_time')
                        ->where('notify.notify_status', 0)
                        ->whereNull('v.deleted_at')
                        ->orderBy('notify.vehicle_id', 'asc')
                        ->get();
        // $notify = Notify::leftJoin('users as u', 'notify.user_id', '=', 'u.id')
        //                 ->where("notify.notify_status", 0)
        //                 ->select('notify.id', 'notify.vehicle_id', 'notify.user_id', 'u.name', 'u.email')
        //                 ->get();
        
        $subject = "Available to Sell";
        foreach ($notify as $row){
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
                // update vehicle status from invoice issued to inquery
                Vehicle::where('id', $row->vehicle_id)->update(['status' => Vehicle::INQUIRY]);
                Notify::where('id', $row->id)->update(['notify_status' => 1]);
            }
        }     

        return response()->json(['result' => true]);
    }
    public function create(Request $request){
        $notify = new Notify;
        $notify->user_id = $request->user_id;
        $notify->vehicle_id = $request->vehicle_id;
        $notify->save();
        return response()->json(['result' => true]);
    }
}
