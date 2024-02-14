<?php

namespace App\Models;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model {
	use HasFactory;

	protected $casts = [
		'id' => 'integer',
		'qualifications' => 'array'
	];

	public function conferences(): BelongsToMany {
		return $this->belongsToMany(Conference::class);
	}

	public static function getForm(): array {
		return [
			TextInput::make('name')
				->required()
				->maxLength(255),
			TextInput::make('email')
				->email()
				->required()
				->maxLength(255),
			Textarea::make('bio')
				->required()
				->maxLength(65535)
				->columnSpanFull(),
			TextInput::make('twitter_handle')
				->required()
				->maxLength(255),
		];
	}
}
