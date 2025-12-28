<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encabezado</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        
        .main-header {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 18px;
            background-color: #6b2e0cba;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease-in-out;
            z-index: 1000;
            font-family: 'Arial', sans-serif;
        }

        
        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 45px;
            margin-right: 15px;
        }

        .logo h1 {
            font-size: 24px;
            color: white;
            font-weight: bold;
            letter-spacing: 1px;
        }

        
        .extras {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .extras a, .extras button {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: white;
            transition: color 0.3s;
            text-decoration: none;
            font-weight: normal;
        }

        .extras a:hover, .extras button:hover {
            color: #ffcc00;
        }

        
        .btn-action {
            background-color: #ffcc00;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

    </style>
</head>
<body>
    <!-- Encabezado -->
    <header class="main-header">
        <div class="logo">
            <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo">
            <h1>Restaurant</h1>
        </div>

        <!-- Extras (Botón de soporte) -->
        <div class="extras">
            <a href="view/menus/Soporte.php" class="btn-action">SOPORTE</a>
        </div>
    </header>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const body = document.body;
            
            // Cargar estado del modo oscuro desde LocalStorage
            if (localStorage.getItem("darkMode") === "enabled") {
                body.classList.add("dark-mode");
            }

            // Cambiar entre modo oscuro y claro al hacer clic
            body.addEventListener("click", function () {
                body.classList.toggle("dark-mode");
                
                // Guardar estado en LocalStorage
                if (body.classList.contains("dark-mode")) {
                    localStorage.setItem("darkMode", "enabled");
                } else {
                    localStorage.setItem("darkMode", "disabled");
                }
            });
        });
    </script>
</body>
</html>
