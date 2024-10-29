<?php
session_start();
require "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['idUSUARIO'])) {
    header("location: index.php");
    exit;
}

// Variables de sesión del usuario
$nombre = $_SESSION['nombre'];
$nivel = $_SESSION['nivel'];
$tipo_idtipo = $_SESSION['tipo_idtipo'];
$id_usuario = $_SESSION['idUSUARIO'];

// Consulta para obtener el total de carritos abiertos
$queryCarritosAbiertos = "SELECT COUNT(*) AS total_abiertos FROM carrito WHERE cerrado = 0";
$resultCarritosAbiertos = mysqli_query($mysqli, $queryCarritosAbiertos);
$totalCarritosAbiertos = mysqli_fetch_assoc($resultCarritosAbiertos)['total_abiertos'];

// Consulta para obtener el total de ventas realizadas
$queryTotalVentas = "SELECT SUM(p.precio * c.cantidad) AS total_ventas FROM carrito c JOIN producto p ON c.id_producto = p.id_producto WHERE c.cerrado = 1";
$resultTotalVentas = mysqli_query($mysqli, $queryTotalVentas);
$totalVentas = mysqli_fetch_assoc($resultTotalVentas)['total_ventas'];

// Consulta para obtener los productos más vendidos
$queryProductosMasVendidos = "SELECT p.nombre, SUM(c.cantidad) AS total_vendido FROM carrito c JOIN producto p ON c.id_producto = p.id_producto GROUP BY c.id_producto ORDER BY total_vendido DESC LIMIT 5";
$resultProductosMasVendidos = mysqli_query($mysqli, $queryProductosMasVendidos);
?>

<?php include("template/cabecera.php"); ?>
<main class="container mx-auto px-6 py-24">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">Dashboard de Ventas</h1>

    <!-- Tarjetas de KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 mb-12">
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <h2 class="text-2xl font-bold text-gray-800">Carritos Abiertos</h2>
            <p class="text-4xl font-bold text-indigo-600"><?php echo $totalCarritosAbiertos; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <h2 class="text-2xl font-bold text-gray-800">Total de Ventas</h2>
            <p class="text-4xl font-bold text-indigo-600">$<?php echo number_format($totalVentas, 2); ?></p>
        </div>
    </div>

    <!-- Gráfico de Productos Más Vendidos -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Productos Más Vendidos</h2>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <canvas id="productosMasVendidos"></canvas>
    </div>
</main>

<!-- Librería de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos para el gráfico de Productos Más Vendidos
    var ctx = document.getElementById('productosMasVendidos').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php 
                mysqli_data_seek($resultProductosMasVendidos, 0); // Reiniciar el cursor de resultados
                while ($row = mysqli_fetch_array($resultProductosMasVendidos)) { 
                    echo "'" . $row['nombre'] . "', "; 
                } 
                ?>
            ],
            datasets: [{
                label: 'Cantidad Vendida',
                data: [
                    <?php 
                    mysqli_data_seek($resultProductosMasVendidos, 0); // Reiniciar el cursor para datos
                    while ($row = mysqli_fetch_array($resultProductosMasVendidos)) { 
                        echo $row['total_vendido'] . ", "; 
                    } 
                    ?>
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include("template/pie.php"); ?>
