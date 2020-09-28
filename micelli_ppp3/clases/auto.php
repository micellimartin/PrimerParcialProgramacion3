<?php

class Auto
{
    public $patente;
    public $fecha_ingreso;
    public $tipo;
    public $email;
    const nombreArchivo = "../micelli_ppp3/archivos/autos.json";

    public function __construct($patente, $fecha_ingreso, $tipo, $email)
    {
        $this->patente = $patente;
        $this->fecha_ingreso = $fecha_ingreso;
        $this->tipo = $tipo;
        $this->email = $email;
    }

    public function guardar()
    {
        $retorno = "";

        $exito = Archivo::guardarJson(Auto::nombreArchivo, $this);

        if ($exito != 0 && $exito != false) {
            $retorno = "Auto guardado con exito<br/>";
        } else {
            $retorno = "Ocurrio un error al guardar el auto<br/>";
        }

        return $retorno;
    }
}

?>