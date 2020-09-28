<?php

require __DIR__ . '/vendor/autoload.php';
require_once './clases/user.php';
require_once './clases/archivo.php';
require_once './clases/precio.php';
require_once './clases/auto.php';

$method = $_SERVER['REQUEST_METHOD'];
$path_info = $_SERVER['PATH_INFO'];

switch($method)
{
    case 'POST':

        if($path_info == '/registro')
        {
            if(!(empty($_POST['email'])) && !(empty($_POST['tipo'])) && !(empty($_POST['password'])))
            {
                if(User::validarMail($_POST['email']))
                {
                    echo "El mail ya existe";
                }
                else
                {
                    if(User::validarTipo($_POST['tipo']))
                    {
                        $usuario = new User($_POST['email'], $_POST['tipo'] ,$_POST['password']);
                        echo $usuario->guardar();  
                    }
                    else
                    {
                        echo "El tipo tiene que ser admin o user";
                    }
                }                      
            }
            else
            {
                echo "Para generar un nuevo usuario es necesario email, tipo y password";
            } 
        }
        else if($path_info == '/login')
        {
            if(!(empty($_POST['email'])) && !(empty($_POST['password'])))
            {
                $respuestaLogin = User::LogearUsuario($_POST['email'], $_POST['password']);

                if($respuestaLogin != false)
                {
                    echo "Login exitoso!<br/>";
                    echo "Su token de validacion es el siguiente: <br/>";

                    echo "<pre>"; 
                    var_dump($respuestaLogin);
                    echo "<pre>"; 
                }
                else
                {                   
                    echo "El usuario no existe";                  
                }
            }
            else
            {
                echo "Para logearse debe enviar email y password";
            } 
        }
        else if($path_info == '/precio')
        {
            if(!(empty($_SERVER['HTTP_TOKEN'])))
            {
                if(User::validarTokenJWT($_SERVER['HTTP_TOKEN']))
                {
                    //Devuelve true si es admin
                    if(User::validarTipoToken($_SERVER['HTTP_TOKEN']))
                    {
                        if( !(empty($_POST['precio_hora'])) && !(empty($_POST['precio_estadia'])) && !(empty($_POST['precio_mensual'])) )
                        {
                            
                            $precio = new Precio($_POST['precio_hora'], $_POST['precio_estadia'], $_POST['precio_mensual']);
                            echo $precio->guardar();                          
                        }
                        else
                        {
                            echo "Para agregar un precio debe enviar valor de precio por hora, por estadia y precio mensual";
                        } 
                    }
                    else
                    {
                        echo "Solo un usuario de tipo admin puede cargar un precio";
                    }                  
                }
                else
                {
                    echo ("Error: El token de autenticacion enviado no es valido");
                }               
            }
            else
            {
                echo "Necesita estar logeado para cargar un precio. Enviar token de autenticacion";
            }
        } 
        else if($path_info == '/ingreso')
        {
            if(!(empty($_SERVER['HTTP_TOKEN'])))
            {
                if(User::validarTokenJWT($_SERVER['HTTP_TOKEN']))
                {
                    //Devuelve false si es user
                    if(!(User::validarTipoToken($_SERVER['HTTP_TOKEN'])))
                    {
                        if( !(empty($_POST['patente'])) && !(empty($_POST['tipo'])) )
                        {
                            $fecha_ingreso = "Dia: " . date("d") . " - " . date("H") . "hs";
                            $email = User::obtenerMailUser($_SERVER['HTTP_TOKEN']);

                            $auto = new Auto($_POST['patente'], $fecha_ingreso, $_POST['tipo'], $email);
                            echo $auto->guardar();                        
                        }
                        else
                        {
                            echo "Para hacer un ingreso debe enviar patente y tipo";
                        } 
                        
                    }
                    else
                    {
                        echo "Solo un usuario de tipo user puede hacer un ingreso";
                    }                  
                }
                else
                {
                    echo ("Error: El token de autenticacion enviado no es valido");
                }               
            }
            else
            {
                echo "Necesita estar logeado para hacer un ingreso. Enviar token de autenticacion";
            }
        } 
        break;

    default:
    break;
}


?>