<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox; 
use Filament\Infolists\Components\Tabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker; 
use Filament\Forms\Components\MarkdownEditor; 
use Filament\Forms\Components\TagsInput; 
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $modelLabel = 'Bài viết';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Nhập thông tin')//chia thành khối chứa các phần tử bên trong
                    ->description('Nhập các thông tin bài viết tại đây')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->rules('min:3|max:20'),//validate field
                            // ->minLength(3),//validate field
                            // ->maxLength(20),//validate field
                            //->numeric()->minValue(3)->maxValue(20)

                        TextInput::make( 'slug')
                            ->unique(ignoreRecord: true)
                            ->required(),

                        Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck ('name', 'id'))
                            ->searchable()//cho phép tìm kiếm
                            // ->relationship('category', 'name')
                            ,


                        ColorPicker::make('color')
                            ->required(),

                        FileUpload::make('thumbnail')
                            ->disk('public')
                            ->directory( 'thumbnails')
                            ->columnSpanFull('full'),//sẽ chiếm hết 1 dòng

                        MarkdownEditor::make('content')
                            ->required()
                            // ->columnSpan('full'),//sẽ chiếm hết 1 dòng
                            ->columnSpanFull('full'),//sẽ chiếm hết 1 dòng

                        TagsInput::make( 'tags')
                            ->required()
                            ->columnSpanFull('full'),//sẽ chiếm hết 1 dòng

                        Checkbox::make( 'published'),
                ])
                ->columns(2)//chia thành mấy cột

                // ->columns([//chia cột theo chiều rộng màn hình
                //     'default' => 1,
                //     'md' => 2,
                //     'lg' => 2,
                //     'xl' => 2,
                // ])

                ->collapsible(),//cho phep thu gon
            ])
            ->columns(3);//chia thành mấy cột
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->sortable()//cho phep sap xep
                ->toggleable(isToggledHiddenByDefault:true),//cho phep bat tat hien thi cot(mac dinh la ko hien thi tai ds)

                TextColumn::make('title')
                    ->toggleable()//cho phep bat tat hien thi cot
                    ->searchable()//cho phép tìm kiếm theo trường này
                    ->sortable(),//cho phep sap xep

                TextColumn::make('slug')
                ->toggleable(),

                TextColumn::make('category.name')
                    ->searchable(),//cho phép tìm kiếm theo trường này

                ColorColumn::make('color'),

                ImageColumn::make('thumbnail'), 

                TextColumn::make('tags'), 

                CheckboxColumn::make('published'),

                TextColumn::make('created_at')
                    ->date('d-m-Y'), 
            ])
            ->filters([

                Filter::make('Published')->query( 
                    function (Builder $query): Builder {
                        return $query->where('published', true);
                    },
                ),

                Filter::make('Unpublished')->query( 
                    function (Builder $query): Builder {
                        return $query->where('published', false);
                    },
                ),

                // TernaryFilter::make('Published'),

                SelectFilter::make( 'category_id')
                    ->label('Category')
                    // ->options (Category:: all()->pluck ('name', 'id'))
                    // ->multiple()
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()

                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),//hành động edit ở mỗi row
                Tables\Actions\DeleteAction::make(),//hành động xóa ở mỗi row
            ])
            ->bulkActions([//cac hanh dong
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
