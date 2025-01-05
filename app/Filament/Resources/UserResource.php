<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Usuários';

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome:')
                    ->placeholder('Nome do usuário...')
                    ->required()
                    ->minValue(3)
                    ->maxValue(50),
                TextInput::make('email')
                    ->label('E-mail:')
                    ->placeholder('E-mail do usuário...')
                    ->required()
                    ->email(),
                TextInput::make('password')
                    ->label('Senha:')
                    ->placeholder('Senha do usuário...')
                    ->password()
                    ->required()
                    ->minValue(8)
                    ->maxValue(255)
                    ->columnSpanFull(),
                Toggle::make('is_admin')
                    ->label('Administrador')
                    ->disabled(fn ($operation, $record) => $operation === 'edit' && $record->id === Auth::user()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome:')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail:')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('is_admin')
                    ->label('Administrador:')
                    ->disabled(fn ($record) => Auth::user()->id === $record->id),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'admins' => 'Mostrar somente admins',
                        'users' => 'Mostrar somente usuários',
                    ])
                    ->query(function (Builder $query, $state): Builder {
                        switch($state['value']) {
                            case 'admins':
                                return $query->where('is_admin', true);
                            case 'users':
                                return $query->where('is_admin', false);
                            default:
                                return $query;
                        }
                    })
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filtros'),
            )
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
