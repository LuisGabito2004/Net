<?php
function main_header($args = [],$sesion = null){
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <title>GREENNET</title>
    <?php
    if (isset($args['styles'])) {
        foreach ($args['styles'] as $s) {
            echo_link_style($s);
        }
    }
    ?>
    <link rel="shortcut icon" type="image/x-icon" href="<?php  ?>">
</head>
<body>
    <header>
        <div class="logo" id="logo">
            <img src="resources/img/logo_mission_vision.png" alt="Logo de mi foro" class="img-logo">
            <h1 class="nombre-logo">GreenNet</h1>
        </div>
       
        <div class="perfil">
            <p class="nombre-perfil"><?php echo !isset($sesion->sv) ? "<h2><button class='registerbtn' onclick=\"app.view('login')\"> Registrate o Inicia sesion</button></h2>" : "" ?> </p><!--Pone en el header el boton para registrarse o no -->
            <img src="resources/img/perfil_img.jpg" alt="Foto de perfil" class="img-perfil" id="perfil_Icono" onclick="app.toggleDetails()">
        </div>
    </header>

<?php scripts(); }?>
