<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';

$database = new Database();
$db = $database->getConnection();

/*
Obtener los parámetros por url
//CLIENTES: ID, Nombre, Direccion, IDSector, Telefono, Telefono2, LinkMaps, Frecuencia, DiaRepartoExcepcional, Observacion, Preferencia, Visible
*/
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$direccion = isset($_GET['direccion']) ? $_GET['direccion'] : '';
$idsector = isset($_GET['idsector']) ? $_GET['idsector'] : '';
$telefono = isset($_GET['telefono']) ? $_GET['telefono'] : '';
$telefono2 = isset($_GET['telefono2']) ? $_GET['telefono2'] : '';
$linkmaps = isset($_GET['linkmaps']) ? $_GET['linkmaps'] : '';
$frecuencia = isset($_GET['frecuencia']) ? $_GET['frecuencia'] : '';
$observacion = isset($_GET['observacion']) ? $_GET['observacion'] : '';
$preferencia = isset($_GET['preferencia']) ? $_GET['preferencia'] : '';
$dre = isset($_GET['dre']) ? $_GET['dre'] : NULL;

//Comprueba que las variables NO estén vacías
if (!empty($nombre) /*&& !empty($direccion) && !empty($idsector) && !empty($telefono)*/) {
    $query = "INSERT INTO clientes 
            SET Nombre=:n, Direccion=:d, IDSector=:ids, Telefono=:t, Telefono2=:t2, LinkMaps=:l, Frecuencia=:f, Observacion=:o, Preferencia=:p, DiaRepartoExcepcional=:dre";

    $stmt = $db->prepare($query);

    //Bindear las variables usando bindParam
    $stmt->bindParam(":n", $nombre);
    $stmt->bindParam(":d", $direccion);
    $stmt->bindParam(":ids", $idsector);
    $stmt->bindParam(":t", $telefono);
    $stmt->bindParam(":t2", $telefono2);
    $stmt->bindParam(":l", $linkmaps);
    $stmt->bindParam(":f", $frecuencia);
    $stmt->bindParam(":o", $observacion);
    $stmt->bindParam(":p", $preferencia);
    $stmt->bindParam(":dre", $dre);
    
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "El usuario fue creado."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo crear el usuario. Revisa el log."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "El nombre está vacío"));
}