<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoginUserResource\Pages;
use App\Models\LoginUser;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LoginUserResource extends Resource
{
    protected static ?string $model = LoginUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "المستخدمين" : "Users";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()->schema([
                    Section::make([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(100),
                    ])->columns(2),
                    Section::make([
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(15),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(100),
                    ])->columns(2),
                    Section::make([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->maxLength(100),
                        Forms\Components\TextInput::make('country')
                            ->maxLength(50)
                            ->default('Egypt'),

                    ])->columns(2),
                    Forms\Components\Toggle::make('status')
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                Tables\Columns\TextColumn::make('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])  ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoginUsers::route('/'),
            'create' => Pages\CreateLoginUser::route('/create'),
        ];
    }
    public static function getPluralLabel(): string
    {
        return app()->getLocale() == 'ar' ? 'تسجيل علي الموقع' : 'Users';
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 150 ? 'danger' : 'success';
    }

}
