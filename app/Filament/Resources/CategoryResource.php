<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Set;
use Illuminate\Support\Str;
class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationGroup = 'Category';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';//searsh

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }
    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "الاقسام" : "Category";
    }


    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make('الفئات')->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()

                        ->label('الفسم')
                        ->live(true)
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->label('التاكيد')
                        ->hint('سيتم توليد هذا الحقل تلقائيًا، ليس عليك كتابة أي شيء هنا'),

                    Forms\Components\Toggle::make('status')
                        ->required(),
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
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                    ToggleColumn::make('status')
                    ->label('Status')
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
             ->defaultSort('created_at','desc')

            ->actions([
                Tables\Actions\ActionGroup::make(actions: [
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])


                ]);

    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
        ];
    }
    public static function getPluralLabel(): string
    {
        return app()->getLocale() == 'ar' ? 'الفئات ' : 'Category';
    }
}








