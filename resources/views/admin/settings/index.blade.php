@extends('admin.layouts.admin')
@section('content')
    <div class="container-fluid disable-text-selection">
        <div class="row">
            <div class="col-12">
                <div class="mb-3 d-flex align-items-center justify-content-between">
                    <h1 class="pb-0">Settings</h1>
                </div>

                <div class="separator mb-5"></div>
            </div>
        </div>

        <div class="row list disable-text-selection" data-check-all="checkAll">
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        @if ($settings->count())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">#No</th>
                                            <th scope="col" class="w-50 text-center">
                                                Name
                                            </th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($settings as $setting)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td class="text-center">
                                                    {{ $setting->name }}
                                                </td>
                                                <td>
                                                    @if (Auth()->user()->isA('superadmin'))
                                                        <ul class="action_list">
                                                            <li>
                                                                <a class="pr-4"
                                                                    href="{{ route('admin.settings.edit',$setting) }}">
                                                                    <img src="{{ getAdminAsset('img/pencil.png') }}"
                                                                        width="20" class="img-fluid" alt="Edit">
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h3 class="text-center">No Setting Found</h3>
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
