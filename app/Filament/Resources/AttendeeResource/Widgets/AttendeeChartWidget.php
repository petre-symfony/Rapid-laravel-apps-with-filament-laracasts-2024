<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use Filament\Widgets\ChartWidget;

class AttendeeChartWidget extends ChartWidget {
	protected static ?string $heading = 'Chart';

	protected int | string | array $columnSpan = 'full';

	protected static ?string $maxHeight = '200px';

	protected function getData(): array {
		return [
			'datasets' => [
				[
					'label' => 'Blog posts created',
					'data' => [0, 10, 5, 2, 31, 32, 45, 65, 77, 89, 34, 56]
				]
			],
			'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
		];
	}

	protected function getType(): string {
		return 'line';
	}
}
