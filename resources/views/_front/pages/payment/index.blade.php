@extends('front.layouts.index')
@section('title') Payment @endsection
@section('css')
    <link href="{{ URL::asset('/assets/frontend/css/hero.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/frontend/pages/payment/style.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="hero">
    <div class="hero-wrapper">
        @include('front.layouts.menu')
    </div>
</div>

<div class="payment-container">
    <!-- Payment Header -->
    <div class="payment-header">
        <h1>PAYMENT</h1>
    </div>

    <!-- Payment Content -->
    <div class="payment-content">
        <div class="payment-card">
            <div class="card-header">
                <h2>Bank Account Information</h2>
                <div class="contact-info">
                    <i class="fas fa-phone"></i>
                    <span>+81-29-868-3668</span>
                </div>
            </div>

            <div class="card-body">
                <div class="account-details">
                    <div class="detail-row">
                        <span class="label">Account Name:</span>
                        <span class="value">Sakura Motors</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Account Number:</span>
                        <span class="value">10,000,000 Yen</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Bank Name:</span>
                        <span class="value">SUMITOMO MITSUI BANKING CORPORATION</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Swift Code:</span>
                        <span class="value">SMBCJPJT</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Branch Name:</span>
                        <span class="value">TSUKUBA BRANCH</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Branch Name:</span>
                        <span class="value">5-19, KENKYUGAKUEN, TSUKUBA-SHI, IBARAKI ,305-0817, JAPAN</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
    </script>
@endsection