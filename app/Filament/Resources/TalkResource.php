<?php

namespace App\Filament\Resources;

use App\Enums\TalkLength;
use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TalkResource extends Resource {
	protected static ?string $model = Talk::class;

	protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

	public static function form(Form $form): Form {
		return $form
			->schema([
				Forms\Components\TextInput::make('title')
					->required()
					->maxLength(255),
				Forms\Components\Textarea::make('abstract')
					->required()
					->maxLength(65535)
					->columnSpanFull(),
				Forms\Components\Select::make('speaker_id')
					->relationship('speaker', 'name')
					->required(),
			]);
	}

	public static function table(Table $table): Table {
		return $table
			->persistFiltersInSession()
			->filtersTriggerAction(function ($action) {
				return $action->button()->label('Filters');
			})
			->columns([
				Tables\Columns\TextColumn::make('title')
					->searchable()
					->sortable()
					->description(function (Talk $record) {
						return Str::of($record->abstract)->limit(40);
					}),
				Tables\Columns\ImageColumn::make('speaker.avatar')
					->label('Speaker Avatar')
					->defaultImageUrl(function ($record) {
						return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->speaker->name);
					})
					->circular(),
				Tables\Columns\TextColumn::make('speaker.name')
					->numeric()
					->sortable()
					->searchable(),
				Tables\Columns\ToggleColumn::make('new_talk'),
				Tables\Columns\TextColumn::make('status')
					->badge()
					->sortable()
					->color(function ($state) {
						return $state->getColor();
					}),
				Tables\Columns\IconColumn::make('length')
					->icon(function ($state) {
						return match($state) {
							TalkLength::NORMAL => 'heroicon-o-megaphone',
							TalkLength::LIGHTNING => 'heroicon-o-bolt',
							TalkLength::KEYNOTE => 'heroicon-o-key'
						};
					})
			])
			->filters([
				Tables\Filters\TernaryFilter::make('new_talk'),
				Tables\Filters\SelectFilter::make('speaker')
					->relationship('speaker', 'name')
					->multiple()
					->searchable()
					->preload(),
				Tables\Filters\Filter::make('has_avatar')
					->label('Show Only Speakers with Avatars')
					->toggle()
					->query(function ($query) {
						return $query->whereHas('speaker', function (Builder $query) {
							$query->whereNotNull('avatar');
						});
					})
			])
			->actions([
				Tables\Actions\EditAction::make()
					->slideOver(),
				Tables\Actions\Action::make('approve')
					->icon('heroicon-o-check-circle')
					->color('success')
					->action(function (Talk $record) {
						$record->approved();
					})
					->after(function () {
						Notification::make()->success()->title('This talk was approved')
							->duration(1000)
							->body('The speaker has been notified and the talk has been added to the conference schedule')
							->send();
					})
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					Tables\Actions\DeleteBulkAction::make(),
				]),
			]);
	}

	public static function getRelations(): array {
		return [
			//
		];
	}

	public static function getPages(): array {
		return [
			'index' => Pages\ListTalks::route('/'),
			'create' => Pages\CreateTalk::route('/create'),
	//		'edit' => Pages\EditTalk::route('/{record}/edit'),
		];
	}
}
