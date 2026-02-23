<?php

namespace App\Http\Controllers;

use App\Models\Syllabu;
use App\Models\Theme;
use App\Models\VerifyCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SyllabusController extends Controller
{
    /**
     * Slugs válidos que existen en Wix para el redireccionamiento especial.
     */
    private const WIX_VALID_SLUGS = [
        'ue1-themes',
        'ue1-themes-1',
        'ue1-themes-3',
        'ue1-themes-4',
        'ue1-themes-5',
        'ue1-themes-6',
        'ue1-themes-7',
        'ue1-themes-8',
        'ue1-themes-9',
        'ue1-themes-10',
        'ue1-themes-11',
    ];

    /** Lista de syllabus activos. */
    public function index()
    {
        $syllabus = Syllabu::all();
        return view('syllabus.index', compact('syllabus'));
    }

    /** Página de un syllabus (lista de temas). */
    public function syllabu(string $slug)
    {
        if ($redirect = $this->ensureActiveUser()) {
            return $redirect;
        }

        $user = Auth::user();

        // Verifica código activo para este slug
        $hasAccess = VerifyCode::where([
            'user_id' => $user->id,
            'theme'   => $slug,
            'active'  => 1
        ])->exists();

        if (!$hasAccess) {
            return redirect()->route('code-livre', ['slug' => $slug]);
        }

        // Base query
        $query = Syllabu::where('slug', $slug);

        // Si no es admin, solo activos
        if ($user->role !== 'admin') {
            $query->where('status', 1);
        }

        $syllabu = $query->firstOrFail();

        // Cargar temas según rol
        $themes = $syllabu->themes()
            ->when($user->role !== 'admin', function ($q) {
                $q->where('status', 1);
            })
            ->get();

        return view('syllabus.theme', compact('syllabu', 'slug', 'themes'));
    }

    /** Formulario para ingresar código de libro. */
    public function codelivre(string $slug)
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $redirect;
        }

        return view('syllabus.codelivre', compact('slug'));
    }

    /** Guarda/valida el código de libro. */
    public function store(Request $request)
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $redirect;
        }

        $user = Auth::user();

        $data = $request->validate([
            'code_livre' => ['required', 'string'],
            'slug'       => ['required', 'string'],
        ]);

        $slug = $data['slug'];
        $code = $data['code_livre'];

        $verifyCode = VerifyCode::where('code', $code)
            ->where('active', 0)
            ->first();

        if (!$verifyCode) {
            return back()
                ->withErrors(['error' => 'Code de livre invalide'])
                ->withInput(['slug' => $slug]);
        }

        VerifyCode::updateOrCreate(
            ['code' => $code],
            [
                'user_id'=> $user->id,
                'active' => 1,
                'theme'  => $slug,
            ]
        );

        return redirect()->route('syllabus.slug', ['slug' => $slug]);
    }

    /** Página de un tema específico del syllabus. */
    public function theme(string $slug, string $theme, ?string $code = null)
    {
        if ($redirect = $this->ensureActiveUser()) {
            return $redirect;
        }

        $user = Auth::user();

        // 🔐 Validar acceso solo si NO es admin
        if ($user->role !== 'admin') {

            $hasAccess = VerifyCode::where([
                'user_id' => $user->id,
                'theme'   => $slug,
                'active'  => 1
            ])->exists();

            if (!$hasAccess) {
                return redirect()->route('code-livre', ['slug' => $slug]);
            }
        }

        // 📚 Base query syllabus
        $syllabuQuery = Syllabu::where('slug', $slug);

        if ($user->role !== 'admin') {
            $syllabuQuery->where('status', 1);
        }

        $syllabu = $syllabuQuery->firstOrFail();

        // 📂 Base query theme
        $themeQuery = $syllabu->themes()
            ->where('slug', $theme);

        if ($user->role !== 'admin') {
            $themeQuery->where('status', 1);
        }

        $themeModel = $themeQuery->firstOrFail();

        // 🎥 Videos (admin ve todo)
        $videosQuery = DB::table('video_themes_cloudinary')
            ->where([
                'syllabu_id' => $syllabu->id,
                'theme_id'   => $themeModel->id,
            ])
            ->orderBy('title');

        if ($user->role !== 'admin') {
            $videosQuery->where('active', 1);
        }

        $videos = $videosQuery
            ->get(['id', 'url as url_video', 'title'])
            ->toArray();

        return view('syllabus.show', compact('syllabu', 'themeModel', 'videos'));
    }



    /* ======================== helpers ======================== */

    private function ensureLoggedIn()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return null;
    }

    private function ensureActiveUser()
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $redirect;
        }

        $user = Auth::user();
        if ((int) ($user->is_active ?? 0) !== 1) {
            return redirect()->route('verification.notice');
        }
        return null;
    }
}
