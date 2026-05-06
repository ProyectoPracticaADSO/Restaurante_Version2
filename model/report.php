<?php

class Report
{
    private $db;

    public function __construct($connection)
    {
        $this->db = $connection;
    }

    public function obtenerProductosMasVendidos()
    {
        $sql = "SELECT descripcion_producto, cantidad 
                FROM inventario 
                ORDER BY cantidad DESC 
                LIMIT 5";
        return $this->db->query($sql);
    }

    public function obtenerValorTotalInventario()
    {
        $sql = "SELECT SUM(costo_total) as gran_total FROM kardex";
        $stmt = $this->db->query($sql);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila['gran_total'] ?? 0;
    }

    public function obtenerTotalPedidosHoy()
    {
        $sql = "SELECT COUNT(*) as total FROM pedidos";
        $stmt = $this->db->query($sql);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila['total'] ?? 0;
    }

    public function obtenerProductosPorVencer()
    {
        // Busca productos que venzan en los próximos 7 días
        $sql = "SELECT descripcion_producto, fecha_vencimiento, cantidad 
            FROM inventario 
            WHERE fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
            ORDER BY fecha_vencimiento ASC";
        return $this->db->query($sql);
    }

    public function obtenerProductosAntiguos()
    {
        $sql = "SSELECT descripcion_producto, cantidad FROM inventario ORDER BY cantidad DESC LIMIT 5";
        return $this->db->query($sql);
    }

    public function obtenerInventarioConFechas()
    {
        $sql = "SELECT descripcion_producto, fecha_vencimiento FROM inventario ORDER BY fecha_vencimiento ASC LIMIT 10";
        return $this->db->query($sql);
    }

    public function obtenerValorRealProductos()
    {
        $sql = "SELECT SUM(cantidad * precio_unitario) as total FROM inventario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila['total'] ?? 0;
    }

    public function obtenerProductosBajoStock()
    {
        $sql = "SELECT descripcion_producto, cantidad FROM inventario WHERE cantidad <= 5 ORDER BY cantidad ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
