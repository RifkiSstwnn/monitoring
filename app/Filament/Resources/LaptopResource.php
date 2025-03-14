<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Laptop;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\LaptopResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LaptopResource\RelationManagers;

class LaptopResource extends Resource
{
    protected static ?string $model = Laptop::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('OWNER')
                    ->label('Owner')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('PHASE')
                    ->label('Phase')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('COMP NAME REV')
                    ->label('Comp Name Rev')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('CLASSIFICATION UNIT')
                    ->label('Classification Unit')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('CATEGORY UNIT')
                    ->label('Category Unit')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('SN')
                    ->label('Serial Number')
                    ->required()
                    ->maxLength(255),
    
                Forms\Components\TextInput::make('USER NAME')
                    ->label('Nama Peminjam')
                    ->required()
                    ->maxLength(255),
    
                Forms\Components\TextInput::make('NRP')
                    ->label('NRP')
                    ->required()
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
                    ->hidden(fn ($livewire) => $livewire instanceof Pages\CreateLaptop) // Sembunyikan saat create
                    ->schema([
                        Forms\Components\TextInput::make('total_uptime')
                            ->label('Total Uptime')
                            ->disabled()
                            ->formatStateUsing(function ($state, $record) {
                                if (!$record) return 'N/A';
                                
                                $uptimeStats = DB::table('daily_uptimes')
                                    ->where('laptop_sn', $record->SN)
                                    ->sum('uptime');
                
                                return $uptimeStats ?? '0';
                            }),
                        Forms\Components\TextInput::make('total_idle_time')
                            ->label('Total Idle Time')
                            ->disabled()
                            ->formatStateUsing(function ($state, $record) {
                                if (!$record) return 'N/A';
                                
                                $idleTimeStats = DB::table('daily_uptimes')
                                    ->where('laptop_sn', $record->SN)
                                    ->sum('idle_time');
                
                                return $idleTimeStats ?? '0';
                            }),
                    ])
                    ->columnSpan('full'),
                            
                        Section::make('Daily Uptime Records')
                        ->hidden(fn ($livewire) => $livewire instanceof Pages\CreateLaptop) // Sembunyikan saat create
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
        return $table->query(fn() => Laptop::query()->where('CATEGORY UNIT', 'notebook')->whereNotNull('DIVISI'))
            ->columns([
                Tables\Columns\TextColumn::make('SN')
                    ->label('Serial Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('USER NAME')
                    ->label('Nama')
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => strlen($state) > 20 ? substr($state, 0, 20) . '...' : $state),
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
                    ->sortable()                
                    ->formatStateUsing(fn (string $state): string => strlen($state) > 20 ? substr($state, 0, 20) . '...' : $state),
                Tables\Columns\TextColumn::make('TYPE UNIT')
                    ->label('Jenis Unit')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => strlen($state) > 20 ? substr($state, 0, 20) . '...' : $state),
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
            'index' => Pages\ListLaptops::route('/'),
            'create' => Pages\CreateLaptop::route('/create'),
            'view' => Pages\ViewLaptop::route('/{record}'),
            // 'edit' => Pages\EditLaptop::route('/{record}/edit'),
        ];
    }
}