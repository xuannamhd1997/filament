<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\TextInput;
use App\Models\Post;
use App\Models\User;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship ('user', 'name') 
                    ->searchable()
                    ->preload(),

                TextInput::make('comment'),

                MorphToSelect::make('commentable')
                ->label('Object')
                ->types ([
                MorphToSelect\Type::make (Post::class)
                    ->titleAttribute('title'),
                MorphToSelect\Type::make(User::class)
                    ->titleAttribute('email'),
                MorphToSelect\Type::make(Comment::class)
                    ->titleAttribute('id'),
                ])
                ->searchable()
                ->preload()



                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                ->sortable()//cho phep sap xep
                ->toggleable(isToggledHiddenByDefault:true),//cho phep bat tat hien thi cot(mac dinh la ko hien thi tai ds)

                TextColumn::make('comment')
                    ->toggleable()//cho phep bat tat hien thi cot
                    ->searchable()//cho phép tìm kiếm theo trường này
                    ->sortable(),//cho phep sap xep
                TextColumn::make('user.name')
                ->searchable(),//cho phép tìm kiếm theo trường này

                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
