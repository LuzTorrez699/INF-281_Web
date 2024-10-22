<?php
session_start();
require "conexion.php";

if (!isset($_SESSION['idUSUARIO'])) {
    header("location: index.php");
}

$nombre = $_SESSION['nombre'];
$nivel = $_SESSION['nivel'];
$tipo_idtipo = $_SESSION['tipo_idtipo'];
$id_usuario = $_SESSION['idUSUARIO'];

// Obtener todos los carritos abiertos del usuario
$query = "SELECT c.id_carrito, p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS total
          FROM carrito c
          JOIN producto p ON c.id_producto = p.id_producto
          WHERE c.id_usuario = $id_usuario AND c.cerrado = 0"; // Solo carritos abiertos

$result = mysqli_query($mysqli, $query);

// Verificar si hay resultados
if (!$result || mysqli_num_rows($result) == 0) {
    echo "No hay carritos para mostrar.";
    exit;
}

// Cerrar todos los carritos abiertos del usuario
$updateQuery = "UPDATE carrito SET cerrado = 1 WHERE id_usuario = $id_usuario AND cerrado = 0";
mysqli_query($mysqli, $updateQuery);

?>

<?php include("template/cabecera.php"); ?>
<main class="container mx-auto px-6 py-24">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">Pago Exitoso</h1>
    
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Detalles de tu Compra</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php 
        $totalGeneral = 0; // Variable para acumular el total general
        while ($row = mysqli_fetch_array($result)) { 
            $totalGeneral += $row['total']; // Acumular el total
        ?>
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                <h3 class="font-semibold text-xl mb-2"><?php echo $row['nombre']; ?></h3>
                <p class="text-gray-600 mb-4">Cantidad: <?php echo $row['cantidad']; ?></p>
                <p class="text-xl font-bold text-indigo-600">Total: $<?php echo number_format($row['total'], 2); ?></p>
            </div>
        <?php } ?>
    </div>
    
    <div class="mt-6">
        <h3 class="text-xl font-bold text-gray-800">Total General: $<?php echo number_format($totalGeneral, 2); ?></h3>
    </div>
    
    <div class="mt-6">
        <a href="mostra_producto.php" class="btn btn-primary">Pagar</a>
    </div>
</main>

<?php include("template/pie.php"); ?>
