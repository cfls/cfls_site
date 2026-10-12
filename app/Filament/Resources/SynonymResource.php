<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SynonymResource\Pages;
use App\Models\Synonym;
use App\Models\VideoTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SynonymResource extends Resource
{
    protected static ?string $model = VideoTheme::class;

    protected static ?string $navigationLabel = 'Synonymes';
    protected static ?string $label = 'Synonyme';
    protected static ?string $pluralLabel = 'Synonymes';
    protected static ?string $navigationGroup = 'Dictionnaire';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'synonymes';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function stripExpr(string $col = 'title'): string
    {
        return "CASE WHEN {$col} REGEXP '[(][0-9]+' AND LOCATE(' (', {$col}) > 1 "
            . "THEN TRIM(SUBSTRING({$col}, 1, LOCATE(' (', {$col}) - 1)) "
            . "ELSE {$col} END";
    }

    /**
     * stripExpr variant that qualifies the column with the outer table name.
     * Required when the expression is used inside a correlated subquery so MySQL
     * doesn't resolve the bare column name to the subquery's alias (vt2) instead.
     */
    public static function stripExprOuter(): string
    {
        $col = '`video_themes_cloudinary`.`title`';
        return "CASE WHEN {$col} REGEXP '[(][0-9]+' AND LOCATE(' (', {$col}) > 1 "
            . "THEN TRIM(SUBSTRING({$col}, 1, LOCATE(' (', {$col}) - 1)) "
            . "ELSE {$col} END";
    }

    /**
     * Base query — used by Filament to resolve individual records (Edit page).
     * No canonical filter here so any VideoTheme ID resolves correctly.
     * The table's canonical + synonym filter is applied in ListSynonyms::getTableQuery().
     */
    public static function getEloquentQuery(): Builder
    {
        return VideoTheme::query()
            ->where('active', true)
            ->with('synonyms');
    }

    /**
     * Returns all VideoTheme IDs that share the same display_title group as $record.
     */
    public static function getGroupIds(VideoTheme $record): array
    {
        $strip1 = self::stripExpr('title');

        return VideoTheme::query()
            ->where('active', true)
            ->whereRaw("({$strip1}) = ?", [$record->display_title])
            ->pluck('id')
            ->toArray();
    }

    /**
     * Distinct display_title → canonical MIN(id) mapping for the Select field.
     */
    public static function getWordOptions(): array
    {
        $expr = self::stripExpr('title');

        return VideoTheme::query()
            ->selectRaw("MIN(id) as id, ({$expr}) as display_title")
            ->where('active', true)
            ->groupByRaw("({$expr})")
            ->orderByRaw("({$expr})")
            ->get()
            ->pluck('display_title', 'id')
            ->toArray();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('video_theme_id')
                    ->label('Mot principal')
                    ->options(self::getWordOptions())
                    ->searchable()
                    ->required()
                    ->visibleOn('create')
                    ->columnSpanFull(),

                Forms\Components\Placeholder::make('word_display')
                    ->label('Mot principal')
                    ->content(fn ($record) => $record?->display_title ?? '—')
                    ->visibleOn('edit')
                    ->columnSpanFull(),

                Forms\Components\Repeater::make('synonyms')
                    ->label('Synonymes')
                    ->schema([
                        Forms\Components\TextInput::make('word')
                            ->label('Synonyme')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->addActionLabel('+ Ajouter un synonyme')
                    ->minItems(1)
                    ->columnSpanFull()
                    ->reorderableWithButtons()
                    ->defaultItems(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        $sortByTitle = fn (Builder $query, string $direction): Builder =>
            $query->orderByRaw('(' . self::stripExpr('title') . ") {$direction}");

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Mot principal')
                    ->getStateUsing(fn (VideoTheme $record): string => $record->display_title)
                    ->searchable()
                    ->sortable(query: $sortByTitle),

                Tables\Columns\TextColumn::make('synonyms_list')
                    ->label('Synonymes')
                    ->getStateUsing(
                        fn (VideoTheme $record): string =>
                            $record->synonyms->pluck('word')->sort()->join(' · ') ?: '—'
                    )
                    ->wrap(),

                Tables\Columns\TextColumn::make('synonyms_count')
                    ->label('Quantité')
                    ->getStateUsing(fn (VideoTheme $record): int => $record->synonyms->count()),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('delete')
                    ->label('Supprimer')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Supprimer les synonymes')
                    ->modalDescription('Voulez-vous supprimer tous les synonymes pour ce mot ? Cette action est irréversible.')
                    ->action(function (VideoTheme $record): void {
                        Synonym::whereIn('video_theme_cloudinary_id', self::getGroupIds($record))->delete();
                        Notification::make()->title('Synonymes supprimés')->success()->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('delete_synonyms')
                        ->label('Supprimer les synonymes')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records): void {
                            foreach ($records as $record) {
                                Synonym::whereIn(
                                    'video_theme_cloudinary_id',
                                    self::getGroupIds($record)
                                )->delete();
                            }
                            Notification::make()->title('Synonymes supprimés')->success()->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateHeading('Aucun synonyme')
            ->emptyStateDescription('Commencez par ajouter des synonymes à un mot du dictionnaire.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSynonyms::route('/'),
            'create' => Pages\CreateSynonym::route('/create'),
            'edit'   => Pages\EditSynonym::route('/{record}/edit'),
        ];
    }
}
