<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Set;
use Illuminate\Support\Str;
 class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $recordTitleAttribute = 'name';//searsh

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "المنتجات" : "Product";
    }
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات المنتج')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                             ->live(true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label(' تاكيد الاسم')
                            ->required()
                            ->hint('سيتم توليد هذا الحقل تلقائيًا . '),
                    ])->columns(2),

                Section::make('التصنيف والعلامة التجارية')
                    ->schema([
                        Forms\Components\Select::make('Categore_id')
                            ->label('التصنيف')
                            ->relationship('categore', 'name',function($query){
                                $query->where('status','!=',0);
                           })
                            ->preload()
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('brand_id')
                            ->label('العلامة التجارية')
                            ->relationship('brand', 'name',function($query){
                                 $query->where('status','!=',0);
                            })
                            ->preload()
                            ->searchable()
                            ->required(),
                    ])->columns(2),

                Section::make('المواصفات')
                    ->schema([

                        Forms\Components\Repeater::make('color')
                            ->label('الألوان')
                            ->schema([
                                Forms\Components\ColorPicker::make('color')
                                    ->label('اختر لون')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->createItemButtonLabel('أضف لوناً جديداً')
                            ->default([])
                            ->required(),
                        Forms\Components\Select::make('size')
                            ->label('المقاس')
                            ->preload()
                            ->multiple()
                            ->searchable()
                            ->options([
                                'S' => 'S',
                                'M' => 'M',
                                'L' => 'L',
                                'XL' => 'XL',
                                '2XL' => '2XL',
                                '3XL' => '3XL',
                                'منتج اخر' => 'منتج اخر',
                            ])
                            ->hint('يمكنك اختيار أكثر من مقاس'),

                    ])->columns(2),

                Section::make('السعر والخصم')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label(' السعر المنتج الواحده')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->reactive(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('الكمية')
                            ->required()
                            ->minLength(1)
                            ->maxLength(5)
                            ->numeric()
                            ->reactive(),
                        Forms\Components\TextInput::make('discount')
                            ->label('الخصم')
                            ->numeric()
                            ->default(0)

                    ])->columns(3),

                Section::make('الوصف')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('description')
                            ->label('الوصف')
                            ->required()
                            ->default('--')
                            ->columnSpanFull(),
                    ]),

                Section::make('washing method')
                    ->schema([
                        Forms\Components\Textarea::make('washing_method')
                            ->label('طريقه الغسيل')
                            ->required()
                            ->default('You can choose any cleaner you need (:')
                            ->columnSpanFull(),
                    ]),

                Section::make('الصورة والحالة')
                    ->schema([
                        FileUpload::make('images')
                            ->multiple()
                            ->maxFiles(5)
                            ->directory('products')
                            ->reorderable()

                            ->hint('الحد الادنا للصور 5 صور للتحميل')
                        ,

                        Forms\Components\Toggle::make('status')
                            ->label('الحالة')
                            ->required()
                            ->default(true)
                            ->hidden(),

                    ]),
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
                    ->label('الاسم'),
                Tables\Columns\TextColumn::make('categore.name')
                    ->sortable()
                    ->label('القسم'),
                Tables\Columns\TextColumn::make('brand.name')
                ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->label('الماركه'),

                Tables\Columns\TextColumn::make('color')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('اللون')
                    ->searchable(),
                Tables\Columns\TextColumn::make('size')
                    ->label('المقاس')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('تاكيد الاسم')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                    ImageColumn::make('images')
                    ->label('الصور')
                    ->sortable()
                    ->size(40)
                    ->circular()
                    ->stacked(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->label('العدد')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_sold')
                    ->numeric()
                    ->label('عدد المبيعات')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->label('الخصم')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\ImageColumn::make('images')
                       ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('السعر الإجمالي بعد الخصم')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('washing_method')
                    ->label('طريقه الغسيل')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
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
                SelectFilter::make('المنتجات')
                    ->relationship('categore', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('الماركات')
                    ->relationship('brand', 'name')
                    ->preload()
                    ->searchable(),

            ])
            ->defaultSort('created_at','desc')
            ->actions([
                Tables\Actions\ActionGroup::make(actions: [

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
        ];
    }
    public static function getPluralLabel(): string
    {
        return app()->getLocale() == 'ar' ? 'المنتجات ' : 'Product';
    }
}


