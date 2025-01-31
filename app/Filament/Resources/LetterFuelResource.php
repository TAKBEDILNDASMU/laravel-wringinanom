<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterFuelResource\Pages;
use App\Filament\Resources\LetterFuelResource\RelationManagers;
use App\Models\LetterFuel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LetterFuelResource extends Resource
{
    protected static ?string $model = LetterFuel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Dokumen';

    protected static ?string $navigationLabel = 'Surat Izin BBM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pribadi')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('no_letter')
                            ->label("Nomor Surat")
                            ->numeric()
                            ->minValue(1)
                            ->helperText("Ketika membuat dokumen baru anda bisa mengosongi form ini")
                            ->rules([
                                function ($get, $record) {
                                    return function ($attribute, $value, $fail) use ($get, $record) {
                                        $year = $record->year ?? now()->year;

                                        $exists = LetterFuel::where('no_letter', $value)
                                            ->where('year', $year)
                                            ->when($record, fn($query) => $query->where('id', '!=', $record->id))
                                            ->exists();

                                        if ($exists) {
                                            $fail("Nomor Surat $value sudah ada pada tahun $year");
                                        }
                                    };
                                }
                            ]),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                    ]),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(255),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('business_name')
                            ->required(false)
                            ->label('Nama Usaha')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user_consumer_sector')
                            ->label('Sektor Konsumen Pengguna')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_type')
                            ->label('Jenis Usaha')
                            ->required()
                            ->maxLength(255),
                    ])
                ]),
                Forms\Components\Section::make('Data Kebutuhan')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('tool_type')
                            ->label('Jenis Alat / Mesin')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tool_sum')
                            ->label('Jumlah Alat / Mesin')
                            ->required()
                            ->maxLength(255),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('tool_use')
                            ->label('Fungsi Alat / Mesin')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tool_sum2')
                            ->label('Jumlah Alat / Mesin')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tool_power')
                            ->label('Daya Alat / Mesin')
                            ->required()
                            ->maxLength(255),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('tool_time_used_hour')
                            ->label('Lama Penggunaan Alat / Mesin (jam)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tool_time_used_daily')
                            ->label('Lama Operasional Alat / Mesin (hari/minggu/bulan)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('jbt_consumption')
                            ->label('Konsumsi JBT / JBKP Alat / Mesin')
                            ->required()
                            ->maxLength(255),
                    ])
                ]),
                Forms\Components\Section::make('Data BBM')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('volume_allocation')
                            ->label('Alokasi Volume')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pick_up_place')
                            ->label('Tempat Pengambilan')
                            ->required()
                            ->maxLength(255),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('no_distributor')
                            ->label('Nomor Penyalur')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address_distributor')
                            ->label('Alamat Penyalur')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('purchasing_tools_used')
                            ->label('Alat Pembelian yang Digunakan')
                            ->required()
                            ->maxLength(255),
                    ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_letter')
                    ->label('Nomor Surat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_distributor')
                    ->label('Nomor Distributor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_distributor')
                    ->label('Alamat Distributor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('no_letter', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('Filter Berdasarkan Tahun')
                    ->options(
                        LetterFuel::select('year')
                            ->distinct()
                            ->pluck('year', 'year')
                            ->sort()
                            ->toArray()
                    )
                    ->default(now()->year)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Download PDF')
                    ->label("Download PDF")
                    ->color(Color::hex('#2196F3'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn(LetterFuel $letterFuel): string => route('surat.bbm', [$letterFuel->id]))
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
            'index' => Pages\ListLetterFuels::route('/'),
            'create' => Pages\CreateLetterFuel::route('/create'),
            'edit' => Pages\EditLetterFuel::route('/{record}/edit'),
        ];
    }
}
