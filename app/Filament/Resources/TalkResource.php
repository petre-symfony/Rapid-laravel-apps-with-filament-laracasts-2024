<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Talk;
use Filament\Forms;
use Filament\Forms\Form;
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
			->columns([
				Tables\Columns\TextInputColumn::make('title')
					->searchable()
					->rules(['required', 'max:255'])
					->sortable(),
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
				Tables\Columns\TextColumn::make('status')->badge()
			])
			->filters([
				//
			])
			->actions([
				Tables\Actions\EditAction::make(),
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
			'edit' => Pages\EditTalk::route('/{record}/edit'),
		];
	}
}
