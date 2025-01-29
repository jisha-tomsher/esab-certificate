@extends('admin.layouts.admin')
@section('content')
    <div class="container-fluid disable-text-selection">
        <div class="row">
            <div class="col-12">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h1 class="pb-0">Update Profile</h1>
                    <a href="{{ route('admin.users.index') }}" class="btn btn_primary">Back</a>
                </div>
                <div class="separator mb-5"></div>
            </div>
        </div>

        <div class="row ">
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.settings.update', $setting) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="key" value="{{ $setting->key }}">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="#">Content</label>
                                    <textarea name="value" id="" class="form-control" cols="30" rows="10">{{ old('value', $setting->value) }}</textarea>
                                    <x-form.error name="value" />
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="submit" value="Update" class="btn btn_primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('footer')
    <script src="{{ asset('js/vendor/select2.full.js') }}"></script>
    <script>
        $('#deleteBtn').on('click', function() {
            $confirm = confirm("Do you want to delete this user?");
            if ($confirm) {
                $('#deleteForm').submit();
            }
        });
    </script>
@endpush
