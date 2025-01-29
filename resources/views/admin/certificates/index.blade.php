@extends('admin.layouts.admin')
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-12">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h1 class="m-0 p-0">Certificates</h1>

                    <div class="btn_group d-flex">
                        @if ($selected_user || $start_date || $end_date)
                            <a href="{{ route('admin.certificates.index') }}" class="btn btn_primary  mr-2">Clear Filter</a>
                        @endif
                        <a href="#" class="btn btn_secondary mr-2" data-toggle="modal" data-backdrop="static"
                            data-target="#exampleModalRight">Filter</a>

                        @if (auth()->user()->can('certificates-export'))
                            <form class="d-inline" action="{{ route('admin.certificates.export') }}" method="POST">
                                @csrf
                                <input type="submit" value="Export Excel" class="btn btn_primary">
                            </form>
                        @endif
                    </div>
                </div>
                <div class="separator mb-5"></div>
            </div>
        </div>

        <x-form.status />

        <div class="row">
            <div class="col-xl-12">
                <div class="card recent_certificate">
                    <div class="card-body">
                        <div class="data_card">
                            <div class="table-responsive">
                                @if ($certificates->count())
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    Certificate No
                                                </th>
                                                <th scope="col">Test</th>
                                                <th scope="col">Item 1</th>
                                                <th scope="col">Lot 1</th>
                                                <th scope="col">Item 2</th>
                                                <th scope="col">Lot 2</th>
                                                <th scope="col">
                                                    Uploaded Date
                                                </th>
                                                <th scope="col">
                                                    Uploaded User
                                                </th>
                                                <th scope="col">
                                                    No of Downloads
                                                </th>
                                                <th scope="col">
                                                    No of Views
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($certificates as $certificate)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.certificates.view', $certificate) }}">
                                                            {{ $certificate->certificate_no }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $certificate->test }}
                                                    </td>
                                                    <td>
                                                        {{ $certificate->item_1 }}
                                                    </td>
                                                    <td>
                                                        {{ $certificate->lot_1 }}
                                                    </td>
                                                    <td>
                                                        {{ $certificate->item_2 }}
                                                    </td>
                                                    <td>
                                                        {{ $certificate->lot_2 }}
                                                    </td>
                                                    <td>
                                                        {{ $certificate->created_at->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        {{ $certificate->user->name }}
                                                    </td>
                                                    <td>
                                                        {{ $certificate->getDownloads() }}
                                                    </td>
                                                    <td>
                                                        {{ $certificate->getViews() }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>
                                        No Cerificates To List
                                    </p>
                                @endif
                            </div>
                            {{ $certificates->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade modal-right" id="exampleModalRight" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalRight" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter By Certificates</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ route('admin.certificates.index') }}" id="filterForm">
                        <div class="form-group">
                            <label>User</label>
                            <select name="user_id" class="form-control" id="exampleFormControlSelect1">
                                <option value='0'>Select User</option>
                                @if ($users)
                                    @foreach ($users as $user)
                                        <option {{ $selected_user == $user->id ? 'selected' : '' }}
                                            value="{{ $user->id }}">
                                            {{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group" style="position:relative">
                            <label>Start Date</label>
                            <input name="start_date" type="text" class="form-control" id="start_date"
                                placeholder="Select Start Date" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input name="end_date" type="text" class="form-control" id="end_date"
                                placeholder="Select End Date" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Sort By</label>
                            <select name="sort" id="sortSelect" class="form-control" style="min-width: 120px">
                                <option {{ $sort == 'latest' ? 'selected' : '' }} value="latest">Latest</option>
                                <option {{ $sort == 'oldest' ? 'selected' : '' }} value="oldest">Oldest</option>
                                <option {{ $sort == 'name-asc' ? 'selected' : '' }} value="name-asc">Name Ascending
                                </option>
                                <option {{ $sort == 'name-desc' ? 'selected' : '' }} value="name-desc">Name Descending
                                </option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer justify-content-start">
                    <button type="button" onclick="document.getElementById('filterForm').submit();"
                        class="btn btn_primary">Submit</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('footer')
    <script>
        $('#sortSelect').on('change', function() {
            $('#sortForm').submit();
        });
    </script>
@endpush
