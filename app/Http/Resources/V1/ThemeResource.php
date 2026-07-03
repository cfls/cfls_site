<?php

namespace App\Http\Resources\V1;

use App\Models\VideoTheme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // ThemeResource.php

    public function toArray(Request $request): array
    {

        $subtheme = VideoTheme::where('theme_id', $this->id)->first();

        return [
            'type' => 'themes',
            'id'   => $this->id,
            'attributes' => [
                'title'        => $this->title,
                'slug'         => $this->slug,
                'slug_syllabu' => $this->syllabus->slug,
                'image'        => asset('storage/' . $this->image),
                'type'         => $subtheme ? $subtheme->type : null,
                'videos'       => VideoResource::collection($this->whenLoaded('mainVideos')), // ✅
                'annexes'      => VideoResource::collection($this->whenLoaded('annexes')),    // ✅
            ]
        ];
    }
}
