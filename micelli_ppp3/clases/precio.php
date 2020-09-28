<?php

class Precio
{
    public $precio_hora;
    public $precio_estadia;
    public $precio_mensual;
    const nombreArchivo = "../micelli_ppp3/archivos/precios.json";

    public function __construct($precio_hora, $precio_estadia, $precio_mensual)
    {
        $this->precio_hora = $precio_hora;
        $this->precio_estadia = $precio_estadia;
        $this->precio_mensual = $precio_mensual;
    }

    public function guardar()
    {
        $retorno = "";

        $exito = Precio::guardarJsonPrecio(Precio::nombreArchivo, $this);

        if ($exito != 0 && $exito != false) {
            $retorno = "Precio guardado con exito<br/>";
        } else {
            $retorno = "Ocurrio un error al guardar el precio<br/>";
        }

        return $retorno;
    }

    //Devuelve cantidad de bytes escritos o false si hubo un error
    public static function guardarJsonPrecio($nombreArchivo, $precio)
    {
        $arrayJson = [];

        array_push($arrayJson, $precio);

        $archivo = fopen($nombreArchivo, "w");

        $fwrite = fwrite($archivo, json_encode($arrayJson));

        $fclose =  fclose($archivo);

        return $fwrite;
    }
}
