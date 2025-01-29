<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Certificates\Certificate;
use App\Models\VisitorHistory;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        // dd("Asd");
        // return redirect()->to('https://esab.com/sa/mea_en/');
        return redirect()->route('login');
    }

    public function certificate($slug)
    {
        return view('frontend.viewForm')->with(['slug' => $slug]);
    }
    public function certificateView(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'terms' => 'required',
        ], [
            'email.required' => "Please enter your email",
            'email.email' => "Please enter a valid email",
            'terms.required' => "Please agree to the terms & conditions",
        ]);

        $certificate = Certificate::with('file')->whereSlug($request->slug)->firstOrFail();

        VisitorHistory::create([
            'email' => $request->email,
            'certificate_id' => $certificate->id
        ]);

        views($certificate)
            ->cooldown(1440)
            ->collection('views')
            ->record();
        return view('frontend.view')->with(['certificate' => $certificate]);
    }
    public function download(Request $request)
    {
        $certificate = Certificate::whereSlug($request->slug)->firstOrFail();
        views($certificate)
            ->cooldown(1440)
            ->collection('downloads')
            ->record();
        return true;
    }
}
