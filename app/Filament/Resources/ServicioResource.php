<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServicioResource\Pages;
use App\Filament\Resources\ServicioResource\RelationManagers;
use App\Models\Servicio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;

class ServicioResource extends Resource
{
    protected static ?string $model = Servicio::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 
                Forms\Components\Radio::make('vehiculo')
                    ->options( ['A'=>'Auto', 'C'=>'Camioneta' ])
                    ->default('A')
                    ->disabledOn('edit')
                    ->label('Vehículo'),

                Forms\Components\Radio::make('color') 
                    ->options( ['1'=>'Rojo', '2'=>'Negro', '3'=>'Blanco', '4'=>'Azul', 
                                '5'=>'Gris', '6'=>'Plata', '7'=>'Café', '8'=>'Verde', 
                                '9'=>'Amarillo', '10'=>'Morado', '11'=>'Naranja', '12'=>'Otro' ])
                    ->default('1')
                    ->inline()
                    ->disabledOn('edit') ,

                Forms\Components\DateTimePicker::make('entrada')->default(now())->readonly()->disabledOn('edit'),
                Forms\Components\DateTimePicker::make('salida')->default(now())->readonly()->disabledOn('create'),

                Forms\Components\TextInput::make('comentarios')->columns(4) 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('vehiculo')
                    ->formatStateUsing(function ($state): string {
                        if($state=='A') $valor='Auto';
                        else $valor='Camioneta';
                        return $valor;
                        })
                    ->label('Vehículo'),

                Tables\Columns\TextColumn::make('color')
                    ->formatStateUsing(function ($state): string {
                        switch($state) {
                            case '1': $valor='Rojo'; break;
                            case '2': $valor='Negro'; break;
                            case '3': $valor='Blanco'; break;
                            case '4': $valor='Azul'; break;
                            case '5': $valor='Gris'; break;
                            case '6': $valor='Plata'; break;
                            case '7': $valor='Café'; break;
                            case '8': $valor='Verde'; break;
                            case '9': $valor='Amarillo'; break;
                            case '10': $valor='Morado'; break;
                            case '11': $valor='Naranja'; break;
                            case '12': $valor='Otro'; break;
                        } 
                        return $valor;
                        })
                    ->label('Color'),
                Tables\Columns\TextColumn::make('entrada'),
                Tables\Columns\TextColumn::make('salida'),
                Tables\Columns\TextColumn::make('costo')->money('MXN'),                
                Tables\Columns\TextColumn::make('comentarios'),                

            ])
            ->filters([
                //
                Filter::make('Aún no salen')
                    ->query(fn (Builder $query) => $query->where('salida', null))
                    ->toggle(),
            ])
            ->actions([
              //  Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Registra salida')
                    ->action( fn (Servicio $ser) => $ser->update( ['salida'=>now(), 
                                                                   'costo'=> ((Carbon::parse($ser->entrada))->diffInHours(now()) * env('CTO_HR') ) + env('CTO_HR') ]
                                                                ) 
                            )
                   /* ->successNotification(Notification::make()
                        ->title('Total: $')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->send()
                            )
                            */              
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
            'index' => Pages\ListServicios::route('/'),
            'create' => Pages\CreateServicio::route('/create'),
            'edit' => Pages\EditServicio::route('/{record}/edit'),
        ];
    }
}
