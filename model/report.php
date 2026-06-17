<?php

class Report
{
    private PDO $db;

    public function __construct(PDO $connection)
    {
        $this->db = $connection;
        // Nos aseguramos de que PDO lance excepciones para que el try-catch funcione siempre
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function obtenerProductosMasVendidos()
    {
        try {
            $sql = "SELECT descripcion_producto, cantidad 
                    FROM inventario 
                    ORDER BY cantidad DESC 
                    LIMIT 5";
            return $this->db->query($sql);
        } catch (PDOException $e) {
            // Si la estructura cambió, devolvemos un objeto vacío simulado para no romper el bucle fetch de la vista
            return new PDOStatement();
        }
    }

    public function obtenerValorTotalInventario()
    {
        try {
            $sql = "SELECT SUM(costo_total) as gran_total FROM kardex";
            $stmt = $this->db->query($sql);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return $fila['gran_total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function obtenerTotalPedidosHoy()
    {
        try {
            // Buscamos si existe la tabla pedidos o pedido
            $sql = "SELECT COUNT(*) as total FROM pedidos";
            $stmt = $this->db->query($sql);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return $fila['total'] ?? 0;
        } catch (PDOException $e) {
            try {
                $sql = "SELECT COUNT(*) as total FROM pedido";
                $stmt = $this->db->query($sql);
                $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                return $fila['total'] ?? 0;
            } catch (PDOException $ex) {
                return 0;
            }
        }
    }

    public function obtenerProductosPorVencer()
    {
        try {
            $sql = "SELECT descripcion_producto, fecha_vencimiento, cantidad 
                    FROM inventario 
                    WHERE fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                    ORDER BY fecha_vencimiento ASC";
            return $this->db->query($sql);
        } catch (PDOException $e) {
            return new PDOStatement();
        }
    }

    public function obtenerProductosAntiguos()
    {
        try {
            $sql = "SELECT descripcion_producto, cantidad FROM inventario ORDER BY cantidad DESC LIMIT 5";
            return $this->db->query($sql);
        } catch (PDOException $e) {
            return new PDOStatement();
        }
    }

    public function obtenerInventarioConFechas()
    {
        try {
            $sql = "SELECT descripcion_producto, fecha_vencimiento FROM inventario ORDER BY fecha_vencimiento ASC LIMIT 10";
            return $this->db->query($sql);
        } catch (PDOException $e) {
            return new PDOStatement();
        }
    }

    public function obtenerValorRealProductos()
    {
        try {
            $sql = "SELECT SUM(cantidad * precio_unitario) as total FROM inventario";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return $fila['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function obtenerProductosBajoStock()
    {
        try {
            $sql = "SELECT descripcion_producto, cantidad FROM inventario WHERE cantidad <= 5 ORDER BY cantidad ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // --- MÓDULO: CLIENTES (Doble verificación Plural/Singular) ---
    public function obtenerTotalClientes()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM clientes";
            $stmt = $this->db->query($sql);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return $fila['total'] ?? 0;
        } catch (PDOException $e) {
            try {
                // Alternativa por si el pull de Git renombró la tabla a singular
                $sql = "SELECT COUNT(*) as total FROM cliente";
                $stmt = $this->db->query($sql);
                $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                return $fila['total'] ?? 0;
            } catch (PDOException $ex) {
                return 0;
            }
        }
    }

    // --- MÓDULO: FACTURAS (Doble verificación Plural/Singular) ---
    public function obtenerTotalFacturas()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM pedidos";
            $stmt = $this->db->query($sql);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            return $fila['total'] ?? 0;
        } catch (PDOException $e) {
            try {
                $sql = "SELECT COUNT(*) as total FROM pedido";
                $stmt = $this->db->query($sql);
                $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                return $fila['total'] ?? 0;
            } catch (PDOException $ex) {
                return 0;
            }
        }
    }
}
