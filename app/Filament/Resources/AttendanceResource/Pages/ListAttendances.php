<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Models\Leave;
use Filament\Actions;
use App\Models\Schedule;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AttendanceResource;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make(),
        ];

        // Cek schedule dan leave user
        $userSchedule = Schedule::where('user_id', Auth::id())->first();
        $leave = Leave::where('user_id', Auth::id())->first();

        // Tambah pengecekan null untuk menghindari error
        if ($userSchedule) {
            if ($userSchedule->is_banned == 0) {
                array_unshift(
                    $actions,
                    Action::make("Tambah Presensi")
                        ->url(route('presensi'))
                        ->color("info")
                        ->icon("heroicon-o-plus")
                );
            } else {
                // Tampilkan notifikasi warning jika user diblokir
                Notification::make()
                    ->danger()
                    ->title('Akses Diblokir')
                    ->body("Anda tidak dapat menambahkan presensi karena akun Anda diblokir. Hubungi admin untuk info lebih lanjut")
                    ->persistent()
                    ->send();
            }
        }

        // Tambah aksi export untuk super admin
        if (Auth::user()->hasRole("super_admin")) {
            array_unshift(
                $actions,
                Action::make("Export ke Excel")
                    ->url(route('attendance-export'))
                    ->color("success")
                    ->icon("heroicon-o-arrow-down-tray")
                    ->tooltip('Export data ke format Excel')
            );
        }

        return $actions;
    }
}
