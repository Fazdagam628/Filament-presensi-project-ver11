<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Map extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static string $view = 'filament.pages.map';

    protected static ?string $navigationLabel = 'Peta Absensi';
    protected static ?string $label = 'Peta Absensi';
    protected static ?int $navigationSort = 2;
}