<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdventVideoResource\Pages;
use App\Models\AdventVideo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class AdventVideoResource extends Resource
{
    protected static ?string $model = AdventVideo::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationLabel = 'Calendrier de l’Avent';
    protected static ?string $label = 'Vidéo de l’Avent';
    protected static ?string $pluralLabel = 'Calendrier de l’Avent';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')
                    ->label('Année')
                    ->numeric()
                    ->minValue(2024)
                    ->default(fn () => now()->year)
                    ->required()
                    ->live(),

                Forms\Components\Select::make('day')
                    ->label('Jour')
                    ->options(array_combine(range(1, 25), range(1, 25)))
                    ->required()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, Forms\Get $get) => $rule->where('year', $get('year')),
                    )
                    ->validationMessages([
                        'unique' => 'Une vidéo existe déjà pour ce jour et cette année.',
                    ]),

                Forms\Components\TextInput::make('video_url')
                    ->label('URL de la vidéo')
                    ->url()
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->placeholder('https://www.facebook.com/facebook/videos/123456789/')
                    ->helperText('Vidéo ou reel Facebook (…/videos/XXXX, …/reel/XXXX) ou reel Instagram (https://www.instagram.com/reel/XXXX).')
                    ->rules(['regex:/^https:\/\/(www\.)?(facebook\.com\/(.+\/videos\/|reel\/|watch\/?\?v=)|instagram\.com\/(reels?|p|tv)\/)[^\s]+$/'])
                    ->validationMessages([
                        'regex' => 'L’URL doit être une vidéo Facebook ou un reel Instagram.',
                    ]),

                Forms\Components\Toggle::make('is_published')
                    ->label('Publié')
                    ->helperText('Une fois publiée, la case du jour est débloquée sur le site.')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Année')
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->label('Jour')
                    ->sortable(),
                Tables\Columns\TextColumn::make('video_url')
                    ->label('URL')
                    ->limit(60)
                    ->url(fn (AdventVideo $record) => $record->video_url, shouldOpenInNewTab: true)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Publié')
                    ->onColor('success')
                    ->offColor('danger')
                    ->afterStateUpdated(function ($record, $state) {
                        \Filament\Notifications\Notification::make()
                            ->title('Statut mis à jour')
                            ->body("Jour {$record->day} " . ($state ? 'publié' : 'dépublié'))
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort(fn ($query) => $query->orderByDesc('year')->orderBy('day'))
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Année')
                    ->options(fn () => AdventVideo::query()
                        ->distinct()
                        ->orderByDesc('year')
                        ->pluck('year', 'year')
                        ->toArray()
                    ),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Publié'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publier')
                        ->icon('heroicon-o-eye')
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListAdventVideos::route('/'),
            'create' => Pages\CreateAdventVideo::route('/create'),
            'edit' => Pages\EditAdventVideo::route('/{record}/edit'),
        ];
    }
}
