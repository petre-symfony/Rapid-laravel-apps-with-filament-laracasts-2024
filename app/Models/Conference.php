<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model {
	use HasFactory;

	protected $casts = [
		'id' => 'integer',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'region' => Region::class,
		'venue_id' => 'integer',
	];

	public function venue(): BelongsTo {
		return $this->belongsTo(Venue::class);
	}

	public function speakers(): BelongsToMany {
		return $this->belongsToMany(Speaker::class);
	}

	public function talks(): BelongsToMany {
		return $this->belongsToMany(Talk::class);
	}

	public static function getForm(): array {
		return [
			Section::make('Conference Details')
				->collapsible()
				->description('Provide some basic information about the conference')
				->icon('heroicon-o-information-circle')
				->columns(2)
				->schema([
					TextInput::make('name')
						->columnSpanFull()
						->label('Conference')
						->default('My Conference')
						->required()
						->maxLength(60),
					MarkdownEditor::make('description')
						->columnSpanFull()
						->helperText('Markdown')
						->required(),
					DateTimePicker::make('start_date')
						->native(false)
						->required(),
					DateTimePicker::make('end_date')
						->native(false)
						->required(),
					Fieldset::make('Status')
					->columns(1)
					->schema([
						Select::make('status')
							->options([
								'draft' => 'Draft',
								'published' => 'Published',
								'archived' => 'Archived'
							])
							->required(),
						Toggle::make('is_published')
							->default(false),
					])
				]),
			Section::make('Location')
				->columns(2)
				->schema([
					Select::make('region')
						->live()
						->enum(Region::class)
						->options(Region::class),
					Select::make('venue_id')
						->searchable()
						->preload()
						->createOptionForm(Venue::getForm())
						->editOptionForm(Venue::getForm())
						->relationship(
							'venue',
							'name',
							modifyQueryUsing: function (Builder $query, Get $get) {
								ray();

								$query->where('region', $get('region'));
							})
				]),
			Actions::make([
				Actions\Action::make('star')
					->label('Fill with Factory Data')
					->icon('heroicon-o-star')
					->action(function ($livewire) {
						ray($livewire->form);
						$data = Conference::factory()->make()->toArray();
					})
			])
		];
	}
}
