<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModelNameResource\Pages;
use App\Filament\Resources\ModelNameResource\RelationManagers;
use App\Models\ModelName;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModelNameResource extends Resource
{
    protected static ?string $model = ModelName::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Model Type';
    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "موديلات" : "Models";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                    ToggleColumn::make('status')
                    ->label(' حاله المنتج')
                    ->onColor('success')
                    ->offColor('danger')
                    ->action(function ($record, $state) {
                        $record->update(['status' => $state]);
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListModelNames::route('/'),
            'create' => Pages\CreateModelName::route('/create'),
            'edit' => Pages\EditModelName::route('/{record}/edit'),
        ];
    }
}
