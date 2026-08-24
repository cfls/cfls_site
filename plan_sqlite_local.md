# Plan: Implementación Local SQLite en NativePHP 4

## Objetivo
Preparar el backend (API Laravel) para que la app NativePHP 4 pueda sincronizar datos a SQLite local
y funcionar offline. La app tiene modelos locales: `Syllabus`, `Theme`, `VideoCloudinary`, `Question`,
`Spelling`, `Word`, `Letter`. Solo necesita API para quiz results y loadQuizCounts.

---

## Estado Actual — Sync Endpoints ya implementados

| Endpoint                      | Estado | Cursor compuesto | Limit | Notas |
|-------------------------------|--------|-----------------|-------|-------|
| `GET /v1/syllabus/sync`       | ✅      | ❌              | ❌    | Falta `desde_id` |
| `GET /v1/themes/sync`         | ✅      | ❌              | ❌    | Falta `type` field y `desde_id` |
| `GET /v1/questions/sync`      | ✅      | ✅              | ✅    | Completo |
| `GET /v1/videos/sync`         | ✅      | ❌              | ❌    | Falta `desde_id` |
| `GET /v1/spell/sync`          | ✅      | ❌              | ❌    | Falta `desde_id` |
| `GET /v1/letters/sync`        | ✅      | ❌              | ❌    | Falta `desde_id` |
| `GET /v1/words/sync`          | ✅      | ❌              | ❌    | Falta `desde_id` |

---

## LO QUE FALTA — Prioridad Alta

### 1. Endpoint: `GET /v1/quiz-results/{user_id}/sync` (PULL)
**Problema:** Si el usuario reinstala la app o cambia de dispositivo, pierde todo su progreso local.
El app no puede saber qué temas ya completó.

**Qué hace:** Devuelve todos los quiz results de un usuario con cursor incremental.

```php
// Agregar en QuizResultController
public function sync($userId, Request $request)
{
    $limit = min((int) $request->query('limit', 500), 1000);
    $desde = $request->filled('desde') ? $request->date('desde') : null;
    $desdeId = (int) $request->query('desde_id', 0);

    $query = QuizResult::where('user_id', $userId);

    if ($desde) {
        $query->where(function ($q) use ($desde, $desdeId) {
            $q->where('updated_at', '>', $desde)
              ->orWhere(function ($q) use ($desde, $desdeId) {
                  $q->where('updated_at', $desde)
                    ->where('id', '>', $desdeId);
              });
        });
    }

    $total = $query->count();
    $items = $query->orderBy('updated_at')->orderBy('id')->limit($limit)->get([
        'id', 'user_id', 'syllabus', 'theme', 'theme_variant', 'type', 'score', 'played_at', 'updated_at',
    ]);

    return response()->json([
        'data'          => $items,
        'total'         => $total,
        'has_more'      => $total > $limit,
        'servidor_hora' => now()->toIso8601String(),
    ]);
}
```

**Ruta a agregar en `routes/api.php`:**
```php
Route::get('/quiz-results/{user_id}/sync', [QuizResultController::class, 'sync']);
```

---

### 2. Endpoint: `POST /v1/quiz-results/batch` (PUSH offline results)
**Problema:** Cuando el usuario juega sin conexión, los resultados se guardan en SQLite local.
Al reconectarse, necesita subir todos los resultados de golpe (no uno por uno).

**Qué hace:** Recibe un array de quiz results y los inserta ignorando duplicados.

```php
// Agregar en QuizResultController
public function batch(Request $request)
{
    $results = $request->input('results', []);

    $saved = 0;
    foreach ($results as $item) {
        // Solo guardar si no existe ya (evitar duplicados)
        $exists = QuizResult::where([
            'user_id'       => $item['user_id'],
            'syllabus'      => $item['syllabus'],
            'theme'         => $item['theme'],
            'theme_variant' => $item['theme_variant'] ?? 'principal',
            'type'          => $item['type'],
        ])->exists();

        if (!$exists) {
            QuizResult::create([
                'user_id'       => $item['user_id'],
                'syllabus'      => $item['syllabus'],
                'theme'         => $item['theme'],
                'theme_variant' => $item['theme_variant'] ?? 'principal',
                'type'          => $item['type'],
                'score'         => $item['score'],
                'played_at'     => $item['played_at'] ?? now()->toDateString(),
            ]);
            $saved++;
        }
    }

    return response()->json(['saved' => $saved, 'total' => count($results)]);
}
```

