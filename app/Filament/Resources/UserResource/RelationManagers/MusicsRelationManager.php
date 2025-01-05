<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enum\Music\StatusEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MusicsRelationManager extends RelationManager
{
    protected static string $relationship = 'musics';

    protected static ?string $title = 'Músicas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('youtube_id')
                    ->label('URL')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return 'https://www.youtube.com/watch?v=' . $state;
                    })
                    ->url(fn ($state) => 'https://www.youtube.com/watch?v=' . $state, true),
                SelectColumn::make('status')
                    ->options(StatusEnum::options()),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StatusEnum::options())
                    ->query(fn (Builder $query, $state): Builder => $state['value'] ? $query->where('status', $state['value']) : $query)
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
            ->headerActions([])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
