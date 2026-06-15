<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;

use App\Models\Vimeo;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VimeoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VimeoResource\RelationManagers;

class VimeoResource extends Resource
{
    protected static ?string $model = Vimeo::class;
    protected static ?string $navigationLabel = 'Video';
    protected static ?string $label = 'Video';
    protected static ?string $navigationGroup = 'Vidéos & Mots Croisés';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('url_cloudinary')
                    ->required()
                    ->label('URL Cloudinary'),
                Forms\Components\TextInput::make('url_cloudinary_subtitled')
                    ->required()
                    ->label('URL Cloudinary avec sous-titres'),
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
                        ->required()
                        ->maxLength(255)
                        ->unique(table: Vimeo::class, column: 'slug', ignorable: fn ($record) => $record)
                        ->validationMessages([
                            'unique' => 'Cette Slug est déjà utilisée. Veuillez en choisir un autre.',
                        ]),
                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->maxSize(1024)
                    ->acceptedFileTypes(['image/jpeg', 'image/png']),
                Forms\Components\Toggle::make('status')
                    ->label('Statut')
                    ->default(true)
                    ->required(),
                    Forms\Components\Select::make('categories_id')
                    ->label('Catégorie')
                    ->options(Category::all()->pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                     ->label('Statut')
                    ->boolean(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),

            ])
            ->filters([
                //
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
            'index' => Pages\ListVimeos::route('/'),
            'create' => Pages\CreateVimeo::route('/create'),
            'edit' => Pages\EditVimeo::route('/{record}/edit'),
        ];
    }
}
