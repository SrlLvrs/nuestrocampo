<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

// Obtener la fecha del parámetro GET
$fechaBusqueda = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

// Modificar la consulta SQL para usar DATE() en la comparación
$query = "SELECT p.ID,
            p.IDPA,
            p.IDCliente,
            c.Nombre,
            c.Direccion,
            s.NombreSector,
            s.Comuna,
            c.LinkMaps,
            s.IDRepartidor,
            u.Username AS NombreRepartidor,
            p.Estado,
            p.Pagado,
            p.MedioPago,
            COALESCE(pa.Observacion, c.Observacion) AS Observacion,
            DATE_FORMAT(p.FechaEntrega, '%d/%m/%Y') AS FechaEntrega,
            DATE_FORMAT(p.HoraCreacion, '%d/%m/%Y %H:%i') AS HoraCreacion,
            DATE_FORMAT(p.HoraCierre, '%d/%m/%Y %H:%i') AS HoraCierre
          FROM pedidos p
          JOIN clientes c ON p.IDCliente = c.ID
          JOIN sector s ON c.IDSector = s.ID
          JOIN usuarios u ON s.IDRepartidor = u.ID
          LEFT JOIN pedidos_automaticos pa ON p.IDPA = pa.ID
          WHERE p.Visible = 1
            AND p.Estado != 'Pendiente'
            AND DATE(p.FechaEntrega) = :fechaBusqueda
          ORDER BY p.FechaEntrega DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':fechaBusqueda', $fechaBusqueda, PDO::PARAM_STR);
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
    echo json_encode(array("message" => "No se encontraron pedidos para la fecha seleccionada"));
    exit();
}
