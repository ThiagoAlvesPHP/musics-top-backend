<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Dashboard\StatsOverviewWidget;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class
        ];
    }
}
