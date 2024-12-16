<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialResource\Pages;
use App\Models\Order;
use App\Models\Special;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SpecialResource extends Resource
{
    protected static ?string $model = Special::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "الطلبات" : "Orders";
    }


    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make([
                Select::make('order_id')
                    ->label('صاحب الطلب')
                    ->options(fn() => Order::query()
                        ->select(['id', 'first_name', 'last_name'])
                        ->get()
                        ->mapWithKeys(fn($order) => [$order->id => $order->first_name . ' ' . $order->last_name])
                    )
                    ->required()
                    ->default(fn($record) => $record?->order_id),

                Select::make('order_id')
                    ->label('هاتف  ')
                    ->options(fn() => Order::query()->pluck('phone', 'id'))
                    ->preload()
                    ->searchable()
                    ->required()
                    ->default(fn($record) => $record?->order_id),

                Select::make('order_id')
                    ->label('إيميل المنتج')
                    ->options(fn() => Order::query()->pluck('email', 'id'))
                    ->required()
                    ->default(fn($record) => $record?->order_id),

                Forms\Components\Select::make('size')
                    ->label('المقاسات')
                    ->multiple()
                    ->options([
                        'S' => 'S',
                        'M' => 'M',
                        'L' => 'L',
                        'XL' => 'XL',
                        'XXL' => 'XXL',
                    ])
                    ->required()
                    ->default(fn($record) => $record?->size),

                Forms\Components\TextInput::make('price')
                    ->label('السعر')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(fn($record) => $record?->price),

                Forms\Components\TextInput::make('discount')
                    ->label('الخصم')
                    ->required()
                    ->numeric()
                    ->default(fn($record) => $record?->discount),

                Forms\Components\TextInput::make('quantity')
                    ->label('العدد')
                    ->required()
                    ->numeric()
                    ->default(fn($record) => $record?->quantity),

                Forms\Components\Select::make('color')
                    ->label('اللون')
                    ->options([
                        'الأحمر' => 'الأحمر',
                        'أبيض' => 'أبيض',
                        'أصفر' => 'أصفر',
                        'أخضر' => 'أخضر',
                        'الأسود' => 'الأسود',
                        'غير ذلك' => 'غير ذلك',
                    ])
                    ->required()
                    ->default(fn($record) => $record?->color)
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $set('color', null))
                    ->hint('اختر لونًا أو حدد "غير ذلك" لإدخال لون جديد'),

                Forms\Components\TextInput::make('color')
                    ->label('لون مخصص')
                    ->visible(fn($get) => $get('color')  === 'غير ذلك')
                    ->required(fn($get) => $get('color') === 'غير ذلك')
                    ->default(fn($record) => $record?->color)
                    ->hint('اكتب اللون المخصص هنا'),

                Forms\Components\Toggle::make('status')
                    ->label('الحالة')
                    ->required()
                    ->default(fn($record) => $record?->status),

                Forms\Components\FileUpload::make('image')
                    ->label('صورة المنتج')
                    ->directory('special')
                    ->multiple()
                    ->maxFiles(2)
                    ->required()
                    ->default(fn($record) => $record?->image),
            ])->columns(2),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('#')->rowIndex(),

            Tables\Columns\TextColumn::make('order.full_name')
            ->label('صاحب الطلب')
            ->getStateUsing(fn($record) => $record->order->first_name . ' ' . $record->order->last_name)
            ->sortable()
            ->searchable(),
            Tables\Columns\TextColumn::make('order.phone')
                ->label('هاتف  ')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('order.email')
                ->label('  الايميل')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('price')
                ->label('السعر')
                ->money()
                ->sortable(),

            Tables\Columns\TextColumn::make('discount')
                ->label('الخصم')
                ->numeric()
                ->sortable(),

            Tables\Columns\TextColumn::make('quantity')
                ->label('العدد')
                ->numeric()
                ->sortable(),

            Tables\Columns\ImageColumn::make('image')
                ->label('الصورة')
                ->circular(),

            Tables\Columns\TextColumn::make('size')
                ->label('المقاسات')
                ->searchable(),

            Tables\Columns\TextColumn::make('color')
                ->label('اللون')
                ->searchable(),

            ToggleColumn::make('status')
                ->label('الحالة')
                ->onColor('success')
                ->offColor('danger')
                ->action(function ($record, $state) {
                    $record->update(['status' => $state]);
                }),

            Tables\Columns\TextColumn::make('created_at')
                ->label('تاريخ الإنشاء')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('تاريخ التحديث')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])->defaultSort('created_at', 'desc')
        ->actions([
            Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('download_image')
                    ->label('تنزيل جميع الصور')
                    ->action(function ($record) {
                        $images = $record->image;

                        if (is_array($images) && !empty($images)) {
                            $zip = new ZipArchive();
                            $zipFileName = storage_path('app/public/Specail_images_' . $record->id . '.zip');

                            if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
                                foreach ($images as $image) {
                                    $filePath = storage_path('app/public/' . $image);

                                    if (file_exists($filePath)) {
                                        $zip->addFile($filePath, basename($filePath));
                                    }
                                }
                                $zip->close();

                                return response()->download($zipFileName)->deleteFileAfterSend(true);
                            }

                            return response()->json(['error' => 'حدث خطأ أثناء إنشاء الملف المضغوط.']);
                        }

                        return response()->json(['error' => 'لا توجد صور لتحميلها.']);
                    }),
            ])

        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecials::route('/'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الرسم الإبداعي' : 'Specials';
    }
    public static function getNavigationBadge(): ?string
        {
            return static::getModel()::count();
        }
        public static function getNavigationBadgeColor(): string|array|null
        {
            return static::getModel()::count() > 150 ? 'warning' : 'danger';
        }
}
