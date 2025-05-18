<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\ListUserActivities;
use App\Models\User;
use DateTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rappasoft\LaravelTimezone\Timezone;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect;
use Tapp\FilamentTimezoneField\Tables\Columns\TimezoneColumn;
use Tapp\FilamentTimezoneField\Tables\Filters\TimezoneSelectFilter;

class UserResource extends Resource
{
    protected static ?string $navigationGroup = 'Auth';

    protected static ?int $navigationSort = 1;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return self::getUrl('view', ['record' => $record]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'E-mail' => $record->email,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Member Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                        Forms\Components\TextInput::make('password')->password()->required()->maxLength(255)->visibleOn('create'),
                        TimezoneSelect::make('timezone')->searchable()->required(),
                    ])->columns(),
                Forms\Components\Section::make('Abilities')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->multiple()
                            ->preload()
                            ->relationship('roles', 'name'),
                        Forms\Components\Select::make('permissions')
                            ->multiple()
                            ->preload()
                            ->relationship('permissions', 'name'),
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                TimezoneColumn::make('timezone')->formattedOffsetAndTimezone()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->formatStateUsing(fn (DateTime $state) => resolve(Timezone::class)->convertToLocal($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name'),
                Tables\Filters\SelectFilter::make('permissions')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->preload(),
                TimezoneSelectFilter::make('timezone')->searchable(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Action::make('History')
                        ->color('gray')
                        ->icon('heroicon-o-list-bullet')
                        ->url(fn (User $record): string => route('filament.admin.resources.users.activities', $record)),
                ]),
                Impersonate::make()->redirectTo(route('dashboard')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('User Information')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('timezone'),
                    ])->columns(3),
                Section::make('Abilities')
                    ->schema([
                        TextEntry::make('roles.name')->listWithLineBreaks(),
                        TextEntry::make('permissions.name')->listWithLineBreaks(),
                    ])->columns(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'activities' => ListUserActivities::route('/{record}/activities'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
