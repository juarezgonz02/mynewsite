<?php
    $ruta = App\Http\Controllers\UserController::ruta(1);
?>
<div class="sidebar" style="height: 100%; top: 0;" id="sidebarMenu">
            <nav class="sidebar-nav">
                <ul class="nav" id="nav-toogler">
                    <li @click='menu=0' class="nav-title">
                           <img alt="uca" style="color: white, width: auto; height: 55px;" src="img/logotype_white.svg" />
                    </li>
                    <li @click='menu=0' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/calendar-week.svg" alt="proyectos"></i> Proyectos Disponibles</a>
                    </li>
                    <li @click='menu=1' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/calendar-check.svg" alt="buscar"></i> Proyectos Aplicados </a>
                    </li>
                    <li @click='menu=2' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/lightbulb_white_24dp.svg" alt="historial"></i> Recordatorio </a>
                    </li>
                    <li @click='menu=3' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/person.svg" alt="recordatorio"></i> Perfil </a>
                    </li>
                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>