
@if($vehicle_data)
    @foreach($vehicle_data as $row)
        <div class="contents-list">
            <div class="stock-mobile-title">
                <a target="_blank" href="{{route('front.details', ['id' => $row->id])}}">
                    <h5>{{$row->make_type}}{{' '}}{{$row->model_type}}</h5>
                </a>
            </div>
            <div class="media-count">
                    @if(isset($row->image_length))
                        <div class="image-count" data-id="{{$row->id}}">
                            <i class="fas fa-camera"></i>
                            <span>{{$row->image_length}}</span>
                        </div>
                    @endif
                    @if(isset($row->video_link))
                        <div class="video-count" data-id="{{$row->video_link}}">
                            <i class="fas fa-video"></i>
                            <span>1</span>
                        </div>
                    @endif
            </div>
            <div class="stock-image">
                <a target="_blank" href="{{route('front.details', ['id' => $row->id])}}">
                    <img src="{{URL::asset('/uploads/vehicle')}}{{'/'}}{{$row->id}}{{'/thumb'}}{{'/'}}{{$row->image}}" alt="">
                    @if($row->status == 'Invoice Issued')
                        <div class="reserved-mark">Reserved</div>
                    @endif
                </a>                                              
            </div>
            <div class="stock-contents">
                <a target="_blank" href="{{route('front.details', ['id' => $row->id])}}" class="stock-name">
                    <h5>{{$row->make_type}} {{' '}} {{$row->model_type}}</h5>
                </a>
                <table class="table table-bordered dt-responsive  nowrap w-100">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="table-light" scope="result">STOCK NO</td>
                            <td>{{$row->stock_no}}</td>
                            <td class="table-light">Year</td>
                            <td>{{$row->registration}}</td>
                            <td class="table-light">Model</td>
                            <td>{{$row->model_code}}</td>
                        </tr>
                        <tr>
                            <td class="table-light" scope="result">Gear</td>
                            <td>{{$row->transmission}}</td>
                            <td class="table-light">ENGINE MODEL</td>
                            <td>{{$row->engine_model}}</td>
                            <td class="table-light">Body Type</td>
                            <td class="body_type">{{$row->body_type}}</td>
                        </tr>     
                        <tr>
                            <td class="table-light">Engine CC</td>
                            <td>{{$row->engine_size}}</td>
                            <td class="table-light">Seating</td>
                            <td>{{$row->seats}}</td>
                            <td class="table-light">Chassis</td>
                            <td>{{$row->chassis}}</td>
                        </tr>
                    <tr>
                        <td class="table-light">OPTIONS</td>
                        <td colspan="5">
                            {{$row->ac==1 ? 'A/C, ':''}}
                            {{$row->power_steering==1 ? 'Power Steering, ':''}}
                            {{$row->auto_door==1 ? 'Auto Door, ':''}}
                            {{$row->remote_key==1 ? 'Remote Key, ':''}}
                            {{$row->navigation==1 ? 'Navigation, ':''}}
                            {{$row->power_locks==1 ? 'Power Locks, ':''}}
                            {{$row->cd_player==1 ? 'CD player, ':''}}
                            {{$row->dvd==1 ? 'DVD, ':''}}
                            {{$row->mp3_interface==1 ? 'MP3 interface, ':''}}
                        </td>
                    </tr>      
                    </tbody>
                </table>
            </div>
            <div class="stock-mobile-contents">
                <div class="stock-mobile-info"><p class="stock-label">Stock No :</p>
                <p class="stock-value">{{$row->stock_no}}</p></div>
                <div class="stock-mobile-info"><p class="stock-label">Year :</p>
                <p class="stock-value">{{$row->registration}}</p></div>
                <div class="stock-mobile-info"><p class="stock-label">Model :</p>
                <p class="stock-value">{{$row->model_code}}</p></div>
                <div class="stock-mobile-info"><p class="stock-label">Gear :</p>
                <p class="stock-value">{{$row->transmission}}</p></div>
                <div class="stock-mobile-info"><p class="stock-label">Engine :</p>
                <p class="stock-value">{{$row->engine_model}}</p></div>
                <input type="hidden" class="mobile-body-type" value="{{$row->body_type}}">
            </div>
            <div class="stock-price-list">
                @php 
                    $now = Carbon\Carbon::now();
                    $countTime = $row->count_time;
                    $counTimeAt = Carbon\Carbon::parse($row->count_time_at);
                    $diffTime = $now->diffInSeconds($counTimeAt); 
                    $result = $countTime*3600 - $diffTime;
                @endphp
                @if($row->status == "Invoice Issued")
                <div class="under-offer">
                    <h3>Under Offer</h3>
                    @if($result > 0)
                    <p class="remaining-time" data-time="{{ $result }}">
                        Available in: 
                        <!-- <span class="hour-time"></span>
                        <span class="symbol">h,</span>
                        <span class="min-time"></span>
                        <span class="symbol">m,</span>
                        <span class="sec-time"></span>
                        <span class="symbol">s,</span> -->
                    </p>
                    @endif
                    @if(Auth::user() && Auth::user()->role == 2)
                        @php
                            $user_id = Auth::user()->id;
                            $notify = App\Models\Notify::where('user_id', $user_id)->where('vehicle_id', $row->id)->first();
                        @endphp
                        @if($notify)
                        <p class="added-notify">Added to NOTIFY ME</p>
                        @else
                            <a class="nofity-signup add-notify" href="javascript: void(0);"  data-vehicle-id="{{ $row->id }}"  data-user-id="{{ $user_id }}" >Inform me when it's available</a>    
                        @endif
                    @else
                        <a class="nofity-signup" href="{{route('front.user.signup', ['vehicle_id' => $row->id])}}">Inform me when it's available</a>
                    @endif
                </div>
                @else
                <div class="fob-price">
                    <input type="hidden" class="cubic-meter" value="{{($row->length * $row->width * $row->height)/1000000}}">
                    @if($row->sale_price==null)
                        <span class="fob-label">Price (FOB)</span>
                        <input type="hidden" class="price" value="{{round($row->price/$rate)}}">
                        <span class="fob-value">{{number_format(round($row->price/$rate))}}</span>
                    @else
                        <div class="original-price">Original Price ${{number_format(round($row->price/$rate))}}</div>
                        <div class="price-border-bottom"></div>
                        <span class="fob-label">Price (FOB)</span>
                        <input type="hidden" class="price" value="{{round($row->sale_price/$rate)}}">
                        <span class="fob-value">${{number_format(round($row->sale_price/$rate))}}</span>
                    @endif
                </div>
                <div class="price-border-bottom"></div>
                <div class="total-price">
                    <span class="total-label">Total Price</span>
                    <span class="totla-value">ASK</span>
                </div>
                <div class="price-border-bottom"></div>
                <div class="country-port">
                    <p class="cif">(Final Country)</p>
                    <p class="port">Port</p>
                </div>
                <div class="detail-inquire">
                    <a target="_blank" href="{{route('front.details', ['id' => $row->id])}}" data-contents="{{route('front.details', ['id' => $row->id])}}" class="btn-detail">Details</a>
                    <a target="_blank" href="{{route('front.details', ['id' => $row->id])}}" class="btn-inquire">Inquire</a>
                </div>
                @endif
            </div>
        </div>
    @endforeach
    @if($vehicle_data->hasPages())
        <div id="load_more">
            <button type="button" name="load_more_button"  id="load_more_button">View More</button>
        </div>
    @else
        <div id="load_more" style="display:none">
            <button type="button" name="load_more_button">No Data Found</button>
        </div>
    @endif
