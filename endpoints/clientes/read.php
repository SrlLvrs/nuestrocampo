<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Consulta para obtener info de clientes
$query = "  SELECT 
                c.ID, 
                c.Nombre, 
                c.Direccion, 
                c.IDSector, 
                s.NombreSector, 
                s.Comuna, 
                c.Telefono, 
                c.Telefono2, 
                c.LinkMaps, 
                c.Observacion, 
                COALESCE(c.DiaRepartoExcepcional, s.DiaReparto) AS diaDeReparto, 
                COALESCE(pa.Frecuencia, 'Ninguna') AS Freq
            FROM 
                clientes c
            JOIN 
                sector s ON c.IDSector = s.ID
            LEFT JOIN 
                pedidos_automaticos pa ON c.ID = pa.IDCliente AND pa.Visible = 1
            WHERE 
                c.Visible = 1
            ORDER BY 
                c.ID DESC";

$stmt = $db->prepare($query);

// Se ejecuta la consulta.
$stmt->execute();

// Array para almacenar resultados
$resultados = array();

// Recorrer todos los registros obtenidos
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultados[] = $row;
}

// Verifica si se encontraron registros
if (count($resultados) > 0) {
    http_response_code(200);
    echo json_encode($resultados);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No se encontraron clientes"));
    exit();
}