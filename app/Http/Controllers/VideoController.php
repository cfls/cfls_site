<?php

namespace App\Http\Controllers;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Search\SearchApi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function getAllVideos()
    {

        // Configurar Cloudinary globalmente
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true]
        ]);
        // Replace 'your-folder-name' with your actual folder name
        $folderPath = "Syllabus_1_2026/THEME 1";

        $search = new SearchApi();



        $response = $search->expression("resource_type:video AND folder:\"$folderPath\"")
            ->sortBy('created_at','desc')
            ->maxResults(250)
            ->execute();
        foreach ($response['resources'] as $video) {

            $title = $video['display_name'] ?? $video['public_id'];
            $slug  = Str::slug($title);

            DB::table('video_themes_cloudinary_version')->updateOrInsert(
                ['slug' => $slug], // condición única
                [
                    'title'      => $title,
                    'theme_id'   => 1,
                    'syllabu_id' => 1,
                    'url'        => $video['url'],
                    'active'     => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        return response()->json($response);
    }
}
