<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Filament\Resources\UserResource\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // protected static ?string $modelLabel = 'Khách hàng';

    public static function form(Form $form): Form//form tạo user
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),//input tên
                TextInput::make('email')->email(),
                TextInput::make('password')->password(),
                // Select::make('gender')->options([
                //     1 => 'Male',
                //     0 => 'Female',
                //     2 => 'Other',
                // ]),//select chon gioi tinh
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')//trường
                    ->label('ID')//tên hiển thị
                    ->sortable(),//cot ten
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable(),//cho phép tìm kiếm theo trường này
                Tables\Columns\TextColumn::make('email'),//cot email

                Tables\Columns\TextColumn::make('email'),//cot email

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                    // ->toggleable(isToggledHiddenByDefault:true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
                    // ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->paginated([25, 36, 50, 100,])// phân trang
            ->defaultSort('id', 'desc')//danh sách sắp xếp mặc định id giảm dần
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
