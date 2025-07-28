@if($vehicle_data)
    @foreach($vehicle_data as $row)
        <!-- Car 1 -->
        <div class="car-card card-shadow stock-contents">
            <a target="_blank" href="{{route('front.details', ['id' => $row->id])}}" class="image-section">
                <img src="{{URL::asset('/uploads/vehicle')}}{{'/'}}{{$row->id}}{{'/thumb'}}{{'/'}}{{$row->image}}" alt="Nissan Civilian" />
                <div class="top-right-buttons">
                    <button class="image-action-btn">MP3 interface</button>
                    <button class="image-action-btn">ABS</button>
                </div>
                @if($row->status == 'Invoice Issued')
                <button class="top-left-reserved-btn">Reserved</button>
                <div class="reserved-image-overlay"></div>
                @endif
            </a>
            <div class="details-section">
                <div>
                    <h2>{{$row->make_type}}{{' '}}{{$row->model_type}}</h2>
                    <div class="details-grid">
                        <div class="details-grid-left">
                            <p><span class="font-semibold">Stock no:</span> <b>{{$row->stock_no}}</b></p>
                        <p><span class="font-semibold">Gear:</span> <b>{{$row->transmission}}</b></p>
                        <p><span class="font-semibold">Engine CC:</span> <b>{{$row->engine_model}}</b></p>
                        <p><span class="font-semibold">Year:</span> <b>{{$row->registration}}</b></p>
                        </div>
                        <div class="details-grid-right">
                        <p><span class="font-semibold">Engine model:</span> <b>{{$row->engine_model}}</b></p>
                        <p><span class="font-semibold">Seating:</span> <b>{{$row->seats}}</b></p>
                        <p><span class="font-semibold">Model:</span> <b>{{$row->engine_model}}</b></p>
                        <p><span class="font-semibold">Body Type:</span> <b class="body_type">{{$row->body_type}}</b></p>


                        </div>
                    </div>
                    @php
                        $features = [];

                        if ($row->ac) $features[] = 'A/C';
                        if ($row->power_steering) $features[] = 'Power Steering';
                        if ($row->auto_door) $features[] = 'Auto Door';
                        if ($row->navigation) $features[] = 'Navigation';
                        if ($row->power_locks) $features[] = 'Power Locks';
                        if ($row->cd_player) $features[] = 'CD Player';
                        if ($row->dvd) $features[] = 'DVD';
                        if ($row->mp3_interface) $features[] = 'MP3 Interface';

                        $visibleFeatures = array_slice($features, 0, 3);
                        $remainingCount = count($features) - count($visibleFeatures);
                    @endphp

                    <div class="features-container">
                        @foreach ($visibleFeatures as $feature)
                            <span class="feature-badge">{{ $feature }}</span>
                        @endforeach

                        @if ($remainingCount > 0)
                            <span class="feature-badge">+{{ $remainingCount }} more</span>
                        @endif
                    </div>
                </div>
                
            </div>
                <div class="price-inquire-section">
                    <input type="hidden" class="cubic-meter" value="{{($row->length * $row->width * $row->height)/1000000}}">
                    <div class="price-inquire-iner">
                        <input type="hidden" class="price" value="{{round($row->price/$rate)}}">
                        @if($row->sale_price==null)
                            <p class="main-price price-red">${{number_format(round($row->price/$rate))}} <span class="price-fob-text">Price (FOB)</span></p>
                            <p class="description-text">Inspection & Insurance</p>
                            <p class="final-country"> Final Country</p>
                        @else
                            <p class="original-price">was ${{number_format(round($row->sale_price/$rate))}}</p>
                            <p class="main-price price-green"><i class="fa-solid fa-circle-arrow-down-right"></i> ${{number_format(round($row->price/$rate))}} <span class="price-fob-text">Price (FOB)</span></p>
                            <p class="description-text">Inspection & Insurance</p>
                            <p class="final-country"> Final Country</p>
                        @endif
                    </div>
                    <p class="final-price-text">Final Price <span class="price-red final-price-value">-</span></p>
                    <div class="inquire-button-container detail-inquire">
                        <a target="_blank" data-contents="{{route('front.details', ['id' => $row->id])}}" href="{{route('front.details', ['id' => $row->id])}}" class="inquire-button">Inquire</a>
                    </div>
                </div>
        </div>
    @endforeach     
    @if($vehicle_data->hasPages())
        <a  class="view-more-opt" id="load_more_button">View More</a> 
    @endif
@else
 @if($vehicle_data->currentPage() == 1)
    <h3 class="no-data">Sorry. The vehicle you are searching for is currently out of our stock. 
        <br> Please send us an email mentioning your request car and budget: 
        <br> <a href="mailto:info@sakuramotors.com">info@sakuramotors.com</a>
    </h3>
    @endif
@endif


