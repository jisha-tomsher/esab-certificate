@extends('admin.layouts.admin')
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-12">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h1 class="pb-0">Visitor History</h1>
                </div>

                <div class="separator mb-5"></div>
            </div>
        </div>

        <div class="row list " data-check-all="checkAll">
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        @if ($visitors->count())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">#No</th>
                                            <th scope="col" class="w-50 text-center">
                                                Email
                                            </th>
                                            <th scope="col" class=" text-center">
                                                Certificate
                                            </th>
                                            <th scope="col" class=" text-center">
                                                Viewed Time
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($visitors as $visitor)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td class="text-center">
                                                    {{ $visitor->email }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.certificates.view', $visitor->certificate) }}">
                                                        {{ $visitor->certificate->certificate_name }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    {{ $visitor->created_at->format('d/m/Y h:i:s') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h3 class="text-center">No visitors Found</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('header')
    <style>
        .action_list {
            gap: 0;
        }
    </style>
@endpush
