<?php
namespace Model;

class Usuario extends ActiveRecord {
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = [
        'id',
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'admin',
        'confirmado',
        'token'
    ];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    // 1 Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre || !$this-> apellido || !$this-> email || !$this-> telefono){
            self::$alertas['error'][] = 'Todos los campos son obligatorios';
        }
        if(strlen($this-> password) < 6){
            self::$alertas['error'][] = 'Tu password debe tener almenos 6 caracteres';
        }
        
        return self::$alertas;
    }
         //Revisar si el usuario ya existe
     public function existeUsuario(){
         $query = "SELECT * FROM " . self::$tabla. " WHERE email ='". $this->email. "' LIMIT 1";
         $resultado = self::$db->query($query);

         if($resultado->num_rows){
            self::$alertas['error'][] = 'El usuario con este email ya está registrado';
        }
        return $resultado;
     }
       //Hashear password
     public function hashPassword(){
         $this->password = password_hash($this->password, PASSWORD_BCRYPT);
     }
         //Generar token
     public function crearToken(){
         $this->token = uniqid();
     }

     // 2 Validar el login
        //Validando el email
     public function validarLogin(){
        if(!$this-> email || !$this-> password){
            self::$alertas['error'][] = 'Todos los campos son obligatorios';
        }        
        return self::$alertas;
     }
        //Comprobar password y si el usuario está verificado
     public function validarPasswordAndConfirmado($password) {
        $resultado = password_verify($password, $this->password);// Verificar password
        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'Contraseña incorrecta o verifica tu cuenta';
        } else {
            return true;
        }
     }
     // 3 Recuperar contraseña
     public function validarEmail(){
         if(!$this->email){
             self::$alertas['error'][] = 'Ingresa tu email';
         }
         return self::$alertas;
     } 
        //Verificar que este confirmado
    public function vaildarConfirmado(){
        if(!$this->confirmado){
            self::$alertas['error'][] = 'Cuenta no verificada ve a tu email para verificarla';
        } else {
            return true;
        }
    }
        //Validando el password
    public function validarPassword(){
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'La contraseña debe tener almenos 6 caracteres';
        }        
        return self::$alertas;
     }
}