**Ruta a agregar en `routes/api.php`:**
```php
Route::post('/quiz-results/batch', [QuizResultController::class, 'batch']);
```

---

### 3. Corregir `getQuizResultForTopic` en QuizResult model
**Problema:** El comentario en el modelo dice "no funciona igual en todos los drivers".
El `COUNT(DISTINCT ...)` sobre dos columnas usando `DB::raw` no es portable.

**Archivo:** `app/Models/QuizResult.php` línea 70-77

**Corrección:**
```php
public static function getQuizResultForTopic($userId, $slug, $type)
{
    return self::where('user_id', $userId)
        ->where('syllabus', $slug)
        ->where('type', $type)
        ->selectRaw('COUNT(DISTINCT CONCAT(theme, ":", COALESCE(theme_variant, "principal"))) as count')
        ->value('count') ?? 0;
}
```

Esto cuenta pares únicos `theme + theme_variant` para saber cuántos temas completó el usuario
por tipo de juego (lo que usa `loadQuizCounts` en SyllabusGames.php).

---

### 4. Agregar campo `type` en ThemeController::sync
**Problema:** El campo `type` (valores: `principal` | `pour_en_savoir_plus`) no se devuelve
en el sync de temas. La app necesita este campo para saber si un tema tiene sub-tema
"pour en savoir plus" y mostrar las cards correctas en Options.php.

**Archivo:** `app/Http/Controllers/Api/V1/ThemeController.php` línea 120-128

**Corrección:**
```php
$items = $query->orderBy('updated_at')->get([
    'id', 'title', 'slug', 'syllabu_id', 'image', 'type', 'status', 'updated_at',
]);
```
Agregar `'type'` al array de campos.

---

## LO QUE FALTA — Prioridad Media

### 5. Agregar cursor compuesto (`desde_id`) a todos los sync endpoints

Los endpoints de sync usan solo `updated_at` para el cursor. Si múltiples registros tienen
el mismo `updated_at` justo en el límite de una página, pueden perderse registros.
El endpoint de Questions (`/v1/questions/sync`) ya lo hace bien — los demás deben seguir
el mismo patrón.

**Archivos a modificar:**
- `ThemeController::sync` — agregar `desde_id`, `limit`, y cursor compuesto
- `SyllabusController::sync` — agregar `desde_id`, `limit`, y cursor compuesto
- `VideoController::sync` — agregar `desde_id`, `limit`, y cursor compuesto
- `SpellController::sync` — agregar `desde_id`, `limit`, y cursor compuesto
- `LettersController::sync` — agregar `desde_id`, `limit`, y cursor compuesto
- `WordController::sync` — agregar `desde_id`, `limit`, y cursor compuesto

**Patrón a aplicar (igual que QuizController::sync):**
```php
public function sync(Request $request)
{
    $limit   = min((int) $request->query('limit', 500), 1000);
    $desde   = $request->filled('desde') ? $request->date('desde') : null;
    $desdeId = (int) $request->query('desde_id', 0);

    $query = <Model>::query();

    if ($desde) {
        $query->where(function ($q) use ($desde, $desdeId) {
            $q->where('updated_at', '>', $desde)
              ->orWhere(function ($q) use ($desde, $desdeId) {
                  $q->where('updated_at', $desde)
                    ->where('id', '>', $desdeId);
              });
        });
    }

    $total = $query->count();
    $items = $query->orderBy('updated_at')->orderBy('id')->limit($limit)->get([/* campos */]);

    return response()->json([
        'data'          => $items,
        'total'         => $total,
        'has_more'      => $total > $limit,
        'servidor_hora' => now()->toIso8601String(),
    ]);
}
```

