<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCarreras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $carreras = [
            ['idCarrera' => '1', 'idFacultad' => '1', 'nombre' => 'Ingeniería Informática'],
            ['idCarrera' => '2', 'idFacultad' => '1', 'nombre' => 'Ingeniería Industrial'],
            ['idCarrera' => '3', 'idFacultad' => '1', 'nombre' => 'Ingeniería Civil'],
            ['idCarrera' => '4', 'idFacultad' => '1', 'nombre' => 'Ingeniería Eléctrica'],
            ['idCarrera' => '5', 'idFacultad' => '1', 'nombre' => 'Ingeniería de Alimentos'],
            ['idCarrera' => '6', 'idFacultad' => '1', 'nombre' => 'Ingeniería Mecánica'],
            ['idCarrera' => '7', 'idFacultad' => '1', 'nombre' => 'Ingeniería Química'],
            ['idCarrera' => '8', 'idFacultad' => '1', 'nombre' => 'Ingeniería Energética'],
            ['idCarrera' => '9', 'idFacultad' => '1', 'nombre' => 'Arquitectura'],
            ['idCarrera' => '10', 'idFacultad' => '2', 'nombre' => 'Licenciatura en Psicología'],
            ['idCarrera' => '11', 'idFacultad' => '2', 'nombre' => 'Licenciatura en Ciencias Jurídicas'],
            ['idCarrera' => '12', 'idFacultad' => '2', 'nombre' => 'Licenciatura en Filosofía'],
            ['idCarrera' => '13', 'idFacultad' => '2', 'nombre' => 'Licenciatura en Comunicación Social'],
            ['idCarrera' => '14', 'idFacultad' => '2', 'nombre' => 'Licenciatura en Educación Básica para Primero y Segundo Ciclos'],
            ['idCarrera' => '15', 'idFacultad' => '2', 'nombre' => 'Licenciatura en Idioma Inglés'],
            ['idCarrera' => '16', 'idFacultad' => '2', 'nombre' => 'Profesorado en Educación Inicial y Parvularia'],
            ['idCarrera' => '17', 'idFacultad' => '2', 'nombre' => 'Técnico en Producción Multimedia'],
            ['idCarrera' => '18', 'idFacultad' => '2', 'nombre' => 'Licenciatura en Teología'],
            ['idCarrera' => '19', 'idFacultad' => '2', 'nombre' => 'Profesorado en Teología'],
            ['idCarrera' => '20', 'idFacultad' => '2', 'nombre' => 'Profesorado en Idioma Inglés'],
            ['idCarrera' => '21', 'idFacultad' => '3', 'nombre' => 'Técnico en Mercadeo'],
            ['idCarrera' => '22', 'idFacultad' => '3', 'nombre' => 'Licenciatura en Contaduría Pública'],
            ['idCarrera' => '23', 'idFacultad' => '3', 'nombre' => 'Técnico en Contaduría'],
            ['idCarrera' => '24', 'idFacultad' => '3', 'nombre' => 'Licenciatura en Economía'],
            ['idCarrera' => '25', 'idFacultad' => '3', 'nombre' => 'Licenciatura en Administración de Empresas'],
        ];

        foreach ($carreras as $carrera) {
            $exists = DB::table('carrera')->where('idCarrera', $carrera['idCarrera'])->exists();
            if (!$exists) {
                DB::table('carrera')->insert($carrera);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        DB::table('carrera')
        ->where('idCarrera', '=', 1)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 2)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 3)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 4)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 5)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 6)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 7)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 8)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 9)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 10)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 11)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 12)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 13)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 14)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 15)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 16)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 17)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 18)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 19)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 20)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 21)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 22)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 23)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 24)
        ->delete();
        DB::table('carrera')
        ->where('idCarrera', '=', 25)
        ->delete();

    }
}
