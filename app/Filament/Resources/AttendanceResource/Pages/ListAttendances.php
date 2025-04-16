<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AttendanceResource;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make("Export ke Excel")
                ->url(route('attendance-export'))
                ->color("success")
                ->icon("heroicon-o-arrow-down-tray"),
            Action::make("Tambah Presensi")
                ->url(route('presensi'))
                ->color("info"),
            Actions\CreateAction::make(),
        ];
    }
}