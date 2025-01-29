<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ESAB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="{{ asset('font/iconsmind-s/css/iconsminds.css') }}" />
    <link rel="stylesheet" href="{{ asset('font/simple-line-icons/css/simple-line-icons.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.rtl.only.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-float-label.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/dore.light.blue.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
    <style>
        .checkbox a {
            color: #efd80b;
        }

        .checkbox a:hover {
            color: #000;
        }

        .error-text {
            color: rgb(204, 0, 0);
            padding: 5px 0;
        }
    </style>
</head>

<body class="background show-spinner no-footer rounded">
    <nav class="navbar">
        <a class="navbar-logo m-auto" href="#">
            <span class="logo d-block"></span>
        </a>
    </nav>
    <main>
        <div class="container certificate_viwe_page">
            <div class="my-3 d-flex align-items-center justify-content-between">
                <h2 class="pb-0 mb-0">Certificates</h3>

            </div>
            <div class="separator mb-5"></div>
            <div class="row h-100 mt-2">
                <div class="col-12 col-xl-12">
                    <div class="card mb-3 p-4">
                        <div class="row">
                            <div class="col-md-6 offset-lg-3">
                                <form action="{{ route('certificate.view', $slug) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="slug" value="{{ $slug }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="inputPassword4">Email</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="Enter email" value="" required="">
                                            @error('email')
                                                <div class="error-text">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12 checkbox">
                                            <input type="checkbox" name="terms" id="checkbox_id" value="terms"
                                                required>
                                            <label for="checkbox_id">Agree to <a href="#" data-toggle="modal"
                                                    data-backdrop="static" data-target="#exampleModalRight">Terms &
                                                    Conditions</a></label>
                                            @error('terms')
                                                <div class="error-text">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="submit" class="btn w-100 btn_primary" value="Submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <div class="modal fade" id="exampleModalRight" tabindex="-1" role="dialog" aria-labelledby="exampleModalRight"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms &
                        Conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! getSettings('terms_conditions') !!}
                </div>
                <div class="modal-footer justify-content-start">
                    <button type="button" data-dismiss="modal" aria-label="Close"
                        class="btn btn_primary">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/vendor/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/dore.script.js') }}"></script>
    <script src="{{ asset('js/scripts.single.theme.js') }}"></script>
    <script src="{{ asset('js/jQuery.print.js') }}"></script>
</body>

</html>
