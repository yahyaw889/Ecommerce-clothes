<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "المستخدمين" : "Users";
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make('Owner')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم المستخدم')
                        ->required()
                        ->validationAttribute('Input Name...')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->validationAttribute('Input Email...')
                        ->required()
                        ->label('ايميل')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->label('كلمه المرور')
                        ->required()
                        ->maxLength(255),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->authorize(fn($record) => $record->id === Auth::user()->id),
                Tables\Actions\DeleteAction::make()
                    ->authorize(fn($record) => $record->id === Auth::user()->id)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getPluralLabel(): string
    {
        return app()->getLocale() == 'ar' ? 'المالك' : 'Owner';
    }
}
