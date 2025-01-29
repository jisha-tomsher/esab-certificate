<?php

use App\Models\Certificates\Certificate;
use App\Models\Certificates\CertificateFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManager;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;
use Spatie\PdfToImage\Pdf;

use Illuminate\Support\Facades\Log;

function processManualUpload(Certificate $certificate, CertificateFile $file)
{
}

function processAutoUpload(Certificate $certificate, CertificateFile $file, $position, $x = 0, $y = 0)
{
    $time = time();
    $qr = generateQRCode($certificate->slug, $time);

    $old_path = "/public/certificates-orginal/" . $certificate->certificate_no . '/' . $file->path;
    $new_path = "/public/certificates/" . $certificate->certificate_no . '/' . $file->path;
    copyFile($old_path, $new_path);

    $cerificate_image = $file->getFilePath($certificate->certificate_no);
    mergeImages($qr, $cerificate_image, $file->path, $position, $x, $y);
    cleanFiles($time);
    cleanPdfImage($certificate, $file);
    return true;
}

function generateQRCode($slug, $time)
{
    $output_file = 'qr-code/img-' . $time . '.png';
    $url = URL::to(route('certificate.view', $slug));
    $qrString = QrCode::size(80)->backgroundColor(255, 255, 255, 0)->format('png')->generate($url);
    Storage::disk('public')->put($output_file, $qrString);
    return  Storage::disk('public')->path($output_file);
}

function mergeImages($qr, $cerificate, $name, $position, $xLoc, $yLoc)
{
    $time = time();
    $pdf = new Fpdi();
    $pdf->setSourceFile($cerificate);
    $tplId = $pdf->importPage(1);
    $size = $pdf->getTemplateSize($tplId);
    // $pdf->AddPage('P','A4');
    $pdf->AddPage();
    $pdf->SetMargins(0, 0, 0);
    $pdf->SetXY(0, 0);
    $pdf->SetCompression(false);
    $pdf->useTemplate($tplId, null, null, $size['width'], $size['height'], FALSE);
    $x = 0;
    $y = 0;

    if ($position ==  'manual') {
        $x = $xLoc;
        $y = $yLoc;
    } else if ($position == 'top_left') {
        $x = 20;
        $y = 40;
    } else if($position == 'top_right'){
        $x = 150;
        $y = 40;
    } else if ($position == 'bottom_left') {
        $x = 20;
        $y = 245;
    } else if ($position == 'bottom_right') {
        $x = 160;
        $y = 245;
    }

    $pdf->Image($qr, $x, $y);
    $pdf->Output('F', $cerificate);
}

function cleanFiles($time)
{
    $output_file = "public/qr-code/img-" . $time . ".png";
    if (Storage::exists($output_file)) {
        Storage::delete($output_file);
    }
}

function cleanPdfImage(Certificate $certificate, CertificateFile $file)
{
    $fileName = basename($file->path, '.pdf') . '.jpg';
    $image_path = storage_path('app/public/pdfImages/' . $certificate->certificate_no . $fileName);

    if (is_file($image_path)) {
        unlink($image_path);
    }
}

function convertPdfToImage(Certificate $certificate, CertificateFile $file)
{
    $fileName = basename($file->path, '.pdf') . '.jpg';
    $image_path = storage_path('app/public/pdfImages/' . $certificate->certificate_no . $fileName);
    $pdf = new Pdf($file->getOgFilePath($certificate->certificate_no));
    $pdf->setResolution(300);
    $pdf->saveImage($image_path);
    return URL::to('storage/pdfImages/' . $certificate->certificate_no . $fileName);
}
