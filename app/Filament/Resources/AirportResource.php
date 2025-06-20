<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Airport;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AirportResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AirportResource\RelationManagers;

class AirportResource extends Resource
{
    protected static ?string $model = Airport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               FileUpload::make('image')
               ->image()
               ->directory('airports')
               ->required()
               ->columnSpan(2),
               TextInput::make('iata_code')
               ->required(),
               TextInput::make('name')
               ->required(),
               TextInput::make('city')
               ->required(),
               TextInput::make('country')
               ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('iata_code'),
                TextColumn::make('name'),
                TextColumn::make('city'),
                TextColumn::make('country'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(), // ⬅️ nampilin softDeletes
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(), // ⬅️ tombol restore
                Tables\Actions\ForceDeleteAction::make(), // ⬅️ tombol hapus permanen
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListAirports::route('/'),
            'create' => Pages\CreateAirport::route('/create'),
            'edit' => Pages\EditAirport::route('/{record}/edit'),
        ];
    }
}
