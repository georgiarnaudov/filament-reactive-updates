<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Models\Product;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    // protected $queryString = [
    //     'activeTabIndex' => ['except' => 1]
    // ];


    protected function getActions(): array
    {


        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('goToParent')
                ->label($this->record->parent_id === null ? '' : 'Към ' . $this->record->parentProduct->name . ' (родител)')
                ->visible($this->record->parent_id !== null)
                ->url(fn () => route('filament.resources.products.edit', $this->record?->parent_id))
        ];
    }
}