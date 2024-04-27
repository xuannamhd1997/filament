<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox; 
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker; 
use Filament\Forms\Components\MarkdownEditor; 
use Filament\Forms\Components\TagsInput; 
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\CheckboxColumn;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use App\Models\Category;
use Filament\Resources\Resource;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\CheckboxColumn::make('published'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
