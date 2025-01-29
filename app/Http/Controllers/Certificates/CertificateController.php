<?php

namespace App\Http\Controllers\Certificates;

use App\Exports\CertificateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAutoCertificateRequest;
use App\Models\Certificates\Certificate;
use App\Models\Certificates\CertificateFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
// use Spatie\Searchable\Search;
// use Spatie\Searchable\ModelSearchAspect;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManager;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Log;


class CertificateController extends Controller
{
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->can('certificates-list')) {
            abort(403);
        }
        $users = User::whereHas('certificate')->get();
        $query = Certificate::whereStatus(true)->with(['user', 'views']);

        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name-asc':
                $query->orderBy('certificate_no', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('certificate_no', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        if ($request->user_id && $request->user_id !== '0') {
            $query->where('user_id', $request->user_id);
        }
        if ($request->start_date) {
            if ($request->end_date) {
                $query->whereBetween('created_at', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()]);
            } else {
                $query->where('created_at', '>=', Carbon::parse($request->start_date)->startOfDay());
            }
        }
        $certificates = $query->paginate(50)->withQueryString();
        return view('admin.certificates.index')
            ->with([
                'users' => $users,
                'certificates' => $certificates,
                'selected_user' => $request->user_id ?? 0,
                'start_date' => $request->start_date ?? 0,
                'end_date' => $request->end_date ?? 0,
                'sort' => $request->sort ?? 'latest'
            ]);
    }


    public function view(Certificate $certificate)
    {
        if (!Auth::user()->can('certificates-view')) {
            abort(403);
        }
        $certificate->load(['file']);
        $logs = $certificate->views()->paginate(15);
        return view('admin.certificates.view')->with(['certificate' => $certificate, 'logs' => $logs]);
    }

    public function edit(Certificate $certificate)
    {
        if (!Auth::user()->can('certificates-edit')) {
            abort(403);
        }

        $certificate->load(['file']);
        return view('admin.certificates.edit')
            ->with([
                'certificate' => $certificate
            ]);
    }

    public function update(Certificate $certificate, Request $request)
    {

        if (!Auth::user()->can('certificates-edit')) {
            abort(403);
        }

        $oldNumber = $certificate->certificate_no;

        $status = $certificate->update([
            'certificate_name' => $request->title,
            'certificate_no' => $request->cer_number,
            'test' => $request->test,
            'item_1' => $request->item_1,
            'item_2' => $request->item_2,
            'lot_1' => $request->lot_1,
            'lot_2' => $request->lot_2,
            'status' => $request->status,
        ]);

        if ($status) {
            // get old director
            $oldDir = Storage::path('public/certificates-orginal/' . $oldNumber . "/");
            $newDir = Storage::path('public/certificates-orginal/' . $request->cer_number . "/");
            // Rename
            rename($oldDir, $newDir);

            // get old director
            $oldDir = Storage::path('public/certificates/' . $oldNumber . "/");
            $newDir = Storage::path('public/certificates/' . $request->cer_number . "/");
            // Rename
            rename($oldDir, $newDir);
        }

        if ($request->update) {
            return redirect()->route('admin.certificates.index')->with('status', 'Certificate updated');
        }

        if ($request->editcertificate) {
            $certificate->load('file');
            $file = $certificate->file;
            // $file = $certificate->file->getFileStoragePath($certificate->certificate_no);
            $certificateImage = convertPdfToImage($certificate, $file);
            return redirect()->route('admin.certificates.placeQr')->with([
                'image_path' => $certificateImage,
                'file_id' => $file->id,
                'certificate_id' => $certificate->id
            ]);
        }
    }

    public function download(Certificate $certificate)
    {
        $certificate->load('file');
        return  response()->download(public_path() . $certificate->file->getFile($certificate->certificate_no));
    }

    public function print(Certificate $certificate)
    {
        $certificate->load('file');
        return view('admin.certificates.view')->with(['certificate' => $certificate]);
    }

    public function delete(Certificate $certificate)
    {
        if (!Auth::user()->can('certificates-delete')) {
            abort(403);
        }

        $certificate->views()->delete();

        $certificate->load('file');
        $file = $certificate->file->getFileStoragePath($certificate->certificate_no);
        deleteFile($file);

        $file = $certificate->file->getOgFileStoragePath($certificate->certificate_no);
        deleteFile($file);

        $files = Storage::allFiles('public/certificates-orginal/' . $certificate->certificate_no . "/");
        if (empty($files)) {
            Storage::deleteDirectory('public/certificates-orginal/' . $certificate->certificate_no . "/");
        }

        $files = Storage::allFiles('public/certificates/' . $certificate->certificate_no . "/");
        if (empty($files)) {
            Storage::deleteDirectory('public/certificates/' . $certificate->certificate_no . "/");
        }

        $certificate->file->delete();
        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('status', 'Certificate deleted');
    }

    public function export()
    {
        if (!Auth::user()->isA('superadmin')) {
            abort(403);
        }
        $name = "Certificate Export " . time() . '.xlsx';
        return Excel::download(new CertificateExport, $name);
    }

    public function deleteOldCertificates()
    {
        $certifiates = Certificate::whereStatus(false)->where('user_id', auth()->user()->id)->with(['file'])->get();
        foreach ($certifiates as $certifiate) {
            $certifiate->delete();
        }
    }

    public function uploadAutoView()
    {
        if (!Auth::user()->can('certificates-add')) {
            abort(403);
        }
        $this->deleteOldCertificates();
        return view('admin.certificates.uploadauto');
    }
    public function uploadManualView()
    {
        if (!Auth::user()->can('certificates-add')) {
            abort(403);
        }
        $this->deleteOldCertificates();
        return view('admin.certificates.uploadmanual');
    }

    public function uploadFile(Request $request)
    {

        if (!Auth::user()->can('certificates-add')) {
            abort(403);
        }
        $name = uploadImage($request, 'file', 'temp');

        $imageUpload = CertificateFile::create([
            'path' => $name,
            'status' => 'draft',
            'user_id' => Auth::user()->id
        ]);

        return response()->json(['success' => $imageUpload->id]);
    }

    public function uploadAuto(StoreAutoCertificateRequest $request)
    {
        if (!Auth::user()->can('certificates-add')) {
            abort(403);
        }
        $file = CertificateFile::find($request->file_id);

        if ($file) {
            $certificate = Auth::user()->certificate()->create([
                'certificate_name' => $request->title,
                'certificate_no' => $request->cer_number,
                'test' => $request->test,
                'item_1' => $request->item_1,
                'item_2' => $request->item_2,
                'lot_1' => $request->lot_1,
                'lot_2' => $request->lot_2,
                'slug' => Str::uuid()->toString(),
                'status' => false,
            ]);

            $old_path = "/storage/temp/" . $file->path;
            $new_path = "/public/certificates-orginal/" . $request->cer_number . '/' . $file->path;

            moveFile($old_path, $new_path);

            processAutoUpload($certificate, $file, $request->position);

            $file->update([
                'status' => 'active',
                'certificate_no' => $certificate->id,
            ]);

            $certificate->update([
                'status' => true
            ]);

            return redirect()->route('admin.certificates.index')->with('status', 'Certificate uploaded');
        }

        return back()->withErrors('file_error', 'Sorry, something went wrong, please try again');
    }

    public function uploadManual(Request $request)
    {
        if (!Auth::user()->can('certificates-add')) {
            abort(403);
        }
        $file = CertificateFile::find($request->file_id);

        if ($file) {
            $certificate = Auth::user()->certificate()->create([
                'certificate_name' => $request->title,
                'certificate_no' => $request->cer_number,
                'test' => $request->test,
                'item_1' => $request->item_1,
                'item_2' => $request->item_2,
                'lot_1' => $request->lot_1,
                'lot_2' => $request->lot_2,
                'slug' => Str::uuid()->toString(),
                'status' => false,
            ]);

            $old_path = "/storage/temp/" . $file->path;
            $new_path = "/public/certificates-orginal/" . $request->cer_number . '/' . $file->path;

            moveFile($old_path, $new_path);

            $file->update([
                'status' => 'active',
                'certificate_no' => $certificate->id,
            ]);

            // Convert pd to image
            $certificateImage = convertPdfToImage($certificate, $file);

            return redirect()->route('admin.certificates.placeQr')->with([
                'image_path' => $certificateImage,
                'file_id' => $request->file_id,
                'certificate_id' => $certificate->id
            ]);
        }

        return back()->withErrors('file_error', 'Sorry, something went wrong, please try again');
    }

    public function placeQrView()
    {
        if (!Auth::user()->can('certificates-add') || !Auth::user()->can('certificates-edit')) {
            abort(403);
        }
        return view('admin.certificates.certificatePlacement')->with([
            'image_path' => session('image_path'),
            'file_id' => session('file_id'),
            'certificate_id' => session('certificate_id'),
        ]);
    }

    public function placeQr(Request $request)
    {
        if (!Auth::user()->can('certificates-add') || !Auth::user()->can('certificates-edit')) {
            abort(403);
        }

        $x = $request->x / 5.75;
        $y = $request->y / 6.2;

        // $x = $request->x / 5.9;
        // $y = $request->y / 5.9;

        $file = CertificateFile::find($request->file_id);
        $certificate = Certificate::find($request->certificate_id);
        processAutoUpload($certificate, $file, "manual", $x, $y);
        $certificate->update([
            'status' => true
        ]);
        return redirect()->route('admin.certificates.index')->with('status', 'Certificate uploaded');
    }

    public function cancelUpload(Request $request)
    {
        $file = CertificateFile::find($request->file_id);
        $certificate = Certificate::find($request->certificate_id);

        $filePath = $file->getOgFileStoragePath($certificate->certificate_no);
        deleteFile($filePath);
        $files = Storage::allFiles('public/certificates-orginal/' . $certificate->certificate_no . "/");
        if (empty($files)) {
            Storage::deleteDirectory('public/certificates-orginal/' . $certificate->certificate_no . "/");
        }

        $filePath = $file->getFileStoragePath($certificate->certificate_no);
        deleteFile($filePath);
        $files = Storage::allFiles('public/certificates/' . $certificate->certificate_no . "/");
        if (empty($files)) {
            Storage::deleteDirectory('public/certificates/' . $certificate->certificate_no . "/");
        }

        cleanPdfImage($certificate, $file);

        $certificate->file->delete();
        $certificate->delete();

        return redirect()->route('admin.certificates.index');
    }

    public function checkNumber(Request $request)
    {
        $query = Certificate::where('certificate_no', $request->cer_number);
        if ($request->current_id) {
            $query->where('id', '!=', $request->current_id);
        }
        $certificate = $query->first();
        if ($certificate) {
            return json_encode(false);
        }
        return json_encode(true);
    }

    public function searchView()
    {
        return view('admin.certificates.search');
    }

    public function searchResult(Request $request)
    {

        $query = Certificate::where('status', true)
            ->where('certificate_no', 'like', '%' . $request->q . '%')
            ->orWhere('certificate_name', 'like', '%' . $request->q . '%')
            ->orWhere('test', 'like', '%' . $request->q . '%')
            ->orWhere('item_1', 'like', '%' . $request->q . '%')
            ->orWhere('item_2', 'like', '%' . $request->q . '%')
            ->orWhere('lot_1', 'like', '%' . $request->q . '%')
            ->orWhere('lot_2', 'like', '%' . $request->q . '%');

        // $searchResults = (new Search())
        //     ->registerModel(
        //         Certificate::class,
        //         'certificate_no',
        //         'certificate_name',
        //         'test',
        //         'item_1',
        //         'item_2',
        //         'lot_1',
        //         'lot_2',
        //     )->search($request->q);

        $isAdmin = Auth::user()->isA('superadmin');
        $user = Auth::user()->id;

        // $searchResults->each(function ($q) {
        //     $q->searchable->load('user');
        // });

        if (!$isAdmin) {

            $query->where('user_id', $user);

            // $filtered = $searchResults->filter(function ($q)  use ($isAdmin, $user) {
            //     return $q->searchable->user_id == $user;
            // });
            // $searchResults =  $filtered;
        }

        $searchResults = $query->with(['user'])->get();

        return view('admin.certificates.search-result')
            ->with(['searchResults' => $searchResults]);
    }

    public function testView()
    {
        return view('admin.test');
    }
}
