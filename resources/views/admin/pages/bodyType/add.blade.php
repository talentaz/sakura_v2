@extends('admin.layouts.master')

@section('title') Add vehicle type @endsection
@section('css')
    <link href="{{ URL::asset('/assets/admin/pages/bodyType/style.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Category @endslot
        @slot('title') Add Vehicle Type @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="my-page">
                        <form id="myForm" class="custom-validation">
                            @csrf
                            <div class="picture-container">
                                <div class="picture">
                                    <img src="{{asset('/assets/images/users/user-profile.jpg') }}" class="picture-src" id="wizardPicturePreview" title="">
                                    <input type="file" id="wizard-picture" name="file" class="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label">Vehicle Type</label>
                                    <input type="text" class="form-control" name="vehicle_type" required />
                                </div>
                                <div class="col-md-4 mt-4">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section('script')
    <script>
        var add_url = "{{route('admin.bodyType.add_post')}}"
        var list_url = "{{route('admin.bodyType.index')}}"
    </script>
    
    <script src="{{ URL::asset('/assets/admin/pages/bodyType/add.js') }}"></script>
@endsection
