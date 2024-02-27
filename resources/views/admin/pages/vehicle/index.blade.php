@extends('admin.layouts.master')
@section('title') Vehicle List @endsection
@section('css')
    <link href="{{ URL::asset('/assets/admin/pages/vehicle/style.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Vehicle Management @endslot
        @slot('title') Vehicle List @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 datatable">
                        <thead>
                            <tr>
                                <th align="center" width="5%">Image</th>
                                <th align="center">Stock No</th>
                                <th align="center">Chassis</th>
                                <th align="center">Price</th>
                                <th align="center">Discounted Price</th>
                                <th align="center">USD Price</th>
                                <th align="center">Status</th>
                                <th align="center">Date</th>
                                <th align="center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Are you sure?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to delete these records? This process cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light delete_button">Delete</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div id="exampleModalScrollable" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="exampleModalScrollableTitle">Change Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-form-label">Order Satus:</label>
                        </div>
                        <div class="col-md-8">
                            <select class="form-select select-status">
                                @foreach($order_status as $row)
                                    <option value="{{$row}}">{{$row}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light save_button">Save</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <!-- Datatable init js -->
    <!-- <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script> -->
    <script>
        var delete_url = "{{route('admin.vehicle.delete')}}";
        var index_url = "{{route('admin.vehicle.index')}}";
        var change_status = "{{route('admin.vehicle.change_status')}}";
        var get_data = "{{ route('admin.vehicle.get_data')}}"
    </script>
    <script src="{{ URL::asset('/assets/admin/pages/vehicle/index.js') }}"></script>
@endsection