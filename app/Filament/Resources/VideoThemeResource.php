<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoThemeResource\Pages;
use App\Filament\Resources\VideoThemeResource\RelationManagers;
use App\Models\Syllabu;
use App\Models\VideoTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoThemeResource extends Resource
{
    protected static ?string $model = VideoTheme::class;

    protected static ?string $navigationLabel = 'Vidéo Thèmes';
    protected static ?string $label = 'Vidéo Thème';
    protected static ?string $navigationGroup = 'Syllabus';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true) // Updates the slug as you type or on blur
                    ->afterStateUpdated(function (callable $set, $state) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('theme_id')
                    ->label('Theme')
                    ->required()
                    ->options(\App\Models\Theme::all()->pluck('title', 'id')->toArray())
                    ->searchable(),
                Forms\Components\Select::make('syllabu_id')
                    ->label('Syllabus')
                    ->required()
                    ->options(Syllabu::all()->pluck('title', 'id')->toArray())
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('url')
                    ->label('Url Cloudinary')
                    ->maxLength(255),
                Forms\Components\Toggle::make('active')
                    ->label('Statut')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('themes.title')
                    ->label('Thème')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('syllabus.title')
                    ->label('Syllabus')
                    ->numeric()
                    ->sortable(),
//                Tables\Columns\TextColumn::make('url')
//                    ->label('Url Cloudinary')
//                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->label('Statut')
                    ->boolean()
                    ->sortable()


            ])
            ->filters([
                Tables\Filters\Filter::make('syllabu_theme')
                    ->form([
                        Forms\Components\Select::make('syllabu_id')
                            ->label('Syllabus')
                            ->options(
                                Syllabu::all()
                                    ->groupBy('ue')
                                    ->map(fn ($group) => $group->pluck('title', 'id'))
                                    ->toArray()
                            )
                            ->live() // 👈 Reactivo: actualiza el select de temas
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('theme_id', null)), // 👈 Reset theme al cambiar syllabus
                        Forms\Components\Select::make('theme_id')
                            ->label('Thème')
                            ->options(
                                fn (Forms\Get $get) =>
                                \App\Models\Theme::whereHas('videos', function ($query) use ($get) {
                                    $query->when(
                                        $get('syllabu_id'),
                                        fn ($q, $id) => $q->where('syllabu_id', $id)
                                    );
                                })
                                    ->pluck('title', 'id')
                                    ->toArray()
                            )
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['syllabu_id'] ?? null, fn ($q) => $q->where('syllabu_id', $data['syllabu_id']))
                            ->when($data['theme_id'] ?? null,    fn ($q) => $q->where('theme_id',   $data['theme_id']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (!empty($data['syllabu_id'])) {
                            $indicators[] = Tables\Filters\Indicator::make(
                                'Syllabus: ' . (Syllabu::find($data['syllabu_id'])?->title ?? '')
                            )->removeField('syllabu_id');
                        }

                        if (!empty($data['theme_id'])) {
                            $indicators[] = Tables\Filters\Indicator::make(
                                'Thème: ' . (\App\Models\Theme::find($data['theme_id'])?->title ?? '')
                            )->removeField('theme_id');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideoThemes::route('/'),
            'create' => Pages\CreateVideoTheme::route('/create'),
            'edit' => Pages\EditVideoTheme::route('/{record}/edit'),
        ];
    }
}
