<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-c-list-bullet';
    protected static ?string $navigationGroup = 'Pengelola Jadwal';

    protected static ?string $navigationLabel = 'Absensi Pegawai';
    protected static ?string $label = 'Absensi Pegawai';
    protected static ?int $navigationSort = 8;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('schedule_latitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('schedule_longitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('schedule_start_time')
                    ->required(),
                Forms\Components\TextInput::make('schedule_end_time')
                    ->required(),
                Forms\Components\TextInput::make('start_latitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('start_longitude')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('start_time')
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->required(),
                Forms\Components\TextInput::make('end_latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('end_longitude')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $is_super_admin = Auth::user()->hasRole("super_admin");

                if (!$is_super_admin) {
                    $query->where('user_id', Auth::user()->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Tanggal")
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label("Pegawai")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_late')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->isLate() ? 'Terlambat' : "Tepat Waktu";
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Tepat Waktu' => 'success',
                        'Terlambat' => 'danger'
                    })
                    ->description(fn(Attendance $record): string => "Durasi : " . $record->workDuration()),
                Tables\Columns\TextColumn::make('start_time')
                    ->label("Waktu Datang"),
                Tables\Columns\TextColumn::make('end_time')
                    ->label("Waktu Pulang"),
                // Tables\Columns\TextColumn::make('work_duration')
                //     ->getStateUsing(function ($record) {
                //         return $record->workDuration();
                //     }),
                // Tables\Columns\TextColumn::make('schedule_latitude')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('schedule_longitude')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('schedule_start_time'),
                // Tables\Columns\TextColumn::make('schedule_end_time'),
                // Tables\Columns\TextColumn::make('start_latitude')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('start_longitude')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('end_latitude')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('end_longitude')
                //     ->numeric()
                //     ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}






// ----------------------


// <?php
//
// namespace App\Filament\Resources;
//
// use Filament\Forms;
// use Filament\Tables;
// use Filament\Forms\Form;
// use App\Models\Attendance;
// use Filament\Tables\Table;
// use Filament\Resources\Resource;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
// use App\Filament\Resources\AttendanceResource\Pages;
// use App\Filament\Resources\AttendanceResource\RelationManagers;
//
// class AttendanceResource extends Resource
// {
//     protected static ?string $model = Attendance::class;
//
//     protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
//
//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\TextInput::make('user_id')
//                     ->required()
//                     ->readOnly(),
//                 Forms\Components\TextInput::make('schedule_latitude')
//                     ->required()
//                     ->numeric(),
//                 Forms\Components\TextInput::make('schedule_longitude')
//                     ->required()
//                     ->numeric(),
//                 Forms\Components\TextInput::make('schedule_start_time')
//                     ->required(),
//                 Forms\Components\TextInput::make('schedule_end_time')
//                     ->required(),
//                 Forms\Components\TextInput::make('start_latitude')
//                     ->required()
//                     ->numeric(),
//                 Forms\Components\TextInput::make('start_longitude')
//                     ->required()
//                     ->numeric(),
//                 Forms\Components\TextInput::make('start_time')
//                     ->required(),
//                 Forms\Components\TextInput::make('end_time')
//                     ->required(),
//                 Forms\Components\TextInput::make('end_latitude')
//                     ->numeric(),
//                 Forms\Components\TextInput::make('end_longitude')
//                     ->numeric(),
//             ]);
//     }
//
//     public static function table(Table $table): Table
//     {
//         return $table
//             ->modifyQueryUsing(function (Builder $query) {
//                 $is_super_admin = Auth::user()->hasRole("super_admin");
//
//                 if (!$is_super_admin) {
//                     $query->where('user_id', Auth::user()->id);
//                 }
//             })
//             ->columns([
//                 Tables\Columns\TextColumn::make('created_at')
//                     ->label("Tanggal")
//                     ->date()
//                     ->sortable(),
//                 Tables\Columns\TextColumn::make('user.name')
//                     ->label("Pegawai")
//                     ->sortable(),
//                 Tables\Columns\TextColumn::make('start_time')
//                     ->label("Waktu Datang"),
//                 Tables\Columns\TextColumn::make('end_time')
//                     ->label("Waktu Pulang"),
//                 // Tables\Columns\TextColumn::make('schedule_latitude')
//                 //     ->numeric()
//                 //     ->sortable(),
//                 // Tables\Columns\TextColumn::make('schedule_longitude')
//                 //     ->numeric()
//                 //     ->sortable(),
//                 // Tables\Columns\TextColumn::make('schedule_start_time'),
//                 // Tables\Columns\TextColumn::make('schedule_end_time'),
//                 // Tables\Columns\TextColumn::make('start_latitude')
//                 //     ->numeric()
//                 //     ->sortable(),
//                 // Tables\Columns\TextColumn::make('start_longitude')
//                 //     ->numeric()
//                 //     ->sortable(),
//                 // Tables\Columns\TextColumn::make('updated_at')
//                 //     ->dateTime()
//                 //     ->sortable()
//                 //     ->toggleable(isToggledHiddenByDefault: true),
//                 // Tables\Columns\TextColumn::make('deleted_at')
//                 //     ->dateTime()
//                 //     ->sortable()
//                 //     ->toggleable(isToggledHiddenByDefault: true),
//                 // Tables\Columns\TextColumn::make('end_latitude')
//                 //     ->numeric()
//                 //     ->sortable(),
//                 // Tables\Columns\TextColumn::make('end_longitude')
//                 //     ->numeric()
//                 //     ->sortable(),
//             ])
//             ->filters([
//                 //
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//             ])
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),
//                 ]),
//             ]);
//     }
//
//     public static function getRelations(): array
//     {
//         return [
//             //
//         ];
//     }
//
//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListAttendances::route('/'),
//             'create' => Pages\CreateAttendance::route('/create'),
//             'edit' => Pages\EditAttendance::route('/{record}/edit'),
//         ];
//     }
// }