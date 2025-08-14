<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Fasilitas';
    protected static ?string $modelLabel = 'Fasilitas';
    protected static ?string $pluralModelLabel = 'Fasilitas';
    protected static ?string $navigationGroup = 'Fasilitas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Fasilitas')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Fasilitas')
                            ->required()
                            ->maxLength(255),
                        Select::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options([
                                'classroom' => 'Ruang Kelas',
                                'laboratory' => 'Laboratorium',
                                'library' => 'Perpustakaan',
                                'sports' => 'Olahraga',
                                'multimedia' => 'Multimedia',
                                'administration' => 'Administrasi',
                                'facility' => 'Fasilitas Umum',
                                'other' => 'Lainnya',
                            ])
                            ->default('facility'),
                        FileUpload::make('image')
                            ->label('Gambar Fasilitas')
                            ->image()
                            ->directory('facilities')
                            ->maxSize(2048),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->maxLength(2000),
                    ])->columns(2),

                Section::make('Detail Fasilitas')
                    ->schema([
                        TextInput::make('capacity')
                            ->label('Kapasitas')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255),
                        Repeater::make('specifications')
                            ->label('Spesifikasi')
                            ->schema([
                                TextInput::make('spec')
                                    ->label('Spesifikasi')
                                    ->maxLength(255),
                            ])
                            ->default([])
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        TextInput::make('order_index')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Nama Fasilitas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'classroom' => 'info',
                        'laboratory' => 'warning',
                        'library' => 'primary',
                        'sports' => 'success',
                        'multimedia' => 'danger',
                        'administration' => 'gray',
                        'facility' => 'secondary',
                        default => 'gray',
                    }),
                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->numeric(),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('order_index')
                    ->label('Urutan')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Status'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'classroom' => 'Ruang Kelas',
                        'laboratory' => 'Laboratorium',
                        'library' => 'Perpustakaan',
                        'sports' => 'Olahraga',
                        'multimedia' => 'Multimedia',
                        'administration' => 'Administrasi',
                        'facility' => 'Fasilitas Umum',
                        'other' => 'Lainnya',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order_index');
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
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}