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
use App\Models\SportSection;
use App\Models\SportSubsection;
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

    public function ekhoSport()
    {
        // Portada oculta temporalmente: las subsecciones (/ekho-sport/{subsection}) siguen accesibles.
        // Para publicarla, eliminar esta línea.
        return view('home.ekho-sport-soon');

//        $sections = SportSection::with([
//            'subsections' => fn ($q) => $q->active()->withCount(['videos' => fn ($v) => $v->active()]),
//        ])
//            ->orderBy('sort_order')
//            ->get();
//
//        $totalVideos = $sections->sum(fn ($s) => $s->subsections->sum('videos_count'));
//
//        return view('home.ekho-sport', compact('sections', 'totalVideos'));
    }

    public function ekhoSportShow(SportSubsection $subsection)
    {
        abort_unless($subsection->status, 404);

        $subsection->load([
            'videos' => fn ($q) => $q->active(),
            'section.subsections' => fn ($q) => $q->active()->withCount(['videos' => fn ($v) => $v->active()]),
        ]);

        // Subsecciones en el orden del libro, para "Précédente / Suivante"
        $ordered = SportSubsection::query()
            ->active()
            ->join('sport_sections', 'sport_sections.id', '=', 'sport_subsections.sport_section_id')
            ->orderBy('sport_sections.sort_order')
            ->orderBy('sport_subsections.sort_order')
            ->select('sport_subsections.*')
            ->get();

        $index    = $ordered->search(fn ($s) => $s->id === $subsection->id);
        $previous = $index > 0 ? $ordered[$index - 1] : null;
        $next     = $ordered[$index + 1] ?? null;

        return view('home.ekho-sport-show', compact('subsection', 'previous', 'next'));
    }



}
