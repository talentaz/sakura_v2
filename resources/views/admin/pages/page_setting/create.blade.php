@extends('admin.layouts.master')
@section('title') Create Page @endsection
@section('css')

@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Content Management @endslot
        @slot('title') Create Page @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create New Page</h4>
                    <form id="pageForm" class="custom-validation">
                        @csrf
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required />
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-control-label" for="input-slug">Unique Slug <sup class="text-danger">*</sup> <small class="text-muted">(should contain letters and dashes only)</small></label>
                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text py-2 text-muted" id="website-domain">https://www.sakuramotors.com/page/</span>
                                        </div>
                                        <input type="text" name="slug" id="input-slug" class="form-control " placeholder="Unique Slug" value="" required="">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" class="form-control" name="keywords" />
                                    <small class="text-muted">SEO keywords separated by commas</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce-editor" name="editor_content" required></textarea>
                                    <textarea name="plain_content" style="display: none;"></textarea>
                                </div>
                            </div>

                            <!-- Settings Sidebar -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Page Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Page Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="page_type" required>
                                                <option value="inner_page" selected>Inner Page</option>
                                            </select>
                                        </div>



                                        <div class="mb-3">
                                            <label class="form-label">Menu Order <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="on_menu_order" value="0" min="0" required />
                                        </div>



                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="on_menu" id="on_menu" checked>
                                                <label class="form-check-label" for="on_menu">
                                                    Show in Menu
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Create Page</button>
                                <a href="{{ route('admin.page_setting.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- TinyMCE js -->
    <script src="{{ URL::asset('/assets/libs/tinymce/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize TinyMCE
            tinymce.init({
                selector: '.tinymce-editor',
                height: 300,
                plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                        // Auto-generate plain content from editor
                        let content = editor.getContent();
                        let plainText = $('<div>').html(content).text();
                        $('textarea[name="plain_content"]').val(plainText);
                    });
                }
            });

            // Auto-generate slug from title
            $('input[name="title"]').on('keyup', function() {
                let title = $(this).val();
                let slug = title.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                $('input[name="slug"]').val(slug);
            });
        });

        // Form variables
        let update_url = "{{ route('admin.page_setting.store') }}";
        let list_url = "{{ route('admin.page_setting.index') }}";
    </script>
    <script src="{{ URL::asset('/assets/admin/pages/page_setting/form.js') }}"></script>
@endsection
