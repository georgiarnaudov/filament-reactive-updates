<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Closure;
use App\Models\Attribute;
use Illuminate\Support\Str;
use App\Models\AttributeValue;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateProduct extends CreateRecord
{

    use HasWizard;

    protected static string $resource = ProductResource::class;


    protected function getSteps(): array
    {
        return [

            Step::make('General')
                ->schema([
                    Card::make()->schema([

                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->reactive()
                                    ->afterStateUpdated(function ($set, $state) {
                                        $set('slug', Str::slug($state));
                                    })
                                    ->required(),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique('products', 'slug', ignoreRecord: true),
                                Fieldset::make('Visiblity')
                                    ->columnSpan(1)
                                    ->schema([
                                        Toggle::make('status')
                                            ->columnSpanFull()
                                            ->default(false),
                                    ]),

                                Select::make('type')
                                    ->disablePlaceholderSelection()
                                    ->reactive()
                                    ->options([
                                        'simple' => 'Single',
                                        'configurable' => 'With variations'
                                    ])
                                    ->default('simple'),
                            ]),


                        Section::make('Generate variations')
                            ->hidden(fn (Closure $get) => $get('type') === 'simple')
                            ->schema([
                                Repeater::make('super_attributes')
                                    ->columnSpanFull()
                                    ->columns(2)
                                    ->maxItems(3)
                                    ->schema([
                                        Select::make('attribute_id')
                                            ->label('Attribute')
                                            ->options(
                                                Attribute::all()
                                                    ->where('used_for_variations', true)
                                                    ->pluck('frontend_label', 'id')
                                                    ->toArray()
                                            )
                                            ->reactive()
                                            ->afterStateUpdated(function ($set) {
                                                $set('attribute_value_ids', null);
                                            }),
                                        Select::make('attribute_value_ids')
                                            ->minItems(1)
                                            ->label('Attribute options')
                                            ->multiple()
                                            ->options(fn ($get) => AttributeValue::where('attribute_id', $get('attribute_id'))->pluck('label', 'id'))
                                    ])
                            ]),
                    ])
                        ->columns(2)
                ]),

            Step::make('Pricing')
                ->schema([]),

            Step::make('Stock')
                ->schema([]),

        ];
    }
}