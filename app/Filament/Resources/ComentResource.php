<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComentResource\Pages;
use App\Filament\Resources\ComentResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Coment;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComentResource extends Resource
{
    protected static ?string $model = Coment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')->relationship('user','name')->searchable()->preload(),
                TextInput::make('comment'),
                MorphToSelect::make('commentable')
                ->label('Comment Type')
                ->types([
                    Type::make(Post::class)->titleAttribute('title'),
                    Type::make(User::class)->titleAttribute('email'),
                    Type::make(Coment::class)->titleAttribute('id'),
                ])->searchable()->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('commentable_type'),
                TextColumn::make('commentable_id'),
                TextColumn::make('comment'),
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
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComents::route('/'),
            'create' => Pages\CreateComent::route('/create'),
            'edit' => Pages\EditComent::route('/{record}/edit'),
        ];
    }
}
