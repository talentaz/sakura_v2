@extends('admin.layouts.master')

@section('title') Page Settings @endsection
@section('css')
    
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Content Management @endslot
        @slot('title') Page Settings @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Page Settings</h4>
                        <a href="{{ route('admin.page_setting.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Page
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Page Type</th>
                                    <th>Menu</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $key => $page)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $page->title }}</strong>
                                        @if($page->description)
                                            <br><small class="text-muted">{{ Str::limit($page->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <code>{{ $page->slug }}</code>
                                        @if($page->url)
                                            <br><small class="text-info">External: {{ Str::limit($page->url, 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $page->page_type)) }}</span>
                                    </td>

                                    <td>
                                        @if($page->on_menu)
                                            <span class="badge bg-success">Yes ({{ $page->on_menu_order }})</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($page->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ $page->full_url }}" target="_blank" class="btn btn-sm btn-info" title="View Page">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.page_setting.edit', $page->id) }}" class="btn btn-sm btn-primary" title="Edit Page">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deletePage({{ $page->id }})" title="Delete Page">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td align="center" colspan="9">No pages found.</td>
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
    <div id="deleteModal" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this page? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let deletePageId = null;

        function deletePage(id) {
            deletePageId = id;
            $('#deleteModal').modal('show');
        }

        $('#confirmDelete').click(function() {
            if (deletePageId) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/admin/page_setting/' + deletePageId,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while deleting the page.');
                    }
                });

                $('#deleteModal').modal('hide');
            }
        });
    </script>
@endsection
