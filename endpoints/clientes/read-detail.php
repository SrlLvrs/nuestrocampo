<?php
// Estos encabezados permiten el acceso a la API desde cualquier origen y especifican que el contenido de la respuesta será JSON.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Se incluye el archivo que contiene la clase para la conexión a la base de datos.
include_once '../config/db.php';

// Se instancia la base de datos y se obtiene la conexión.
$database = new Database();
$db = $database->getConnection();

//Parámetros por URL: id de pedido
$id = isset($_GET['id']) ? $_GET['id'] : ''; // NOT NULL

// Verifica si el ID no está vacío
if (!empty($id)) {
    // Consulta SQL modificada
    $query = "  SELECT c.ID AS IDCliente,
                    c.Nombre,
                    c.Direccion,
                    c.IDSector,
                    s.NombreSector,
                    s.Comuna,
                    pa.Frecuencia,
                    c.Telefono,
                    c.Telefono2,
                    c.LinkMaps,
                    COALESCE(c.DiaRepartoExcepcional, s.DiaReparto) AS Dia_de_Reparto,
                    c.Observacion,
                    CASE
                        WHEN EXISTS
                                (SELECT 1
                                FROM pedidos
                                WHERE IDCliente = c.ID
                                    AND Pagado = 'No'
                                    AND Estado = 'Entregado' ) THEN 'Tiene deuda'
                        ELSE NULL
                    END AS Deuda
                FROM clientes c
                JOIN sector s ON c.IDSector = s.ID
                LEFT JOIN pedidos_automaticos pa ON pa.IDCliente = c.ID
                WHERE c.id = :id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Array para almacenar los resultados
    $resultados = array();

    // Recorrer todos los registros obtenidos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $row;
    }

    // Verifica si se encontraron registros
    if(count($resultados) > 0) {
        echo json_encode($resultados);
    } else {
        echo json_encode(array("message" => "No se encontró ningún registro con el ID proporcionado."));
    }
} else {
    // Manejo de error: id no proporcionado
    echo json_encode(array("message" => "ID no proporcionado."));
    exit();
}
?>
