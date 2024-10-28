<?php
// VISTA: ClienteDetalle.vue
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

//Parámetros por URL: id de cliente
$id = isset($_GET['id']) ? $_GET['id'] : ''; // NOT NULL

// Se prepara la consulta SQL para seleccionar pedidos sin pagar.
$query = "  SELECT
                p.ID,
                p.IDPA,
                p.IDCliente,
                s.IDRepartidor,
                u.Username AS NombreRepartidor,
                p.Estado,
                p.Pagado,
                p.MedioPago,
                DATE_FORMAT(p.HoraCierre, '%H:%i - %d/%m/%Y') AS FechaEntrega
            FROM
                pedidos p
                JOIN clientes c ON p.IDCliente = c.ID
                JOIN sector s ON c.IDSector = s.ID
                JOIN usuarios u ON s.IDRepartidor = u.ID
            WHERE
                p.IDCliente = :id
                AND p.Estado = 'Entregado'
                AND p.Pagado = 'No'";

$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);

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
    echo json_encode(array("message" => "No se encontraron pedidos"));
    exit();
}