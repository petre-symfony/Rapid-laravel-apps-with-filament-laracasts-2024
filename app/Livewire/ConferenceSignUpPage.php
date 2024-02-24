<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions {
	use InteractsWithActions, InteractsWithForms;

	public int $conferenceId;

	public int $price = 50000;

	public function mount() {
		$this->conferenceId = 1;
	}

	public function render() {
		return view('livewire.conference-sign-up-page');
	}

	public function signUpAction(): Action {
		return Action::make('signUp')
			->slideOver()
			->form([
				Placeholder::make('total_price')
					->content(function (Get $get) {
						ray(count($get('attendees')));
					}),
				Repeater::make('attendees')
					->schema(Attendee::getForm())
			])
			->action(function (array $data) {
				collect($data['attendees'])->each(function ($data) {
					Attendee::create([
						'conference_id' => $this->conferenceId,
						'name' => $data['name'],
						'ticket_cost' => $this->price,
						'email' => $data['email'],
						'is_paid' => true
					]);
				});
			});
	}
}
