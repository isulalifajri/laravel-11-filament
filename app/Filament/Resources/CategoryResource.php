<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;
use Filament\Forms\Set;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Post Categories';

    protected static ?string $navigationGroup = "Blog";

    // protected static ?int $navigationSort = 2;

    protected static ?string $navigationParentItem = 'Articles';

    protected static bool $shouldSkipAuthorization = true; //skip authorization

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('name')
                // ->required()->live(debounce:500)
                // ->afterStateUpdated(function (callable $set, $state){
                //     $slug = Str::slug($state);
                //     $originalSlug = $slug;
                //     $i = 1;
                //     while (Category::where('slug',$slug)->exists()){
                //         $slug = $originalSlug . '-' . $i++;
                //     }
                //     $set('slug',$slug);
                // }),

                TextInput::make('name')->required()->minLength(1)->maxLength(150)
                ->live(onBlur:true)
                ->afterStateUpdated(function (string $operation, $state, Set $set,Category $category){
                    // dump($operation); //edit,create
                    if($operation == 'edit'){
                        return;
                    }
                    $set('slug', Str::slug($state));
                    // dump($category);
                }),
                TextInput::make('slug')->unique(Category::class,'slug', ignoreRecord:true)->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('slug'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            PostsRelationManager::class
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
