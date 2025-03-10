<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DesktopResource\Pages;
use App\Models\Laptop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DesktopResource extends Resource
{
    protected static ?string $model = Laptop::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $navigationLabel = 'Desktops';
    protected static ?string $label = 'Desktop'; // Singular label

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('SN')
                    ->label('Serial Number')
                    ->required()
                    ->maxLength(255),
    
                Forms\Components\TextInput::make('USER NAME')
                    ->label('Borrower Name')
                    ->required()
                    ->maxLength(255),
    
                Forms\Components\TextInput::make('NRP')
                    ->label('NRP')
                    ->maxLength(255),
    
                Forms\Components\TextInput::make('DIVISI')
                    ->label('Division')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('COMP NAME')
                    ->label('Nama Komputer')
                    ->maxLength(255),

                Forms\Components\TextInput::make('TYPE UNIT')
                    ->label('Tipe Unit')
                    ->maxLength(255),

                Forms\Components\TextInput::make('OS')
                    ->label('OS')
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('SITE')
                    ->label('Site')
                    ->maxLength(255),
    
                Section::make('Uptime Statistics')
                ->schema([
                    Forms\Components\TextInput::make('total_uptime')
                        ->label('Total Uptime')
                        ->disabled()
                        ->formatStateUsing(function ($state, $record) {
                            $uptimeStats = DB::table('daily_uptimes')
                                ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
                                ->select(
                                    DB::raw('SUM(daily_uptimes.uptime) as total_uptime')
                                )
                                ->where('laptops.SN', $record->SN)
                                ->first();

                                return $uptimeStats->total_uptime;
                            }),
                    Forms\Components\TextInput::make('total_idle_time')
                        ->label('Total Idle Time')
                        ->disabled()
                        ->formatStateUsing(function ($state, $record) {
                            $idleTimeStats = DB::table('daily_uptimes')
                                ->join('laptops', 'daily_uptimes.laptop_sn', '=', 'laptops.sn')
                                ->select(
                                    DB::raw('SUM(daily_uptimes.idle_time) as total_idle_time')
                                )
                                ->where('laptops.SN', $record->SN)
                                ->first();

                            return $idleTimeStats->total_idle_time;
                        }),
                ])
                ->columnSpan('full'),
    
                Section::make('Daily Uptime Records')
                    ->schema([
                        Forms\Components\Repeater::make('daily_uptimes')
                            ->relationship('dailyUptimes')
                            ->schema([
                                Forms\Components\TextInput::make('date')
                                    ->label('Date')
                                    ->required(),
                                Forms\Components\TextInput::make('time')
                                    ->label('Start Time')
                                    ->required(),
                                Forms\Components\TextInput::make('uptime')
                                    ->label('Uptime')
                                    ->disabled()
                                    ->formatStateUsing(fn ($state) => $state),
                                    Forms\Components\TextInput::make('idle_time')
                                    ->label('Idle Time')
                                    ->disabled()
                                    ->formatStateUsing(fn ($state) => $state),
                                    ])
                            ->columnSpan('full')
                            ->extraAttributes(['style' => 'max-height: 300px; overflow-y: auto;']),
                    ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->query(fn() => Laptop::query()->where('CATEGORY UNIT', 'Desktop'))
            ->columns([
                Tables\Columns\TextColumn::make('SN')
                    ->label('Serial Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('USER NAME')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('NRP')
                    ->label('NRP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DIVISI')
                    ->label('Division')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CATEGORY UNIT')
                    ->label('Jenis Unit')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('TYPE UNIT')
                    ->label('Jenis Unit')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
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
            'index' => Pages\ListDesktops::route('/'),
            // 'create' => Pages\CreateDesktop::route('/create'),
            'view' => Pages\ViewDesktop::route('/{record}'),
            // 'edit' => Pages\EditDesktop::route('/{record}/edit'),
        ];
    }
}