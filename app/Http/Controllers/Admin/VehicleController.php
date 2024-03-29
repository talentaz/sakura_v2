<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Arr;
use DB, Validator, Exception, Image, URL;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\Rate;
use DataTables;
use Carbon\Carbon;


class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $rate = Rate::latest('id')->first()->rate;
        // $data = Vehicle::leftJoin('vehicle_image', 'vehicle.id', '=', 'vehicle_image.vehicle_id')
        //                 ->orderByRaw('CONVERT(vehicle_image.image, SIGNED) asc')
        //                 ->groupBy('vehicle.id')
        //                 ->select('vehicle.*', 'vehicle_image.image')
        //                 ->orderBy('vehicle.created_at', 'desc')
        //                 ->get();
        $order_status = config('config.order_status');
        return view('admin.pages.vehicle.index', [
            // 'data' => $data,
            'rate' => $rate,
            'order_status' => $order_status,
        ]);
    }
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $rate = Rate::latest('id')->first()->rate;
            $data = Vehicle::leftJoin('vehicle_image', 'vehicle.id', '=', 'vehicle_image.vehicle_id')
                        // ->whereNull('vehicle.deleted_at')
                        ->orderBy('vehicle.id', 'DESC')
                        ->orderByRaw('CONVERT(vehicle_image.image, SIGNED) asc')
                        ->groupBy('vehicle.id')
                        ->select('vehicle.id', 'vehicle.chassis', 'vehicle.stock_no', 'vehicle.price', 'vehicle.sale_price', 'vehicle.status', 'vehicle.created_at', 'vehicle_image.image')
                        ->get();
            return Datatables::of($data)
                            ->addColumn('image', function($row){
                                return  asset('/uploads/vehicle').'/'.$row->id.'/thumb'.'/'.$row->image;
                            })
                            ->addColumn('price', function($row){
                                return  $row->price;
                            })
                            ->addColumn('sale_price', function($row){
                                if($row->sale_price) {
                                    return  $row->sale_price;
                                }
                            })
                            ->addColumn('status', function($row){
                                if($row->status) {
                                    return  $row->status;
                                } else {
                                    return '';
                                }
                            })
                            ->addColumn('usd', function($row) use($rate){
                                if($row->sale_price) {
                                    return  round($row->sale_price / $rate);
                                } else {
                                    return  round($row->price / $rate);
                                }
                            })
                            ->addColumn('created_at', function($row){
                                return  date("Y-m-d", strtotime($row->created_at));
                            })
                            ->addColumn('action', function($row){
                                return  '<a href="'.route('admin.vehicle.edit', ['id' => $row->id]).'" class="text-success edit" >
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="text-danger confirm_delete" data-id="'.$row->id.'" data-bs-toggle="modal" data-bs-target="#myModal">
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="text-warning confirm_status" data-id="'.$row->id.'" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                            <i class="bx bx-cog font-size-18"></i>
                                        </a>';
                            })
                            ->make(true);
        }
  
    }
    public function create(Request $request)
    {
        $models = config('config.model_catgory');
        $body_type= config('config.body_type');
        $fuel_type= config('config.fuel_type');
        $drive_type= config('config.drive_type');
        $transmission= config('config.transmission');
        $steering= config('config.steering');
        $doors= config('config.doors');

        return view('admin.pages.vehicle.create', [
            'models' => $models,
            'body_type' => $body_type,
            'fuel_type' => $fuel_type,
            'drive_type' => $drive_type,
            'transmission' => $transmission,
            'steering' => $steering,
            'doors' => $doors,
        ]);
    }
    public function edit(Request $request, $id) {
        $data = Vehicle::findOrFail($id);
        $image_path = VehicleImage::where('vehicle_id', $id)->orderByRaw('CONVERT(image, SIGNED) asc')->get();
        $get_url = URL::asset('uploads/vehicle/'.$id.'/real');
        $get_image_path = [];
        $id_array = [];
        foreach($image_path as $row){
            array_push($get_image_path, $get_url.'/'.$row->image);
            array_push($id_array, ["key"=>($row->id)]);
        }
        $models = config('config.model_catgory');
        $body_type= config('config.body_type');
        $fuel_type= config('config.fuel_type');
        $drive_type= config('config.drive_type');
        $transmission= config('config.transmission');
        $steering= config('config.steering');
        $doors= config('config.doors');
        return view('admin.pages.vehicle.edit', [
            'data' => $data,
            'models' => $models,
            'body_type' => $body_type,
            'fuel_type' => $fuel_type,
            'drive_type' => $drive_type,
            'transmission' => $transmission,
            'steering' => $steering,
            'doors' => $doors,
            'get_image_path' => $get_image_path,
            'id_array' => $id_array,
        ]);
    }
    public function imageDelete(Request $request){
        $id = $request->key;
        $vehicle_id = VehicleImage::where('id', $id)->first()->vehicle_id;
        $fileName = VehicleImage::where('id', $id)->first()->image;
        $real_image = ('uploads/vehicle/'.$vehicle_id.'/real'.'/'.$fileName);
        $thumb_image = ('uploads/vehicle/'.$vehicle_id.'/thumb'.'/'.$fileName);
        if(File::exists(public_path($real_image))){
            File::delete(public_path($real_image));   
        }
        if(File::exists(public_path($thumb_image))){
            File::delete(public_path($thumb_image));   
        }
        $result = VehicleImage::where('id', $id)->delete();
        return response()->json(['result' => true, 'deleted' => $result]);
    }

    public function delete(Request $request){
        $vehicleId = $request->id;
        $vehicle = Vehicle::where('id', $vehicleId)->delete();
        return response()->json(['result' => true]);
    }
    
    public function create_post(Request $request)
    {
        $vehicle = new Vehicle;
        $vehicle->stock_no = $request->stock_no;
        $vehicle->make_type = $request->make_type;
        $vehicle->model_type = $request->model_type;
        $vehicle->body_type = $request->body_type;
        $vehicle->registration = $request->registration;
        $vehicle->fuel_type = $request->fuel_type;
        $vehicle->mileage = $request->mileage;
        $vehicle->engine_model = $request->engine_model;
        $vehicle->engine_size = $request->engine_size;
        $vehicle->seats = $request->seats;
        $vehicle->model_code = $request->model_code;
        $vehicle->exterior_color = $request->exterior_color;
        $vehicle->drive_type = $request->drive_type;
        $vehicle->transmission = $request->transmission;
        $vehicle->steering = $request->steering;
        $vehicle->doors = $request->doors;
        $vehicle->length = $request->length;
        $vehicle->width = $request->width;
        $vehicle->height = $request->height;
        $vehicle->chassis = $request->chassis;
        $vehicle->loading_capacity = $request->loading_capacity;
        $vehicle->ac = $request->has('ac');
        $vehicle->power_steering = $request->has('power_steering');
        $vehicle->auto_door = $request->has('auto_door');
        $vehicle->remote_key = $request->has('remote_key');
        $vehicle->backup_camera = $request->has('backup_camera');
        $vehicle->navigation = $request->has('navigation');
        $vehicle->power_locks = $request->has('power_locks');
        $vehicle->cd_player = $request->has('cd_player');
        $vehicle->dvd = $request->has('dvd');
        $vehicle->mp3_interface = $request->has('mp3_interface');
        $vehicle->ratio = $request->has('ratio');
        $vehicle->sun_roof = $request->has('sun_roof');
        $vehicle->air_bag = $request->has('air_bag');
        $vehicle->abs = $request->has('abs');
        $vehicle->s_power_locks = $request->has('s_power_locks');
        $vehicle->parking_sensors = $request->has('parking_sensors');
        $vehicle->grill_guard = $request->has('grill_guard');
        $vehicle->back_camera = $request->has('back_camera');
        $vehicle->leather_seat = $request->has('leather_seat');
        $vehicle->power_seat = $request->has('power_seat');
        $vehicle->power_mirrors = $request->has('power_mirrors');
        $vehicle->power_window = $request->has('power_window');
        $vehicle->rear_spoiler = $request->has('rear_spoiler');
        $vehicle->alloy_wheels = $request->has('alloy_wheels');
        $vehicle->bluetooth = $request->has('bluetooth');
        $vehicle->price = $request->price;
        $vehicle->sale_price = $request->sale_price;
        $vehicle->video_link = $request->video_link;
        $vehicle->save();

        //image upload section
        if ($request->has('file')) { 
            $vehicle_id = Vehicle::latest('id')->first()->id;
            $path = public_path('uploads/vehicle/'.$vehicle_id.'/');
            if(!file_exists($path)){
                File::makeDirectory($path);
            }
            $thumb_path = $path.'thumb/';
            $real_path = $path.'real/';
            if (!file_exists($thumb_path)) {
                File::makeDirectory($thumb_path); //create thumb image folder
            }
            if(!file_exists($real_path)) {
                File::makeDirectory($real_path); //create thumb image folder
            }
            foreach($request->file as $row){
                $vehicle_image = new VehicleImage;
                $vehicle_id = $vehicle->id;
                $fileName = $row->getClientOriginalName();
                $imgx = Image::make($row->getRealPath());
                // get real image
                $imgx->resize(640, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->crop(640, 480)
                ->save($real_path.$fileName);
                //get thumbnail image
                $imgx->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumb_path.$fileName);
                
                $vehicle_image->image = $fileName;
                $vehicle_image->vehicle_id = $vehicle_id;
                $vehicle_image->save(); 
            }
        }
        return response()->json(['result' => true, 'Add new vehicle' => $vehicle]);
    }
    public function edit_post(Request $request, $id) {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->stock_no = $request->stock_no;
        $vehicle->make_type = $request->make_type;
        $vehicle->model_type = $request->model_type;
        $vehicle->body_type = $request->body_type;
        $vehicle->registration = $request->registration;
        $vehicle->fuel_type = $request->fuel_type;
        $vehicle->mileage = $request->mileage;
        $vehicle->engine_model = $request->engine_model;
        $vehicle->engine_size = $request->engine_size;
        $vehicle->seats = $request->seats;
        $vehicle->model_code = $request->model_code;
        $vehicle->exterior_color = $request->exterior_color;
        $vehicle->drive_type = $request->drive_type;
        $vehicle->transmission = $request->transmission;
        $vehicle->steering = $request->steering;
        $vehicle->doors = $request->doors;
        $vehicle->length = $request->length;
        $vehicle->width = $request->width;
        $vehicle->height = $request->height;
        $vehicle->chassis = $request->chassis;
        $vehicle->loading_capacity = $request->loading_capacity;
        $vehicle->ac = $request->has('ac');
        $vehicle->power_steering = $request->has('power_steering');
        $vehicle->auto_door = $request->has('auto_door');
        $vehicle->remote_key = $request->has('remote_key');
        $vehicle->backup_camera = $request->has('backup_camera');
        $vehicle->navigation = $request->has('navigation');
        $vehicle->power_locks = $request->has('power_locks');
        $vehicle->cd_player = $request->has('cd_player');
        $vehicle->dvd = $request->has('dvd');
        $vehicle->mp3_interface = $request->has('mp3_interface');
        $vehicle->ratio = $request->has('ratio');
        $vehicle->sun_roof = $request->has('sun_roof');
        $vehicle->air_bag = $request->has('air_bag');
        $vehicle->abs = $request->has('abs');
        $vehicle->s_power_locks = $request->has('s_power_locks');
        $vehicle->parking_sensors = $request->has('parking_sensors');
        $vehicle->grill_guard = $request->has('grill_guard');
        $vehicle->back_camera = $request->has('back_camera');
        $vehicle->leather_seat = $request->has('leather_seat');
        $vehicle->power_seat = $request->has('power_seat');
        $vehicle->power_mirrors = $request->has('power_mirrors');
        $vehicle->power_window = $request->has('power_window');
        $vehicle->rear_spoiler = $request->has('rear_spoiler');
        $vehicle->alloy_wheels = $request->has('alloy_wheels');
        $vehicle->bluetooth = $request->has('bluetooth');
        $vehicle->price = $request->price;
        $vehicle->sale_price = $request->sale_price;
        $vehicle->video_link = $request->video_link;
        $vehicle->save();
        
        //image upload section
        // if ($request->has('file')) { 
        //     dd($request->all());
        //     $path = public_path('uploads/vehicle/'.$request->stock_no.'/');
        //     if(!file_exists($path)){
        //         File::makeDirectory($path);
        //     }
        //     $thumb_path = $path.'thumb/';
        //     $real_path = $path.'real/';
        //     if (!file_exists($thumb_path)) {
        //         File::makeDirectory($thumb_path); //create thumb image folder
        //     }
        //     if(!file_exists($real_path)) {
        //         File::makeDirectory($real_path); //create thumb image folder
        //     }
        //     foreach($request->file as $row){
        //         $vehicle_image = new VehicleImage;
        //         $fileName = $row->getClientOriginalName();
        //         $imgx = Image::make($row->getRealPath());
        //         // get real image
        //         $imgx->resize(640, null, function ($constraint) {
        //             $constraint->aspectRatio();
        //             $constraint->upsize();
        //         })->crop(640, 480)
        //         ->save($real_path.$fileName);
        //         //get thumbnail image
        //         $imgx->resize(300, null, function ($constraint) {
        //                 $constraint->aspectRatio();
        //             })->save($thumb_path.$fileName);
                
        //         $vehicle_image->image = $fileName;
        //         $vehicle_image->stock_no = $request->stock_no;
        //         $vehicle_image->save(); 
        //     }
        // }
        return response()->json(['result' => true, 'Add new vehicle' => $vehicle]);
    }
    public function imageAdd(Request $request){
        //image upload section
        if ($request->has('file')) { 
            $path = public_path('uploads/vehicle/'.$request->vehicle_id.'/');
            if(!file_exists($path)){
                File::makeDirectory($path);
            }
            $thumb_path = $path.'thumb/';
            $real_path = $path.'real/';
            if (!file_exists($thumb_path)) {
                File::makeDirectory($thumb_path); //create thumb image folder
            }
            if(!file_exists($real_path)) {
                File::makeDirectory($real_path); //create real image folder
            }
            foreach($request->file as $row){
                $fileName = $row->getClientOriginalName();
                $imgx = Image::make($row->getRealPath());
                // get real image
                $imgx->resize(640, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->crop(640, 480)
                ->save($real_path.$fileName);
                //get thumbnail image
                $imgx->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($thumb_path.$fileName);
                
                $vehicle_image = new VehicleImage;
                $vehicle_image->image = $fileName;
                $vehicle_image->vehicle_id = $request->vehicle_id;
                $vehicle_image->save(); 
            }
        }
        return response()->json(['uploaded' => 'uploads/vehicle/'.$request->vehicle_id.'/'.$fileName]);
    }
    public function rate(Request $request){

        $rate = Rate::latest('id')->first();
        return view('admin.pages.vehicle.rate', [
            'rate' => $rate,
        ]);
    }
    public function rate_post(Request $request){
        $rate = Rate::latest('id')->first();
        $rate->rate = $request->rate;
        $rate->inspection = $request->inspection;
        $rate->insurance = $request->insurance;
        $rate->save();
        return response()->json(['result' => true, 'Updated rate' => $rate]);
    }
    public function get_status(Request $request){
        $status = Vehicle::select('status', 'count_time')->where('id', $request->id)->first();
        return response()->json(['result' => $status]);
    }
    public function change_status(Request $request){
        Vehicle::where('id', $request->id)
                ->update([
                    'status' => $request->status,
                    'count_time' => $request->count_time,
                    'count_time_at' => Carbon::now()
                ]);
        return response()->json(['result' => true]);
    }
    public function image_all_delete(Request $request){
        $ids = $request->all();
        foreach($ids as $id){
            $vehicle_id = VehicleImage::where('id', $id['key'])->first()->vehicle_id;
            $fileName = VehicleImage::where('id', $id['key'])->first()->image;
            $real_image = ('uploads/vehicle/'.$vehicle_id.'/real'.'/'.$fileName);
            $thumb_image = ('uploads/vehicle/'.$vehicle_id.'/thumb'.'/'.$fileName);
            if(File::exists(public_path($real_image))){
                File::delete(public_path($real_image));   
            }
            if(File::exists(public_path($thumb_image))){
                File::delete(public_path($thumb_image));   
            }
            $result = VehicleImage::where('id', $id['key'])->delete();
        }
        return response()->json(['result' => true]);
    }

    public function deltedList(Request $request){
        // auto delete (3 months)
        $deleted_list = Vehicle::withTrashed()->WhereNotNull('deleted_at')->where('deleted_at', '<', Carbon::now()->subMonths(3))->get();
        foreach ($deleted_list as $row) {
            $vehicleId = $row->id;
            $fileNames = VehicleImage::where('vehicle_id', $vehicleId)->get();

            foreach($fileNames as $fileName){
                $real_image = ('uploads/vehicle/'.$vehicleId.'/real'.'/'.$fileName->image);
                $thumb_image = ('uploads/vehicle/'.$vehicleId.'/thumb'.'/'.$fileName->image);
                if(File::exists(public_path($real_image))){
                    File::delete(public_path($real_image));   
                }
                if(File::exists(public_path($thumb_image))){
                    File::delete(public_path($thumb_image));   
                }
            }
            VehicleImage::where('vehicle_id', $vehicleId)->delete();
            Vehicle::where('id', $vehicleId)->forceDelete();
        } 
        return view('admin.pages.vehicle.deleted_list', []);
    }

    public function getDeltedList(Request $request){
        $rate = Rate::latest('id')->first()->rate;
        if ($request->ajax()) {
            $data = Vehicle::leftJoin('vehicle_image', 'vehicle.id', '=', 'vehicle_image.vehicle_id')
                        ->withTrashed()
                        ->WhereNotNull('vehicle.deleted_at')
                        ->orderByRaw('CONVERT(vehicle_image.image, SIGNED) asc')
                        ->groupBy('vehicle.id')
                        ->orderBy('vehicle.deleted_at', 'desc')
                        ->select('vehicle.id', 'vehicle.chassis', 'vehicle.stock_no', 'vehicle.price', 'vehicle.sale_price', 'vehicle.status', 'vehicle.deleted_at', 'vehicle_image.image')
                        ->get();
            return Datatables::of($data)
                            ->addColumn('image', function($row){
                                return  asset('/uploads/vehicle').'/'.$row->id.'/thumb'.'/'.$row->image;
                            })
                            ->addColumn('price', function($row){
                                return  $row->price;
                            })
                            ->addColumn('sale_price', function($row){
                                if($row->sale_price) {
                                    return  $row->sale_price;
                                }
                            })
                            ->addColumn('status', function($row){
                                if($row->status) {
                                    return  $row->status;
                                } else {
                                    return '';
                                }
                            })
                            ->addColumn('usd', function($row) use($rate){
                                if($row->sale_price) {
                                    return  round($row->sale_price / $rate);
                                } else {
                                    return  round($row->price / $rate);
                                }
                            })
                            ->addColumn('deleted_at', function($row){
                                return  date("Y-m-d", strtotime($row->deleted_at));
                            })
                            ->addColumn('action', function($row){
                                return  '<a href="javscript:void(0);" data-id="'.$row->id.'" class="text-success confirm_restore" data-bs-toggle="modal" data-bs-target="#restoreModal">
                                            <i class="mdi mdi-backup-restore font-size-18"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="text-danger confirm_delete" data-id="'.$row->id.'" data-bs-toggle="modal" data-bs-target="#myModal">
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>';
                            })
                            ->make(true);   
        }
    }
    public function restore(Request $request){
        Vehicle::where('id', $request->id)->restore();
        return response()->json(['result' => true]);
    }

    public function forceDelete(Request $request){
        $vehicleId = $request->id;
        $fileNames = VehicleImage::where('vehicle_id', $vehicleId)->get();

        foreach($fileNames as $fileName){
            $real_image = ('uploads/vehicle/'.$vehicleId.'/real'.'/'.$fileName->image);
            $thumb_image = ('uploads/vehicle/'.$vehicleId.'/thumb'.'/'.$fileName->image);
            if(File::exists(public_path($real_image))){
                File::delete(public_path($real_image));   
            }
            if(File::exists(public_path($thumb_image))){
                File::delete(public_path($thumb_image));   
            }
        }
        VehicleImage::where('vehicle_id', $vehicleId)->delete();
        Vehicle::where('id', $vehicleId)->forceDelete();
        return response()->json(['result' => true]);

    }
}
