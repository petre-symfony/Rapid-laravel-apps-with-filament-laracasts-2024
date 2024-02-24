<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Models\Attendee;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AttendeeChartWidget extends ChartWidget {
	protected static ?string $heading = 'Chart';

	protected int | string | array $columnSpan = 'full';

	protected static ?string $maxHeight = '200px';

	protected function getData(): array {
		$data = Trend::model(Attendee::class)
			->between(
				start: now()->subMonth(3),
				end: now()
			)
			->perMonth()
			->count()
		;
		return [
			'datasets' => [
				[
					'label' => 'Signups',
					'data' => $data->map(fn (TrendValue $value) => $value->aggregate)
				]
			],
			'labels' => $data->map(fn (TrendValue $value) => $value->date)
		];
	}

	protected function getType(): string {
		return 'line';
	}
}
