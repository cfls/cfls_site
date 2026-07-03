<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Syllabu;
use App\Http\Resources\V1\SyllabusResource;
use Illuminate\Http\Request;

class SyllabusController extends Controller
{
    /**
     * Mostrar todos los syllabu
     */
    public function index()
    {
        $syllabus = Syllabu::query()
            //->where('status', 1)
            ->select('id','title','slug','image','link','status')
            ->orderBy('id')
            ->get();



        return SyllabusResource::collection($syllabus);
    }

    /**
     * Guardar un nuevo syllabu
     */
    public function store(Request $request)
    {
        $syllabu = Syllabu::create($request->all());

        return new SyllabusResource($syllabu);
    }

    /**
     * Mostrar un syllabu específico
     */
    public function show($syllabu)
    {

        $syllabu = Syllabu::findOrFail($syllabu);
        return new SyllabusResource($syllabu);
    }

    /**
     * Actualizar un syllabu específico
     */
    public function update(Request $request, Syllabu $syllabu)
    {
        $syllabu->update($request->all());

        return new SyllabusResource($syllabu);
    }

    /**
     * Eliminar un syllabu específico
     */
    public function destroy(Syllabu $syllabu)
    {
        $syllabu->delete();

        return response()->noContent();
    }

    public function settings($slug)
    {


        $syllabu = Syllabu::where('slug', $slug)->firstOrFail();

        return new SyllabusResource($syllabu);
    }
}
