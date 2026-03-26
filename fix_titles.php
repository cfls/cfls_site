<?php
$items = DB::table('video_themes_cloudinary')
    ->where('syllabu_id', 3)
    ->orderBy('title')
    ->get(['id', 'title']);

foreach ($items as $item) {
    $words = explode(' ', $item->title);
    $newWords = array_map(function ($word, $index) {
        if ($index === 0) return $word;
        if (str_contains($word, '-')) return $word;
        return lcfirst($word);
    }, $words, array_keys($words));

    $newTitle = implode(' ', $newWords);

    if ($newTitle !== $item->title) {
        echo "'{$item->title}' → '{$newTitle}'\n";
        DB::table('video_themes_cloudinary')
            ->where('id', $item->id)
            ->update(['title' => $newTitle]);
    }
}
