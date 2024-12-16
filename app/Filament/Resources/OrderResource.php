<?php
namespace App\Filament\Resources;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Illuminate\Support\Number;

use Filament\Forms\Components\Section;
use Filament\Tables\Actions\Action;
class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'first_name';//searsh

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'desc');
    }

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "الطلبات" : "Orders";
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->first_name;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات العميل')->schema([
                    TextInput::make('first_name')
                        ->required()
                         ->maxLength(255),
                    TextInput::make('last_name')
                        ->required()
                        ->rule('regex:/^[a-zA-Z\s]+$/')
                        ->helperText('Only English letters are allowed.')
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Section::make([

                        Textarea::make('address')
                            ->required(),
                        Textarea::make('notes')
                            ->nullable()
                            ->default('--'),
                        TextInput::make('country')
                            ->default('Egypt')
                            ->nullable(),
                        TextInput::make('invoice_number')
                            ->default(function () {
                                
                                $nextInvoiceNumber =  rand(10000,10000000);

                                while(Order::where('invoice_number' , $nextInvoiceNumber )->exists()){
                                    $nextInvoiceNumber =  rand(10000,100000000);

                                }
                                

                                return "N-". str_pad($nextInvoiceNumber, 3, '0', STR_PAD_LEFT);
                            })
                            ->required(),


                    ])->columns(2),

                    Toggle::make('status')
                        ->required()
                        ->label('status')
                        ->hidden(),

                ])->columns(),


                Section::make('OrderItems')->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                                ->relationship('product', 'name', function ($query) {
                                    $query->where('status', '!=', 0);
                                })
                                ->label('product')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->distinct()
                                ->columnSpan(4)
                                ->reactive()
                                ->afterStateUpdated(
                                    fn($state, Set $set) =>
                                    $set('unit_amount', Product::find($state)?->price ?? 0)
                                ),
                            TextInput::make('quantity')
                                ->numeric()
                                ->minValue(1)
                                ->required()
                                ->columnSpan(3)
                                ->reactive()
                                ->afterStateUpdated(
                                    fn($state, Set $set, Get $get) =>
                                    $set('total_amount', $state * $get('unit_amount'))
                                ),
                            TextInput::make('unit_amount')
                                ->numeric()
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(3),
                            TextInput::make('total_amount')
                                ->numeric()
                                ->dehydrated()
                                ->required()
                                ->columnSpan(2),
                        ])->columns(12),
                ]),



                Section::make('Special Information')->schema([
                    Repeater::make('special')
                        ->relationship()
                        ->schema([

                            Section::make([

                                TextInput::make('name')
                                    ->default('special')
                                    ->required(),
                                TextInput::make('price')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1),
                                Select::make('size')
                                    ->label('size')
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->options([
                                        'S' => 'S',
                                        'M' => 'M',
                                        'L' => 'L',
                                        'XL' => 'XL',
                                        '2XL' => '2XL',
                                        '3XL' => '3XL',
                                    ]),
                                ColorPicker::make('color')
                                    ->label('color')
                                    ->required()
                                    ->format('hex')
                                    ->default('#FFFFFF'),

                            ])->columns(3),
                            FileUpload::make('image')->image()->nullable()
                                ->multiple()
                                ->maxFiles(2)
                                ->directory('special')
                                ->reorderable()
                                ->required()
                                ->hint('الحد الادنا للصور2 صور للتحميل')
                        ])
                        ->createItemButtonLabel('Add Special Item'),


                    Section::make('Totals')->schema([
                        Placeholder::make('grand_total_placeholder')
                            ->label('Money Totals')
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                if ($items = $get('items')) {
                                    foreach ($items as $key => $item) {
                                        $total += $get("items.{$key}.total_amount");
                                    }
                                }
                                if ($specials = $get('special')) {
                                    foreach ($specials as $key => $special) {
                                        $total += $get("special.{$key}.price") * $get("special.{$key}.quantity");
                                    }
                                }
                                $set('grand_total', $total);

                                return Number::currency($total, 'EGP');
                            }),
                        Hidden::make('grand_total')
                            ->default(0),
                    ]),


                ])




            ]);


    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->rowIndex(),
                TextColumn::make('invoice_number')->label('ID Number')->searchable(),
                TextColumn::make('first_name')->label('First Name')->searchable(),
                TextColumn::make('last_name')->label('Last Name')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Phone')->searchable(),
                TextColumn::make('country')->label('Country')->searchable(),

                TextColumn::make('address')->label('Address')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')->label('notes')->searchable()->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('status')
                    ->label(' status')
                    ->onColor('success')
                    ->offColor('danger')
                    ->action(function ($record, $state) {
                        $record->update(['status' => $state]);

                    }),



                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Updated At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make(actions: [
                    Tables\Actions\Action::make('viewDetails')
                        ->label('Show Invoice')
                        ->icon('heroicon-o-arrows-pointing-out')
                        ->url(fn($record) => route('invoice', $record->id)),
                    Tables\Actions\Action::make('viewDetails')
                        ->label('Download Invoice')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn($record) => route('invoice_download', $record->id)),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->tooltip('Actions')

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ]);

    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 0)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 150 ? 'danger' : 'success';
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders';
    }
}
