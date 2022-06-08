<h1 class="nombre-pagina">Olvide mi contraseña</h1>
<p class="descripcion-pagina">Enviaremos instrucciones a tu email asociado con tu cuenta</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu Email"
            name="email"
            >   
    </div>
    <input type="submit" class="boton" value="Enviar">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
</div>