---

### 6. Endpoint: `GET /v1/dictionnaire/sync` (PULL)
**Problema:** El diccionario no tiene endpoint de sync. Si la app quiere tener el diccionario
offline, no puede sincronizarlo.

**Archivo:** `app/Http/Controllers/Api/V1/DictionaryController.php`

**Agregar método `sync`** siguiendo el mismo patrón con cursor compuesto.

**Ruta a agregar:**
```php
Route::get('/dictionnaire/sync', [DictionaryController::class, 'sync']);
```

---

## LO QUE FALTA — Prioridad Baja

### 7. Endpoint: `GET /v1/memory-game/sync`
**Problema:** MemoryGameController no tiene endpoint de sync. Para jugar el juego de memoria
offline, la app necesita los datos localmente.

**Ruta a agregar:**
```php
Route::get('/memory-game/sync', [MemoryGameController::class, 'sync']);
```

### 8. Endpoint: `GET /v1/video-quiz/sync`
**Problema:** VideoQuizItemController no tiene endpoint de sync.

**Ruta a agregar:**
```php
Route::get('/video-quiz/sync', [VideoQuizItemController::class, 'sync']);
```

---

## Resumen de cambios en `routes/api.php`

```php
// Agregar en el grupo prefix('v1'):
Route::get('/quiz-results/{user_id}/sync', [QuizResultController::class, 'sync']);
Route::post('/quiz-results/batch', [QuizResultController::class, 'batch']);
Route::get('/dictionnaire/sync', [DictionaryController::class, 'sync']);
Route::get('/memory-game/sync', [MemoryGameController::class, 'sync']);
Route::get('/video-quiz/sync', [VideoQuizItemController::class, 'sync']);
```

---

## Orden de implementación recomendado

1. **Corregir `getQuizResultForTopic`** — bug silencioso que afecta `loadQuizCounts` hoy
2. **Agregar campo `type` en ThemeController::sync** — 1 línea, impacto inmediato
3. **Endpoint `GET /v1/quiz-results/{user_id}/sync`** — crítico para restore de progreso
4. **Endpoint `POST /v1/quiz-results/batch`** — crítico para modo offline
5. **Cursor compuesto en todos los sync** — confiabilidad de sync
6. **Endpoints dictionnaire, memory-game, video-quiz sync** — completitud offline

---

## Confirmación: Opción A para vídeos en local (de syllabus_theme.md §4)

`syllabus_theme.md` dejaba sin resolver si el modelo local distingue los grupos de vídeos
por columna `type` (Opción A) o por relación `annexes()` separada (Opción B).

**La respuesta es Opción A.** El modelo `Theme` del server ya tiene:

```php
public function videos(): HasMany      // todos los activos (sin filtro de type)
public function mainVideos(): HasMany  // where type = 'principal'
public function annexes(): HasMany     // where type != 'principal'
```

El campo `type` existe en `video_themes_cloudinary` y `/v1/videos/sync` ya lo devuelve.
En local, el código de `SyllabusTheme::load()` debe usar:

```php
$videos = $theme->videos()->get();   // todos activos del theme local
$this->principalVideos = $this->mapVideos($videos, 'principal');
$this->plusVideos      = $this->mapVideos($videos, 'pour_en_savoir_plus');
```

No se necesita `annexes()` separado en la BD local — solo la columna `type`.

---

## Nota sobre NativePHP 4 vs NativePHP 3

El documento menciona que en NativePHP 4 los modelos `Syllabus`, `Theme`, `VideoCloudinary`
existen localmente. Por tanto:
- Las rutas `/v1/syllabus/settings/{slug}`, `/v1/sections/{slug}`, etc. ya NO son necesarias
  para la app en modo offline — se reemplazan por queries locales SQLite.
- Solo se necesita llamada remota para: autenticación, quiz results (lectura y escritura),
  feedback, suscripciones y verify-codes.
- El sync initial (primera instalación o reinstalación) sigue necesitando todos los endpoints
  de sync listados arriba.
