<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions {
	use InteractsWithActions, InteractsWithForms;

	public function render() {
		return view('livewire.conference-sign-up-page');
	}

	public function signUpAction(): Action {
		return Action::make('signUp')
			->slideOver()
			->form([
				Repeater::make('attendees')
					->schema(Attendee::getForm())
			])
			->action(function (array $data) {
				ray($data);
			});
	}
}
