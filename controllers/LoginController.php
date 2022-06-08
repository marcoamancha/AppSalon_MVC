<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    //crear cuenta
    public static function crear(Router $router) {
        //Detectar o escuchar el method
        $usuario = new Usuario;
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();  
            //Revisar que alerta este vacia         
            if(empty($alertas)){
                //Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else {// Si no está registrado
                    //Hashear el password
                    $usuario->hashPassword();
                    //Generar Token único
                    $usuario->crearToken();
                    //Enviar el email con el token
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();                    

                    //Crear el usuario
                    $resultado = $usuario->guardar(); 
                    //debuguear($usuario);                 
                    if($resultado){
                        header('Location: /mensaje');
                    }                   
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    //vista con el mensaje de instrucciones
    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }
    //Confirmando la cuenta
    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']); //Obtener token de la url
        $usuario = Usuario::where('token', $token);
        if(empty($usuario) || $usuario->token === ''){
            Usuario::setAlerta('error', 'Token No Válido');//Enviar alerta
        } else{
            //Modificar usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = "";
            $usuario->guardar();
            Usuario::setAlerta('existo', 'Cuenta confirmada correctamente');//Enviar alerta
        }        
        $alertas = Usuario::getAlertas();//Obtengo las alertas
        //Renderizo la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
    //Iniciar sesión
    public static function login(Router $router) {
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();
            if(empty($alertas)){
                //Verificar que el usuario esté registrado
                $usuario = Usuario::where('email', $auth->email);
                if($usuario){
                    //verificar el password
                    if($usuario->validarPasswordAndConfirmado($auth->password)){
                        //Autenticar al usuario
                        if(!isset($_SESSION)) {
                            session_start();
                        }
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['apellido'] = $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento segun su rol
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }                        
                    }
                } else {
                    Usuario::setAlerta('error', 'El usuario o passwor son incorrectos');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    //Correo para recuperar contraseña
    public static function olvide(Router $router) {
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)){
                //Verificar que el usuario esté registrado
                $usuario = Usuario::where('email', $auth->email);
                if($usuario){
                    //Verificar si está confirmada
                    if($usuario->vaildarConfirmado()){
                        //Generar token
                        $usuario->crearToken();
                        $usuario->guardar();

                        //Enviar el email con el token
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                        $email->enviarInstrucciones();

                        Usuario::setAlerta('exito', 'Revisa tu correo');
                    } 
                } else {
                    Usuario::setAlerta('error', 'No exisate una cuenta con este email');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }
    // Guardar el nuevo password
    public static function recuperar(Router $router) {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']); //Obtener token de la url
        $usuario = Usuario::where('token', $token);
        if(empty($usuario) || $usuario->token === ''){
            Usuario::setAlerta('error', 'Token No Válido');//Enviar alerta
            $error = true;
        } 
        //Leer nuevo password para guardarlo
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
        
            if(empty($alertas)){
                $usuario->password = '';//Borramos password anterior

                $usuario->password = $password->password; //asignamos el nuevo password
                $usuario->hashPassword();
                $usuario->token = '';

                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }

            }
        }
        $alertas = Usuario::getAlertas();//Obtengo las alertas
        $router->render('auth/recuperar', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    //Cerrar Sesión
    public static function logout() {
        isAuth();
        $_SESSION = [];
        header('Location: /');
    }

}