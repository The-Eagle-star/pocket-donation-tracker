<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\Widgets\CategoryOverview;
use App\Filament\Resources\CategoryResource\Widgets\CategoryChart;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Donations Management';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    { return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label('Category Name')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->label('Category Name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Updated At')
                ->dateTime(),
        ])
        ->filters([
            // Add filters if needed
        ])
        ->defaultSort('name', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets():array{
        return [
            CategoryOverview::class,
            CategoryChart::class,
           
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
