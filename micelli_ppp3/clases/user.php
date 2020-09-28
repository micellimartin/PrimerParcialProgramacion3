<?php

use \Firebase\JWT\JWT;

class User
{
    public $email;
    public $tipo;
    public $password;
    const nombreArchivo = "../micelli_ppp3/archivos/users.json";

    public function __construct($email, $tipo, $password)
    {
        $this->email = $email;
        $this->tipo = $tipo;
        $this->password = $password;
    }

    public function guardar()
    {
        $retorno = "";

        $exito = Archivo::guardarJson(User::nombreArchivo, $this);

        if ($exito != 0 && $exito != false) {
            $retorno = "User guardado con exito<br/>";
        } else {
            $retorno = "Ocurrio un error al guardar el user<br/>";
        }

        return $retorno;
    }

    public static function validarTipo($tipo)
    {
        $retorno = false;
        $turno = strtolower($tipo);

        if ($turno == "user") {
            $retorno = true;
        } else if ($turno == "admin") {
            $retorno = true;
        }

        return $retorno;
    }

    public static function validarMail($email)
    {
        //Es falso que el mail ya exista
        $retorno = false;

        $arrayUsuarios = Archivo::leerJson(User::nombreArchivo);

        foreach ($arrayUsuarios as $value) {
            if ($value->email == $email) {
                //Es verdad que el mail ya existe
                $retorno = true;
                break;
            }
        }

        return $retorno;
    }

    public static function generarTokenJWT($email, $tipo)
    {
        $key = "primerparcial";

        $payload = array(
            "email" => $email,
            "tipo" => $tipo
        );

        $token = JWT::encode($payload, $key);

        return $token;
    }

    public static function validarTokenJWT($token)
    {
        $retorno = "empty";
        $key = "primerparcial";

        try {
            //Si tuvo exito en decodificar es porque el token es autentico lo que signifca que el usuario existe, se logeo con exito y le devolvi el token
            //Por lo tanto valido que es un usuario existente y logeado y no es necesario mas validaciones
            $tokenDecoficado = JWT::decode($token, $key, array('HS256'));
            $retorno = true;
        } catch (\Throwable $th) {
            $retorno = false;
        }

        return $retorno;
    }

    //Devuelve true si es tipo admin, false si es tipo user
    public static function validarTipoToken($token)
    {
        $retorno = false;
        $key = "primerparcial";

        try {
            //Esto devuelve un objeto de clase Standar
            $tokenDecoficado = JWT::decode($token, $key, array('HS256'));

            $tipoUser = strtolower($tokenDecoficado->tipo);

            if ($tipoUser == "admin") {
                $retorno = true;
            }
        } catch (\Throwable $th) {
            //El token ya va a estar validado de antes asi que nunca va a devolver esto
            $retorno = "No se pudo decodificar el token";
        }

        return $retorno;
    }

    public static function obtenerMailUser($token)
    {
        $retorno = "empty";
        $key = "primerparcial";

        try {
            //Esto devuelve un objeto de clase Standar
            $tokenDecoficado = JWT::decode($token, $key, array('HS256'));
            $retorno = $tokenDecoficado->email;
        } catch (\Throwable $th) {
            //El token ya va a estar validado de antes asi que nunca va a devolver esto
            $retorno = "No se pudo decodificar el token";
        }

        return $retorno;
    }

    public static function LogearUsuario($email, $password)
    {
        $retorno = false;

        $arrayUsuarios = Archivo::leerJson(User::nombreArchivo);

        foreach ($arrayUsuarios as $value) {
            if ($value->email == $email && $value->password == $password) {
                $retorno = User::generarTokenJWT($email, $value->tipo);
                break;
            }
        }

        return $retorno;
    }
}
