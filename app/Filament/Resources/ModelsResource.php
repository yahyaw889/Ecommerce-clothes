<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModelsResource\Pages;
use App\Filament\Resources\ModelsResource\RelationManagers;
use App\Models\Models;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModelsResource extends Resource
{
    protected static ?string $model = Models::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "موديلات" : "Models";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Grid::make()
                    ->schema([

                FileUpload::make('image_forward')
                ->label('صورة الأمامية')
                    ->directory('models')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('image_back')
                    ->label('صورة الخلفية')
                    ->directory('models')
                    ->required()
                    ->columnSpanFull(),
                    Forms\Components\Select::make('model_name_id') 
                    ->label('نوع الموديل')
                    ->preload()
                    ->relationship('model', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\ColorPicker::make('color')
                    ->label('اللون')
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->required()
                    ->default(true),
            ])
        ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model.name')
                    ->label('Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->label('اللون')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return "<div style='display: inline-block; width: 20px; height: 20px; background-color: {$state}; border-radius: 50%; margin-right: 10px;'></div>{$state}";
                    })
                    ->html(),
                Tables\Columns\ImageColumn::make('image_forward')
                    ->label('صورة الأمامية'),
                Tables\Columns\ImageColumn::make('image_back')
                    ->label('صورة الخلفية'),
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
                Tables\Actions\ActionGroup::make(actions: [
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])

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
            'index' => Pages\ListModels::route('/'),
            'create' => Pages\CreateModels::route('/create'),
            'edit' => Pages\EditModels::route('/{record}/edit'),
        ];
    }
}
