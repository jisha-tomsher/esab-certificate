@extends('admin.layouts.admin')
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-12">
                <div class="mb-0">
                    <h1>Search Results</h1>
                    <div class="text-zero top-right-button-container">
                    </div>
                </div>
                <div class="separator mb-5"></div>
            </div>
        </div>

        <div class="row list ">
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card recent_certificate">
                    <div class="card-body">
                        <div class="data_card">
                            <div class="table-responsive">

                                @if ($searchResults->count())
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Certificate No</th>
                                                <th scope="col">Test</th>
                                                <th scope="col">Item 1</th>
                                                <th scope="col">Item 2</th>
                                                <th scope="col">Lot 1</th>
                                                <th scope="col">Lot 2</th>
                                                <th scope="col">Uploaded Date</th>
                                                <th scope="col">Uploaded User</th>
                                                <th scope="col">No of Downloads</th>
                                                <th scope="col">No of Views</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($searchResults as $searchResult)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.certificates.view', $searchResult) }}">
                                                            {{ $searchResult->certificate_no }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->test }}
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->item_1 }}
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->item_2 }}
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->lot_1 }}
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->lot_2 }}
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->created_at->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->user->name }}
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->getDownloads() }}
                                                    </td>
                                                    <td>
                                                        {{ $searchResult->getViews() }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <h3>No results found</h3>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
