<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendeesStatsWidget extends BaseWidget {
	protected function getStats(): array {
		return [
			Stat::make('Unique views', '192.1k'),
			Stat::make('Bounce Rate', '21%'),
			Stat::make('Average time on page', '3.12')
		];
	}
}
