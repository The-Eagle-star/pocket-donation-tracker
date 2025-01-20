<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CharityResource\Pages;
use App\Filament\Resources\CharityResource\RelationManagers;
use App\Models\Charity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CharityResource extends Resource
{
    protected static ?string $model = Charity::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Donations Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Cause/Charity Name')
                    ->required(),
                Forms\Components\FileUpload::make('logo')
                ->label('Logo Image')
                ->image() // Restricts uploads to image files
                ->directory('images') // Directory where the image will be stored
                ->required()
                ->maxSize(12500) // Maximum size in KB (1MB)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']) // Allowed file types
                ->hint('Upload a featured image (JPEG, PNG, max 1MB)'),
                Forms\Components\Textarea::make('short_description')
                    ->label('Short Description')
                    ->required(),
                Forms\Components\TextInput::make('total_donations')
                    ->label('Goal')
                    ->numeric(), // Total donations should be updated programmatically
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->getStateUsing(function ($record) {
                        // Ensure the path is accessible via the public URL
                        $path = $record->logo;

                        // If the path is not empty, generate the correct URL
                        return url('storage/' . $path);
                    })
                    ->width(150) // Set the image width (adjust as needed)
                    ->height(150) // Set the image height (adjust as needed)
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Cause/Charity Name')
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\ImageColumn::make('logo')
                //     ->label('Logo/Icon'),
                Tables\Columns\TextColumn::make('short_description')
                    ->label('Short Description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('total_donations')
                    ->label('Goal')
                    ->money('KES'), // Adjust the currency as needed
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->defaultSort('title', 'asc');
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
            'index' => Pages\ListCharities::route('/'),
            'create' => Pages\CreateCharity::route('/create'),
            'edit' => Pages\EditCharity::route('/{record}/edit'),
        ];
    }
}
