<?php

class Archivo
{
    //Devuelve un array de objetos stdClass
    public static function leerJson($nombreArchivo)
    {
        $archivo = fopen($nombreArchivo, "r");

        //Leo el archivo completo. Le paso de donde voy a leer y que tanto voy a leer.
        $fread = fread($archivo, filesize($nombreArchivo));

        $fclose =  fclose($archivo);

        //Como leo un archivo en Json, ahora le tengo que decodificar. Lo paso a un array de Json
        $arrayJson = json_decode($fread);

        return $arrayJson;
    }

    //Devuelve cantidad de bytes escritos o false si hubo un error
    public static function guardarJson($nombreArchivo, $entidad)
    {
        $arrayJson = Archivo::leerJson($nombreArchivo);

        array_push($arrayJson, $entidad);

        $archivo = fopen($nombreArchivo, "w");

        $fwrite = fwrite($archivo, json_encode($arrayJson));

        $fclose =  fclose($archivo);

        return $fwrite;
    }
}
