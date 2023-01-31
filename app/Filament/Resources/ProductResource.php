<?php

namespace App\Filament\Resources;


use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\AttributeValue;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\ProductResource\Pages;

class ProductResource extends Resource
{

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('main')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Обща информация')
                            ->icon('heroicon-o-home')
                            ->schema([

                                Grid::make()
                                    ->columns(3)
                                    ->schema([

                                        TextInput::make('name')
                                            ->label('Име на продукта')
                                            ->helperText('Показва се на потребителите')
                                            ->reactive()
                                            ->afterStateUpdated(function ($set, $state) {
                                                $set('slug', Str::slug($state));
                                            })
                                            ->required(),
                                        TextInput::make('slug')
                                            ->required()
                                            ->label('URL разширение')
                                            ->helperText('Полето се генерира автоматично от името на продукта.')
                                            ->unique('products', 'slug', ignoreRecord: true),
                                        Fieldset::make('Видимост')
                                            ->columnSpan(1)
                                            ->schema([
                                                Toggle::make('status')
                                                    ->columnSpanFull()
                                                    ->reactive()
                                                    ->label(fn (bool $state) => $state ? 'Видим' : 'Скрит')
                                                    ->helperText(fn (bool $state) => $state ? 'Продукта се показва в магазина' : 'Продукта е скрит и не се показва в магазина')
                                                    ->default(false),
                                            ]),

                                    ]),
                                RichEditor::make('short_description')
                                    ->nullable()
                                    ->maxLength(255),

                                RichEditor::make('description')
                                    ->nullable()
                                    ->maxLength(3000),

                            ])
                            ->icon('heroicon-o-collection')
                            ->schema([
                                Repeater::make('properties')
                                    ->label('')
                                    ->createItemButtonLabel('Добави')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('attribute_id')
                                            ->label('Attribute')
                                            ->reactive()
                                            ->options(function ($get, $state) {

                                                $attrIds = collect($get('../../properties'))
                                                    ->map(fn ($item) => $item['attribute_id'])
                                                    ->flatten()
                                                    ->toArray();


                                                if ($state === null) {
                                                    return Attribute::all()
                                                        ->whereNotIn('id', $attrIds)
                                                        ->pluck('frontend_label', 'id')
                                                        ->toArray();
                                                } else if (in_array($state, $attrIds)) {
                                                    return Attribute::all()
                                                        ->whereIn('id', $state)
                                                        ->pluck('frontend_label', 'id')
                                                        ->toArray();
                                                }

                                                return Attribute::query()
                                                    ->whereNotIn('id', $attrIds)
                                                    ->pluck('frontend_label', 'id')
                                                    ->toArray();
                                            })
                                            ->afterStateUpdated(function ($set, $state) {
                                                $set('attribute_value_id', null);
                                            }),
                                        Select::make('attribute_value_id')
                                            ->label('Attribute Value')
                                            ->options(
                                                fn ($get) =>
                                                AttributeValue::where('attribute_id', $get('attribute_id'))
                                                    ->pluck('label', 'id')
                                            )
                                    ]),

                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('status')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}