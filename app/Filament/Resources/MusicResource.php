<?php

namespace App\Filament\Resources;

use App\Enum\Music\StatusEnum;
use App\Filament\Resources\MusicResource\Pages;
use App\Models\Music;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MusicResource extends Resource
{
    protected static ?string $model = Music::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationLabel = 'Músicas';

    protected static ?string $modelLabel = 'Música';

    protected static ?string $pluralModelLabel = 'Músicas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ViewField::make('thumb')
                    ->view('filament.components.image')
                    ->columnSpanFull()
                    ->disabled()
                    ->hidden(fn ($operation) => $operation === 'create'),
                TextInput::make('title')
                    ->label('Título:')
                    ->placeholder('Título do video...')
                    ->disabled()
                    ->hidden(fn ($operation) => $operation === 'create'),
                TextInput::make('count_views')
                    ->label('Qtd. Visualização:')
                    ->placeholder('Quantidade de visualização...')
                    ->disabled()
                    ->hidden(fn ($operation) => $operation === 'create'),
                Select::make('status')
                    ->options(StatusEnum::options())
                    ->hidden(fn ($operation) => $operation === 'create'),
                TextInput::make('url')
                    ->label('URL:')
                    ->placeholder('URL do Youtube...')
                    ->columnSpan(fn ($operation) => $operation === 'create' ? 2 : 1)
                    ->required()
                    ->url(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Usuário')
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
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('info'),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->button()
                ->label('Ações'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMusic::route('/'),
            'create' => Pages\CreateMusic::route('/create'),
            'edit' => Pages\EditMusic::route('/{record}/edit'),
        ];
    }
}
