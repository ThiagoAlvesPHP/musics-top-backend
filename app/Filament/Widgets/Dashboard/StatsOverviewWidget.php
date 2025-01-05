<?php

namespace App\Filament\Widgets\Dashboard;

use App\Enum\Music\StatusEnum;
use App\Models\Music;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $musics_approveds_count = Music::where('status', StatusEnum::APPROVED)->count();
        $musics_pending_count = Music::where('status', StatusEnum::PENDING)->count();
        $musics_rejected_count = Music::where('status', StatusEnum::REJECTED)->count();

        return [
            Stat::make('Músicas Aprovadas', $musics_approveds_count < 10 ? '0' . $musics_approveds_count : $musics_approveds_count)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Músicas Pendentes', $musics_pending_count < 10 ? '0' . $musics_pending_count : $musics_pending_count)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
            Stat::make('Músicas Rejeitadas', $musics_rejected_count < 10 ? '0' . $musics_rejected_count : $musics_rejected_count)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),
        ];
    }
}
