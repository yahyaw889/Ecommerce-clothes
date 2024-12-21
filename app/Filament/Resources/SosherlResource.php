<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SosherlResource\Pages;
use App\Models\Sosherl;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;


class SosherlResource extends Resource
{
    protected static ?string $model = Sosherl::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? "الإعدادات" : "Settings";
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("info")->schema([
                    Section::make([
                        TextInput::make('name')
                            ->label('اسم الموقع')
                            ->default('Artiva')
                            ->required(),
                        TextInput::make('phone')
                            ->label('التلفون')
                            ->required(),
                        TextInput::make('email')
                            ->label('الايميل')
                            ->email()
                            ->required(),
                    ])->columns(3),
                    Section::make('المديا')->schema([
                        TextInput::make('social')
                            ->label('لنكات التواصل')
                            ->url(),
                    ]),
                    Select::make('sorting_product')
                    ->label('الترتيب حسب التصنيف')
                    ->options([
                        'new' => 'جديد',
                        'sold' => 'الأكثر مبيعا',
                        'views' => 'الأكثر مشاهدة',
                        'discount' => 'الخصمات',
                        ])
                        ->multiple(),
                    Textarea::make('disc')
                        ->label('وصف الموقع')
                        ->nullable()
                        ->default('--')
                        ->required(),
                ]),
                
               
                Section::make('صور الموقع')->schema([
                    FileUpload::make('image')
                    ->directory('images')
                    ->label('صورة الموقع')
                    ->image(),
                ]),



                Section::make('لنكات التواصل')->schema([
                    TextInput::make('youtube')
                    ->label('youtube')
                    ->url(),
                TextInput::make('instagram')
                    ->label('instagram')
                    ->url(),
                TextInput::make('facebook')
                    ->label('facebook')
                    ->url(),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('اسم الموقع')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('التلفون')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('الايميل')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('sorting_product')
                    ->label('ترتيب المنتجات')
                    ->sortable(),
                ToggleColumn::make('quantity')
                    ->label(' quantity')
                    ->onColor('success')
                    ->offColor('danger')
                    ->action(function ($record, $state) {
                    $record->update(['quantity' => $state]);

                }),
                TextColumn::make('facebook')
                    ->label('فيس بوك')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('instagram')
                    ->label('انستجرام')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ,
                TextColumn::make('youtube')
                    ->label('يوتيوب')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ,
                    // ImageColumn::make('image')
                    // ->label('لوجو')
                    // ,

                TextColumn::make('sosherl')
                    ->label('لنكات التواصل')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('disc')
                    ->label('وصف الموقع')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(100)
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
                Tables\Actions\ActionGroup::make(actions: [

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    // Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSosherls::route('/'),
            // 'create' => Pages\CreateSosherl::route('/create'),
        ];
    }

    public static function getPluralLabel(): string
    {
        return app()->getLocale() == 'ar' ? ' لينكات التواصل ' : 'Communication links';
    }
}

