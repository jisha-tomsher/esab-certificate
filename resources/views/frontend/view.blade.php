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
    {{-- <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css"> --}}
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />

    <style>
        .iframe {
            width: 100%;
            height: 700px;
            border: none;
        }

        #the-canvas {
            border: 1px solid black;
            direction: ltr;
            width: 100%;
        }

        @media(min-width:1024px) {
            .data_line {
                display: flex;
                flex-direction: revert;
                gap: 10px;
            }

            .data_line p {
                width: 50%;
            }
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
                    <div class="certificate_action ml-auto pr-4">
                        {{-- <a href="{{ route('certificate.download',$certificate->slug) }}"> --}}
                        <a id="download"
                            href="{{ URL::to($certificate->file->getFile($certificate->certificate_no)) }}" download>
                            <span class="mr-2"> <i class="glyph-icon simple-icon-cloud-download pr-1"></i>Download
                            </span>
                        </a>
                        <a href="#" class="print">
                            <span> <i class="glyph-icon simple-icon-printer pr-1"></i>Print</span>
                        </a>
                    </div>
            </div>
            <div class="separator mb-5"></div>
            <div class="row h-100 mt-2">
                <div class="col-12 col-xl-12">
                    <div class="card mb-3 p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="certificate_card  border ">
                                    <canvas id="the-canvas"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="data_card">
                                    <p><b>{{ $certificate->certificate_name }} </b></p>
                                    <p><b>Certificate No : </b> {{ $certificate->certificate_no }}</p>
                                    <div class="data_line">
                                        @if ($certificate->item_1)
                                            <p><b>Item 1: </b> {{ $certificate->item_1 }}</p>
                                        @endif
                                        @if ($certificate->lot_1)
                                            <p><b>Lot 1: </b> {{ $certificate->lot_1 }}</p>
                                        @endif
                                    </div>
                                    <div class="data_line">
                                        @if ($certificate->item_2)
                                            <p><b>Item 2: </b> {{ $certificate->item_2 }}</p>
                                        @endif
                                        @if ($certificate->lot_2)
                                            <p><b>Lot 2: </b> {{ $certificate->lot_2 }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/vendor/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/dore.script.js') }}"></script>
    <script src="{{ asset('js/scripts.single.theme.js') }}"></script>
    <script src="{{ asset('js/jQuery.print.js') }}"></script>

    {{-- <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script> --}}

    <script>
        $(function() {
            $('.print').on('click', function() {
                // printJS('{{ URL::to($certificate->file->getFile($certificate->certificate_no)) }}') // an entire obj

                // $.print("#the-canvas");
                var iframe = document.createElement('iframe');
                // Hide the IFrame.  
                iframe.style.visibility = "hidden";
                // Define the source.  
                iframe.src = '{{ URL::to($certificate->file->getFile($certificate->certificate_no)) }}';
                // Add the IFrame to the web page.
                document.body.appendChild(iframe);
                iframe.contentWindow.focus();
                iframe.contentWindow.print(); // Print.
            });
        });
    </script>

    <script>
        $('#download').on('click', function(e) {
            $.ajax({
                method: "POST",
                url: "{!! route('certificate.download') !!}",
                data: {
                    _token: "{{ csrf_token() }}",
                    slug: "{{ $certificate->slug }}"
                }
            })
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"
        integrity="sha512-xzh64kBm/sNMH3yE0yfg/V6gl1uzZ6oJCAC14KTY9ORUZzUVe2B/GJYlpmV2J2vrpbheZaeqHIw3XzOePOjx6Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var url = '{{ URL::to($certificate->file->getFile($certificate->certificate_no)) }}';
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        var loadingTask = pdfjsLib.getDocument(url);
        loadingTask.promise.then(function(pdf) {
            console.log('PDF loaded');
            var pageNumber = 1;
            pdf.getPage(pageNumber).then(function(page) {
                console.log('Page loaded');
                var scale = 1.5;
                var viewport = page.getViewport({
                    scale: scale
                });
                var canvas = document.getElementById('the-canvas');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);
                renderTask.promise.then(function() {});
            });
        }, function(reason) {
            console.error(reason);
        });
    </script>

</body>

</html>
