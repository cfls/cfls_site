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
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
        ]);

        return [
            'id'            => $this->id,
           // 'slug'          => $this->syllabus->slug ?? '',
            'theme'         => $this->theme->title ?? '',
            'question_text' => $this->type === 'yes-no'
                ? $this->cleanQuestionText($this->question_text ?? '')
                : $this->cleanWord($this->question_text ?? ''),
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
            'answer'        => $this->cleanWord($this->answer ?? ''),
            'status'        => $this->status
        ];
    }

private function formatOptions()
{
    $options = is_array($this->options)
        ? $this->options
        : json_decode($this->options, true);

    if (!is_array($options)) {
        return $options;
    }

    if ($this->type === 'match') {
        return array_map(function ($option) {
            return array_merge($option, [
                'word' => $this->cleanWord($option['word'] ?? ''),
            ]);
        }, $options);
    }


    if ($this->type === 'video-choice') {
        return array_map(function ($option) {
            return array_merge($option, [
                'value' => $this->cleanWord($option['value'] ?? ''),
            ]);
        }, $options);
    }

    if ($this->type === 'choice') {
        return array_map(function ($option) {
            return $this->cleanWord($option);
        }, $options);
    }

    return $options;
}

    private function cleanWord(string $word): string
    {
        // Quitar todo lo que está entre paréntesis
        $word = preg_replace('/\s*\(.*?\)/', '', $word);

        // Quitar todo lo que está después del /
        $word = explode('/', $word)[0];

        return trim($word);
    }

    private function cleanQuestionText(string $text): string
    {
        // Busca todo lo que está después de "signifie '" hasta el "' ?"
        return preg_replace_callback("/signifie '(.+)' \?/u", function ($matches) {
            return "signifie '" . $this->cleanWord($matches[1]) . "' ?";
        }, $text);
    }
}
