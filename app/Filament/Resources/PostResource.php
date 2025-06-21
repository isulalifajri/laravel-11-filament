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
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->label('Category'),
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
                ]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->wrap(),
                TextColumn::make('slug')->wrap(),
                TextColumn::make('category.name'),
                ColorColumn::make('color'),
                ImageColumn::make('thumbnail'),
                TextColumn::make('tags')->wrap(),
                CheckboxColumn::make('published'),
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
            //
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
