@extends('principal')
@section('dashboard')
    @if(Auth::check())
        @if(Auth::user()->idRol == 1)
            <template v-if="menu==0">
                <admin-proyectos></admin-proyectos>
            </template>
            <template v-if="menu==1">
                <admin-estudiantes></admin-estudiantes>
            </template>
            <template v-if="menu==2">
                <historial-proyectos></historial-proyectos>
            </template>
            <template v-if="menu==3">
                <recordatorio></recordatorio>
            </template>
            <template v-if="menu==4">
                <todas-solicitudes></todas-solicitudes>
            </template>
            <template v-if="menu==5">
                <estadisticas></estadisticas>
            </template>
            <template v-if="menu==6">
                <carreras></carreras>
            </template>
            <template v-if="menu==7">
                <coordinadores></coordinadores>
            </template>
        @elseif(Auth::user()->idRol == 2)
            <template v-if="menu==0">
                <todos-proyectos></todos-proyectos>
            </template>           
            <template v-if="menu==1">
                <mis-proyectos></mis-proyectos>
            </template>
            <template v-if="menu==2">
                <recordatorio></recordatorio>
            </template>
            <template v-if="menu==3">
                <perfil></perfil>
            </template>
        @else
        @endif
    @endif
@endsection