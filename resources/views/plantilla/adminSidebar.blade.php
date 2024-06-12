<?php
    $ruta = App\Http\Controllers\UserController::ruta(1);
?>
<div class="sidebar" style="height: 100%; top: 0;" id="sidebarMenu" >
            <nav class="sidebar-nav">
                <ul class="nav" id="nav-toogler">
                    <li @click='menu=0' class="nav-title clickeable">
                           <img alt="uca" style="color: white, width: auto; height: 55px;" src="img/logotype_white.svg" />                   </li>
                    <li @click='menu=0' class="nav-item">
                      <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/assignment_white_24dp.svg" alt="proyectos"></i> Proyectos </a>
                    </li>
                    <li @click='menu=4' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/solicitudes_icon_24px.svg" alt="Solicitudes"></i> Solicitudes </a>
                    </li>
                    <li @click='menu=1' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/search_white_24dp.svg" alt="buscar"></i> Buscar estudiantes </a>
                    </li>
                    <li @click='menu=2' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/history_white_24dp.svg" alt="historial"></i> Historial de proyectos </a>
                    </li>
                    <li @click='menu=5' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/stats.svg" alt="Estadisticas"></i> Estadisticas</a>
                    </li>
                    <li @click='menu=6' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/coordinadores.svg" alt="Carreras"></i> Carreras</a>
                    </li>
                    <li @click='menu=7' class="nav-item">
                        <a style="font-family: 'Abel', sans-serif;" class="nav-link active" href="#"><i><img src="<?php echo $ruta; ?>/img/icons/coordinadores.svg" alt="Coordinadores"></i> Coordinadores</a>
                    </li>
                </ul>
            </nav>
            <button class="sidebar-minimizer mobile-sidebar-toggler brand-minimizer" type="button"></button>
        </div>