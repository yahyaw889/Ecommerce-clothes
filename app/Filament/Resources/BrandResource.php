<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Illuminate\Support\Str;
// use Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "الماركات" : "Brand";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Type Brand')->schema([
                    Section::make([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('ماركة')
                            ->live(true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->label('التاكيد')
                            ->hint('سيتم توليد هذا الحقل تلقائيًا .'),
                    ])->columns(2),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('brands')
                        ->required(),
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

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                ->circular(),
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),


                Tables\Actions\Action::make('download_image')
                    ->label('تنزيل الصورة')
                    ->action(function ($record) {

                        $filePath = storage_path('app/public/' . $record->image);

                        if (file_exists($filePath)) {
                             return response()->download($filePath, 'صورة-المنتج.jpg');
                        } else {
                             return response()->json(['error' => 'الصورة غير موجودة.']);
                        }
                    }),
            ])
            ->defaultSort('created_at','desc')

            ->actions([
                Tables\Actions\ActionGroup::make([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return app()->getLocale() == 'ar' ? 'ماركة' : 'Brand';
    }
}
