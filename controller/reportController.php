<?php
class ReportController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index()
    {
        // 1. Inicializamos todas las variables para evitar el error de "Undefined variable"
        $valorTotalKardex = 0;
        $valorRealHoy = 0;
        $totalPedidos = 0;
        $totalClientes = 0;
        $totalFacturas = 0;
        $productosTop = null;
        $datosVencimiento = null;

        // 2. Instanciamos el modelo de reportes de manera segura
        if (class_exists('Report')) {
            $reporte = new Report($this->db);

            // Carga de datos del Kardex e Inventario
            $valorTotalKardex = method_exists($reporte, 'obtenerValorTotalInventario') ? $reporte->obtenerValorTotalInventario() : 0;
            $valorRealHoy = method_exists($reporte, 'obtenerValorRealProductos') ? $reporte->obtenerValorRealProductos() : 0;
            $totalPedidos = method_exists($reporte, 'obtenerTotalPedidosHoy') ? $reporte->obtenerTotalPedidosHoy() : 0;

            // Carga de Clientes y Facturas
            $totalClientes = method_exists($reporte, 'obtenerTotalClientes') ? $reporte->obtenerTotalClientes() : 0;
            $totalFacturas = method_exists($reporte, 'obtenerTotalFacturas') ? $reporte->obtenerTotalFacturas() : 0;

            // Listados de Tablas
            $productosTop = method_exists($reporte, 'obtenerProductosMasVendidos') ? $reporte->obtenerProductosMasVendidos() : null;
            $datosVencimiento = method_exists($reporte, 'obtenerInventarioConFechas') ? $reporte->obtenerInventarioConFechas() : null;
        }

        // 3. Definimos el título e incluimos la vista de manera limpia
        $tituloPagina = "Dashboard de Informes Administrativos";
        include __DIR__ . '/../view/gestionInformes.php';
    }
}

// --- EJECUCIÓN AUTOMÁTICA DEL FLUJO MVC ---
require_once __DIR__ . '/../config/connection.php';
$db = ConnectionDB::connection();

if ($db) {
    // Si el modelo no se ha cargado en el index global, lo incluimos de forma relativa
    if (!class_exists('Report') && file_exists(__DIR__ . '/../model/report.php')) {
        require_once __DIR__ . '/../model/report.php';
    }
    $controller = new ReportController($db);
    $controller->index();
} else {
    echo "Error crítico: No se pudo establecer la conexión a la base de datos.";
}
