<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('vehiculo',1)->comment('A=Auto, C=Camioneta');
            $table->tinyInteger('color')->comment('1-Rojo, 2-Negro, 3-Blanco, 4-Azul, 5-Gris, 6-PLata, 7-Cafe, 8-Verde, 9-Amarillo, 10-Morado, 11-Naranja, 12-Otro');
            $table->dateTime('entrada')->default(now())->comment('Fecha y hora de entrada');
            $table->dateTime('salida')->nullable()->comment('Fecha y hora de salida');
            $table->string('comentarios',200)->nullable()->comment('algún comentario adicional');
            $table->unsignedInteger('costo')->nullable()->comment('Costo acumulado por el servicio del estacionamiento');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
