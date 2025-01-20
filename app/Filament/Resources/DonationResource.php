<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Filament\Resources\DonationResource\RelationManagers;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Category;
use Filament\Forms\Components\Select;
use App\Filament\Resources\DonationResource\Widgets\DonationsOverview;
use App\Filament\Resources\DonationResource\Widgets\DonationsChart;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Donations Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('charity_id')
                    ->label('Charity')
                    ->relationship('charity', 'title')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->required()
                    ->numeric()
                    ->prefix('KES'), // Currency symbol
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required(),
                    
               
                    Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required(),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->placeholder('Optional notes about the donation'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('charity.title')
                    ->label('Charity')
                    ->sortable()
                    ->searchable(),
          
                    Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
            Tables\Columns\TextColumn::make('amount')
                ->label('Amount')
                ->sortable()
                ->money('KES'),
            Tables\Columns\TextColumn::make('date')
                ->label('Date')
                ->date(),
                
            Tables\Columns\TextColumn::make('notes')
                ->label('Notes')
                ->limit(50),
        ])
        ->filters([
            // Add filters if necessary
        ])
        ->defaultSort('date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getWidgets():array{
        return [
            DonationsOverview::class,
            DonationsChart::class,
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
