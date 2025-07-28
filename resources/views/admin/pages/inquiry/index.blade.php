@extends('admin.layouts.master')
@section('title') Inquiries @endsection
@section('css')
    <link href="{{ URL::asset('/assets/admin/pages/inquiry/style.css') }}" rel="stylesheet" type="text/css" />
@endsection

@php
    // Helper function to get status badge class
    function getStatusBadgeClass($status) {
        switch($status) {
            case 'Reserved': return 'bg-warning';
            case 'Ready to Ship': return 'bg-primary';
            case 'Open': return 'bg-success';
            case 'Inactive': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }
@endphp
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Inquiry Management @endslot
        @slot('title') Inquiries @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Inquiry List</h4>
                        <div>
                            <label for="entries-select">Show</label>
                            <select id="entries-select" class="form-select d-inline-block w-auto">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ACTIONS</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>VEHICLE NAME</th>
                                    <th>STOCK NUMBER</th>
                                    <th>VEHICLE STATUS</th>
                                    <th>TOTAL</th>
                                    <th>DATE SUBMITTED</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inquiries as $inquiry)
                                <tr>
                                    <td>{{ $inquiry->id ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.inquiry.edit', $inquiry->id) }}" class="btn btn-sm btn-success">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-inquiry"
                                               data-id="{{ $inquiry->id ?? '' }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $inquiry->inqu_name ?? 'N/A' }}</td>
                                    <td>{{ $inquiry->vehicle_name ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($inquiry->stock_no) && $inquiry->stock_no)
                                            <a href="{{ $inquiry->site_url ?? '#' }}" target="_blank" class="text-primary">
                                                {{ $inquiry->stock_no }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ getStatusBadgeClass($inquiry->vehicle_status ?? 'Open') }} status-badge"
                                              data-id="{{ $inquiry->id ?? '' }}"
                                              data-status="{{ $inquiry->vehicle_status ?? 'Open' }}"
                                              style="cursor: pointer;">
                                            {{ $inquiry->vehicle_status ?? 'Open' }}
                                        </span>
                                    </td>
                                    <td>${{ $inquiry->total_price ?? '0' }}</td>
                                    <td>{{ isset($inquiry->created_at) ? $inquiry->created_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No inquiries found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this inquiry? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    var delete_url = "{{ route('admin.inquiry.delete') }}"
</script>
<script src="{{ URL::asset('/assets/admin/pages/inquiry/index.js') }}"></script>
@endsection