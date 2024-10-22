<?php
session_start();
require "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data['action'] === 'aceptar') {
        $id_carrito = $data['id_carrito'];
        $id_producto = $data['id_producto'];
        $cantidad = $data['cantidad'];
        
        // Actualizar el stock del producto
        $query = "UPDATE producto SET stock = stock - $cantidad WHERE id_producto = $id_producto";
        mysqli_query($mysqli, $query);

        // Cambiar el estado del pedido en la tabla carrito y cerrarlo
        $query = "UPDATE carrito SET estado = 'aceptado', cerrado = 1 WHERE id_carrito = $id_carrito"; // Asumiendo que 'cerrado' es un campo en la tabla
        mysqli_query($mysqli, $query);

        echo json_encode(['success' => true]);
    } elseif ($data['action'] === 'rechazar') {
        $id_carrito = $data['id_carrito'];
        
        // Cambiar el estado del pedido a rechazado y cerrarlo
        $query = "UPDATE carrito SET estado = 'rechazado', cerrado = 1 WHERE id_carrito = $id_carrito"; // Asumiendo que 'cerrado' es un campo en la tabla
        mysqli_query($mysqli, $query);
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
