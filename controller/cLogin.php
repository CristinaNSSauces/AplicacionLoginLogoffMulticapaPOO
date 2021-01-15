<?php

if(!isset($_COOKIE['idioma'])){
    setcookie('idioma','es',time()+2592000); // crea la cookie 'idioma' con el valor 'es' para 30 dias
    header('Location: index.php');
    exit;
}

if (isset($_REQUEST['idiomaElegido'])) { // si se ha pulsado el botton de cerrar sesion
    setcookie('idioma', $_REQUEST['idiomaElegido'], time() + 2592000); // modifica la cookie 'idioma' con el valor recibido del formulario para 30 dias
    header('Location: index.php');
    exit;
}

define("OBLIGATORIO", 1); // defino e inicializo la constante a 1 para los campos que son obligatorios

$entradaOK = true;

$aErrores = [ //declaro e inicializo el array de errores
    'CodUsuario' => null,
    'Password' => null
];


if (isset($_REQUEST["IniciarSesion"])) { // comprueba que el usuario le ha dado a al boton de IniciarSesion y valida la entrada de todos los campos
    $aErrores['CodUsuario'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['CodUsuario'], 15, 3, OBLIGATORIO); // comprueba que la entrada del codigo de usuario es correcta

    $aErrores['Password'] = validacionFormularios::validarPassword($_REQUEST['Password'], 8, 1, 1, OBLIGATORIO);// comprueba que la entrada del password es correcta
    
    $oUsuario = UsuarioPDO::validarUsuario($_REQUEST['CodUsuario'], $_REQUEST['Password']);// validamos que el usuario introducido y la contraseña son correctos
    
    if(!isset($oUsuario)){// Si el codUsuario y password no existen
        $aErrores['CodUsuario'] = "Error de autentificacion";// Asignamos un error a uno de los campos para limpiarlos posteriormente
    }
    
    foreach ($aErrores as $campo => $error){
        if ($error != null && $entradaOK) {
            $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario
            unset($_REQUEST); // Limpiamos los campos del formulario
        }
    }
    
} else { // si el usuario no le ha dado al boton de enviar
    $entradaOK = false; // le doy el valor false a $entradaOK
}

if ($entradaOK) { // si la entrada esta bien recojo los valores introducidos y hago su tratamiento

    $_SESSION['usuarioDAW2LoginLogoffMulticapaPOO'] = $oUsuario; // guarda en la session el objeto usuario

    header('Location: index.php');
    exit;
}

$vistaEnCurso = $vistas['login']; // asignamos a la variable vistaEnCurso la vista del login
require_once $vistas['layout']; // cargamos el layout
?>