@else
    @if($vehicle_data->currentPage() == 1)
    <h3 class="no-data">Sorry. The vehicle you are searching for is currently out of our stock. 
        <br> Please send us an email mentioning your request car and budget: 
        <br> <a href="mailto:info@sakuramotors.com">info@sakuramotors.com</a>
    </h3>
    @endif
@endif

<script>
    function countDownTimer() {
        // Get all elements with class 'remaining-time'
        var remainingTimeElements = document.getElementsByClassName('remaining-time');
        
        // Loop through each element and update the countdown timer
        for (var i = 0; i < remainingTimeElements.length; i++) {
            var remainingTimeElement = remainingTimeElements[i];
            var remainingTime = parseInt(remainingTimeElement.getAttribute('data-time'));
            
            // Calculate hours, minutes, and seconds
            var hours = ("0" + Math.floor(remainingTime / 3600)).slice(-2);
            var minutes = ("0" + Math.floor((remainingTime % 3600) / 60)).slice(-2);
            var seconds = ("0" + remainingTime % 60).slice(-2);
            
            // Update the content of the element with the countdown timer
            remainingTimeElement.innerHTML = '<p class="available">Available in: </p>' + '<span class="time">'+ hours + '</span>' + '<span class="symbol"> h, </span>' + '<span class="time">'+ minutes + '</span>' + '<span class="symbol"> m, </span>' + '<span class="time">'+ seconds + '</span>' + '<span class="symbol"> s</span>';
            
            // Decrease remaining time by 1 second
            remainingTime--;
            
            // Update the data-time attribute to reflect the new remaining time
            remainingTimeElement.setAttribute('data-time', remainingTime);
        }
    }
    
    // Update countdown timer every second
    var timer = setInterval(countDownTimer, 1000);
</script>