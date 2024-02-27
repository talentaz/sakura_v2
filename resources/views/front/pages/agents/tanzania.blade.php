@extends('front.layouts.index')
@section('title') Tanzania @endsection
@section('css')
    <link href="{{ URL::asset('/assets/frontend/pages/agent/tanzania.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="hero">
    <div class="hero-wrapper">
        @include('front.layouts.menu')
    </div>
</div>
<div class="contents">
    <div class="agent-details">
        <div class="page-title">
            <ul>
                <li><a href="{{route('front.home')}}">Home <i class="fas fa-angle-right"></i></a></li>
                <li><a class="current-page">Tanzania</a></li>
            </ul>
        </div>
        <div class="agent-contents">
            <div class="agent-list">
                <div class="agent-desc">
                    <h3>SAKURA MOTORS TANZANIA OFFICIAL CONTACT INFORMATION</h3>
                  <h3>WHATS APP : <a href="https://wa.me/0677075474">0677 075 474</a></h3>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <img src="{{asset('/assets/frontend/images/tanzania_2.jpeg')}}" alt="">
                        <div class="agent-person">
                            <h3>YUSUPH KHALIFA</h3>
                            <a href="https://wa.me/0677075470">0677 075 470</a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <img src="{{asset('/assets/frontend/images/tanzania_1.jpeg')}}" alt="">
                        <div class="agent-person">
                            <h3>SIA CHANGE</h3>
                            <a href="https://wa.me/0677075473">0677 075 473</a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                    </div>
                </div>
            </div>
            <div class="agent-location">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <img src="{{asset('/assets/frontend/images/t_building.jpeg')}}" alt="">
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d8521.555265014282!2d39.24944327911242!3d-6.80725428473065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNsKwNDgnMjMuOCJTIDM5wrAxNScxNS44IkU!5e0!3m2!1sen!2slk!4v1700063648691!5m2!1sen!2slk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    
@endsection