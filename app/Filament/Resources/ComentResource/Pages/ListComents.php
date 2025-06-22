<?php

namespace App\Filament\Resources\ComentResource\Pages;

use App\Filament\Resources\ComentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComents extends ListRecords
{
    protected static string $resource = ComentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
