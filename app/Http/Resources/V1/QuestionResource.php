<?php

namespace App\Http\Resources\V1;

use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $publicId = null;

        if (isset($this->video->url)) {
            $publicId = Str::before(
                Str::after($this->video->url, '/upload/'),
                '.mp4'
            );
        }

        // Instancia del SDK PHP Core
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);

        return [
            'id'            => $this->id,
           // 'slug'          => $this->syllabus->slug ?? '',
            'theme'         => $this->theme->title ?? '',
            'question_text' => $this->question_text ?? '',
            'type'          => $this->type ?? '',
            'video'         => $publicId
                ? $cloudinary->video($publicId, [
                    'transformation' => [
                        ['quality' => 'auto'],
                        ['fetch_format' => 'auto'],
                        ['video_codec' => 'auto']
                    ]
                ])->toUrl()
                : null,
            'options'       => $this->formatOptions(),
            'answer'        => $this->answer ?? '',
            'status'        => $this->status ?? false,
        ];
    }

    private function formatOptions()
    {


        // Normalizar
        $options = is_array($this->options)
            ? $this->options
            : json_decode($this->options, true);



        if ($this->type === 'choice') {
            return $options;
        }

        // Si es otro tipo, mapear el video
        return  $options;
    }
}
