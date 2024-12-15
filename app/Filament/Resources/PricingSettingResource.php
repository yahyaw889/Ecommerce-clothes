<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingSettingResource\Pages;
use App\Filament\Resources\PricingSettingResource\RelationManagers;
use App\Models\PricingSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PricingSettingResource extends Resource
{
    protected static ?string $model = PricingSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "الإعدادات" : "Settings";
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('model_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('additional_pricing')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tax')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('additional_pricing')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax')
                    ->numeric()
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([])
            ->filters([])

;
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
            'index' => Pages\ListPricingSettings::route('/'),
            'create' => Pages\CreatePricingSetting::route('/create'),
            // 'edit' => Pages\EditPricingSetting::route('/{record}/edit'),
        ];
    }
}
