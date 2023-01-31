<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    // protected function getTableQuery(): Builder
    // {
    //     return parent::getTableQuery()
    //         ->orderBy('type');
    // }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}