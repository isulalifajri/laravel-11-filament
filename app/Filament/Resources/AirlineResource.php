<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Airline;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AirlineResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AirlineResource\RelationManagers;

class AirlineResource extends Resource
{
    protected static ?string $model = Airline::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('logo')
               ->image()
               ->directory('airlines')
               ->required()
               ->columnSpan(2),
               TextInput::make('code')->required(),
               TextInput::make('name')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo'),
                TextColumn::make('code'),
                TextColumn::make('name'),
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
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListAirlines::route('/'),
            'create' => Pages\CreateAirline::route('/create'),
            'edit' => Pages\EditAirline::route('/{record}/edit'),
        ];
    }
}
