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

        // INSERT INTO `carrera` VALUES (1,1,'Ingeniería Informática'),(2,1,'Ingeniería Industrial'),(3,1,'Ingeniería Civil'),(4,1,'Ingeniería Eléctrica'),(5,1,'Ingeniería de Alimentos'),(6,1,'Ingeniería Mecánica'),(7,1,'Ingeniería Química'),(8,1,'Ingeniería Energética'),(9,1,'Arquitectura'),(10,2,'Licenciatura en Psicología'),(11,2,'Licenciatura en Ciencias Jurídicas'),(12,2,'Licenciatura en Filosofía'),(13,2,'Licenciatura en Comunicación Social'),(14,2,'Licenciatura en Educación Básica para Primero y Segundo Ciclos'),(15,2,'Licenciatura en Idioma Inglés'),(16,2,'Profesorado en Educación Inicial y Parvularia'),(17,2,'Técnico en Producción Multimedia'),(18,2,'Licenciatura en Teología'),(19,2,'Profesorado en Teología'),(20,2,'Profesorado en Idioma Inglés'),(21,3,'Técnico en Mercadeo'),(22,3,'Licenciatura en Contaduría Pública'),(23,3,'Técnico en Contaduría'),(24,3,'Licenciatura en Economía'),(25,3,'Licenciatura en Administración de Empresas');
        
        DB::table('carrera')
        ->insert(array('idCarrera'=>'1', 'idFacultad'=>'1', 'nombre'=>'Ingeniería Informática'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'2', 'idFacultad'=>'1', 'nombre'=>'Ingeniería Industrial'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'3', 'idFacultad'=>'1', 'nombre'=>'Ingeniería Civil'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'4', 'idFacultad'=>'1', 'nombre'=>'Ingeniería Eléctrica'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'5', 'idFacultad'=>'1', 'nombre'=>'Ingeniería de Alimentos'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'6', 'idFacultad'=>'1', 'nombre'=>'Ingeniería Mecánica'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'7', 'idFacultad'=>'1', 'nombre'=>'Ingeniería Química'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'8', 'idFacultad'=>'1', 'nombre'=>'Ingeniería Energética'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'9', 'idFacultad'=>'1', 'nombre'=>'Arquitectura'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'10', 'idFacultad'=>'2', 'nombre'=>'Licenciatura en Psicología'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'11', 'idFacultad'=>'2', 'nombre'=>'Licenciatura en Ciencias Jurídicas'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'12', 'idFacultad'=>'2', 'nombre'=>'Licenciatura en Filosofía'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'13', 'idFacultad'=>'2', 'nombre'=>'Licenciatura en Comunicación Social'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'14', 'idFacultad'=>'2', 'nombre'=>'Licenciatura en Educación Básica para Primero y Segundo Ciclos'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'15', 'idFacultad'=>'2', 'nombre'=>'Licenciatura en Idioma Inglés'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'16', 'idFacultad'=>'2', 'nombre'=>'Profesorado en Educación Inicial y Parvularia'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'17', 'idFacultad'=>'2', 'nombre'=>'Técnico en Producción Multimedia'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'18', 'idFacultad'=>'2', 'nombre'=>'Licenciatura en Teología'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'19', 'idFacultad'=>'2', 'nombre'=>'Profesorado en Teología'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'20', 'idFacultad'=>'2', 'nombre'=>'Profesorado en Idioma Inglés'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'21', 'idFacultad'=>'3', 'nombre'=>'Técnico en Mercadeo'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'22', 'idFacultad'=>'3', 'nombre'=>'Licenciatura en Contaduría Pública'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'23', 'idFacultad'=>'3', 'nombre'=>'Técnico en Contaduría'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'24', 'idFacultad'=>'3', 'nombre'=>'Licenciatura en Economía'));
        DB::table('carrera')
        ->insert(array('idCarrera'=>'25', 'idFacultad'=>'3', 'nombre'=>'Licenciatura en Administración de Empresas'));

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
