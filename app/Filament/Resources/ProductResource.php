<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationLabel = 'Produits';
    protected static ?string $label = 'Produit';
    protected static ?string $navigationGroup = 'Boutique';
    protected static ?int $navigationSort = 3;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true) // Updates the slug as you type or on blur
                    ->afterStateUpdated(function (callable $set, $state) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name', function ($query) {
                        $query->where('type', 'product');
                    })
                    ->required()
                    ->reactive(), // 👈 important for cascading

                Forms\Components\Select::make('sub_category_id')
                    ->label('Sous-catégorie')
                    ->options(function (callable $get) {
                        $categoryId = $get('category_id');

                        if (!$categoryId) {
                            return []; // No category selected yet
                        }

                        return \App\Models\SubCategory::where('category_id', $categoryId)
                            ->pluck('name', 'id');
                    })
                    ->required()
                    ->disabled(fn (callable $get) => !$get('category_id'))
                    ->reactive(),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('€'),
                Forms\Components\TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                Repeater::make('images')
                    ->relationship('images')
                    ->schema([
                        FileUpload::make('image_path')->image()->directory('products'),
                    ])
                    ->label('Product Images')
                    ->columns(1),
                Forms\Components\Repeater::make('options')
                    ->relationship('options')
                    ->schema([
                        Forms\Components\TextInput::make('option_name')
                            ->label('Nom de l\'option')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('total_weight')
                            ->label('Poids total (g)')

                    ])
                    ->label('Options du produit')
                    ->columns(1),
                Forms\Components\TextInput::make('video')
                    ->maxLength(255),
                Forms\Components\TextInput::make('weight')
                    ->numeric(),
                Forms\Components\Select::make('status')
                    ->label('Statut')
                    
                    ->options([
                        3 => 'Épuisé',
                        2 => 'Nouveau',
                        1 => 'Actif',
                        0 => 'Inactif',
                    ])
                    ->default(1) // Puedes cambiar a 2 o 0 según lo que necesites por defecto
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subCategory.name')
                    ->label('Sous-catégorie')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(fn ($state) => match($state) {
                        3 => 'Épuisé',
                        2 => 'Nouveau',
                        1 => 'Actif',
                        0 => 'Inactif',
                        default => 'Inconnu',
                    })
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        3 => 'danger',
                        2 => 'success',
                        1 => 'primary',
                        0 => 'secondary',
                    }),

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
