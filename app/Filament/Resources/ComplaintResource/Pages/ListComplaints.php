<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComplaints extends ListRecords
{
    protected static string $resource = ComplaintResource::class;
    protected static ?string $title = "Komplain";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Komplain Baru"),
        ];
    }
}
