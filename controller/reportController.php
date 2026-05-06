<?php

require_once '../model/report.php';

class ReportController
{
    private $modelo;
    private $db;

    public function __construct($connection)
    {
        $this->db = $connection;
        $this->modelo = new Report($this->db);
    }

    public function index()
    {
        $reporte = new Report($this->db);

        // --- DATOS FINANCIEROS Y KARDEX ---
        // Valor histórico/acumulado del Kardex
        $valorTotalKardex = $reporte->obtenerValorTotalInventario();

        // Valor real de lo que hay en estantes hoy (La nueva función)
        $valorRealHoy = $reporte->obtenerValorRealProductos();

        // Total de ventas del día
        $totalPedidos = $reporte->obtenerTotalPedidosHoy();

        // --- TABLAS Y ALERTAS ---
        // Productos más vendidos para el gráfico o tabla
        $productosTop = $reporte->obtenerProductosMasVendidos();

        // Datos para la tabla de vencimientos
        $datosVencimiento = $reporte->obtenerInventarioConFechas();

        // Alertas de productos con stock bajo (Nueva función para el Dashboard)
        $alertasStock = $reporte->obtenerProductosBajoStock();

        // Finalmente, cargamos la vista que usará todas estas variables
        include '../view/gestionInformes.php';
    }
}

require_once __DIR__ . '/../config/connection.php';

$db = ConnectionDB::connection();

if ($db) {
    $reportController = new ReportController($db);
    $reportController->index();
} else {
    echo "Error: La conexión estática ConnectionDB::connection() devolvió null.";
}
require_once '../view/gestionInformes.php';
