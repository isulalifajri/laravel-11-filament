<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\PromoCode;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PromoCodeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PromoCodeResource\RelationManagers;
use Filament\Tables\Columns\ToggleColumn;

class PromoCodeResource extends Resource
{
    protected static ?string $model = PromoCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('code')
               ->required(),
               Select::make('discount_type')
               ->required()
               ->options([
                'fixed' => 'Fixed',
                'percentage' => 'Percentage'
               ]),
               TextInput::make('discount')
               ->required()
               ->numeric()
               ->minValue(0),
               DateTimePicker::make('valid_until')
               ->required(),
               Toggle::make('is_used')
               ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('discount_type'),
                TextColumn::make('discount')
                ->formatStateUsing(fn(PromoCode $record): string => match ($record->discount_type) {
                    'fixed' => 'Rp. ' . number_format($record->discount, 0, ',',',') ,
                    'percentage' => $record->discount . '%',
                }),
                ToggleColumn::make('is_used'),
                TextColumn::make('valid_until')
                ->dateTime(),
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
            'index' => Pages\ListPromoCodes::route('/'),
            'create' => Pages\CreatePromoCode::route('/create'),
            'edit' => Pages\EditPromoCode::route('/{record}/edit'),
        ];
    }
}
