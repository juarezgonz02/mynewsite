<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarColumnaEstadoProyectoAProyecto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyecto', function (Blueprint $table) {
            $table->string('estado_proyecto', 30)->after('cupos');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proyecto', function (Blueprint $table) {
            $table->dropColumn('estado_proyecto');
        });
    }
}
