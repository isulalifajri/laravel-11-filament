<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create a Post')
                ->description('create posts over here')
                ->collapsible()
                ->schema([
                    TextInput::make('title')
                    ->required()->live(debounce:1000)
                    ->afterStateUpdated(function (callable $set, $state){
                        $slug = Str::slug($state);
                        $originalSlug = $slug;
                        $i = 1;
                        while (Post::where('slug',$slug)->exists()){
                            $slug = $originalSlug . '-' . $i++;
                        }
                        $set('slug',$slug);
                    }),
                    TextInput::make('slug')->unique(Post::class,'slug', ignoreRecord:true)->readOnly(),
                    Select::make('category_id')
                    ->options(Category::all()->pluck('name','id'))
                    ->label('Category')->required(),
                    ColorPicker::make('color'),
                    MarkdownEditor::make('content')->required()->columnSpanFull(),
                ])->columnSpan(2)->columns(2),

                Group::make()->schema([
                    Section::make("Image")
                    ->collapsible()
                    ->schema([
                        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),
                    ])->columnSpan(1),
                    Section::make('Meta')
                    ->schema([
                        TagsInput::make('tags')->required(),
                        Checkbox::make('published')->required(),
                    ]),
                    // Section::make('Authors')
                    // ->schema([
                    //     Select::make('authors')
                    //     ->label('Co Authors')
                    //     ->relationship('authors','name')
                    //     ->multiple()
                    //     ->searchable()
                    //     ->preload(),
                    // ]),
                ]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('title')->wrap()
                ->sortable()
                ->searchable(),
                TextColumn::make('slug')->wrap(),
                TextColumn::make('category.name')
                 ->sortable()
                ->searchable(),
                ColorColumn::make('color'),
                ImageColumn::make('thumbnail'),
                TextColumn::make('tags')->wrap()
                ->toggleable(),
                CheckboxColumn::make('published'),
                TextColumn::make('created_at')
                ->label('Published on')
                ->date()
                ->sortable()
                ->searchable()
                ->toggleable(),
            ])
            ->filters([
                Filter::make('Published Posts')->query(
                    function(Builder $query): Builder{
                        return $query->where('published', true);
                    }
                ),
                Filter::make('UnPublished Posts')->query(
                    function(Builder $query): Builder{
                        return $query->where('published', false);
                    }
                ),
                // TernaryFilter::make('published'),
                SelectFilter::make('category_id')
                ->label('Category')
                // ->options(Category::all()->pluck('name','id'))
                ->relationship('category','name')
                ->searchable()
                ->preload()
                // ->multiple()
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
            AuthorsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
