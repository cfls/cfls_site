<?php

namespace App\Http\Controllers;

use App\Models\Don;
use App\Models\Feature;
use App\Models\General;
use App\Models\Member;
use App\Models\Company;
use App\Models\History;
use App\Models\Mission;
use App\Models\Soutien;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{

//    public function index()
//    {
//        return view('welcome');
//    }
    public function index()
    {
        $data = Company::first();
        $features = Feature::where('status', 1)->get();
        $history = History::first();
        $mission = Mission::first();
        $don = Don::first();
        $members = Member::first();

        $testimonials = Testimonial::where('status', 1)->get();



        return view('home.index', compact('data','features','history','mission','don','members','testimonials'));
    }

    public function contacto()
    {


        $data = Company::first();
        return view('home.contact', compact('data'));

    }

    public function general()
    {
        $data = General::first();
        return view('home.general', compact('data'));
    }

    public function dictionnaire()
    {
        return view('home.dictionnaire');
    }




}
