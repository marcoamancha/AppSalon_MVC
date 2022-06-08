<h1 class="nombre-pagina">Pagina de administración</h1>
<?php include_once __DIR__ . '/../templates/barra.php'; ?>

<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date"
                id="fecha"
                name="fehca"
                value="<?php echo $fecha; ?>"
            />
        </div>
    </form>
    <div id="citas-admin">
        <ul class="citas">
            <?php
                $idCita = 0;
                foreach($citas as $cita){                    
                    if($idCita !== $cita->id){                        
            ?>                
                <li>                
                    <p>ID: <span> <?php echo $cita->id; ?> </span></p>
                    <p>Hora: <span> <?php echo $cita->hora; ?> </span></p>
                    <p>Cliente: <span> <?php echo $cita->cliente; ?> </span></p>
                    <p>Email: <span> <?php echo $cita->email; ?> </span></p>
                    <p>Telefono: <span> <?php echo $cita->telefono; ?> </span></p>
                    <h3>Servicios</h3>
                <?php  
                    $idCita = $cita->id;
                    } 
                ?>
                    <p class="servicio"> <?php echo $cita->servicio; ?> <span id="precio"><?php echo $cita->precio ?></span> </p>                                                 
         <?php  
            }
         ?>
        </ul>
    </div>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>