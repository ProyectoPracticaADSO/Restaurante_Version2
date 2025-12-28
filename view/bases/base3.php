<?php
if (!defined('FONTAWESOME_INCLUDED')) {
    define('FONTAWESOME_INCLUDED', true);
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">';
}
?>

<style>
    /* Reset CSS */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    footer {
        padding: 5px 16px;
        background-color: #212121;
        color: #ffffff;
        font-size: 10px;
        width: 100%;
        height: 50px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-top: 1px solid #444;
        position: fixed;
        bottom: 0;
        left: 0;
        box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        font-family: 'Arial', sans-serif;
        
    }

    /* Fila de contacto */
    .contact-info {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px; /* Espacio entre los elementos */
        flex-wrap: wrap; /* Permite que los elementos se acomoden en varias líneas si es necesario */
        max-width: 900px; /* Limitar el ancho máximo */
        
    }

    .contact-info a {
        font-size: 15px;
        color: #f39c12;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: all 0.3s ease-in-out;
         height: 30px;
        
      
    }

    .contact-info a:hover {
        color: #e67e22;
        
      
      

      
    }

    .contact-info a i {
        margin-right: 8px;
        font-size: 16px;
    }

    /* Fila de derechos reservados */
    .footer-copy p {
        font-size: 10px;
        color: #b0b0b0;
        text-align: center;
        white-space: nowrap;
    }

    /* Responsive design para dispositivos pequeños */
    @media (max-width: 768px) {
        footer {
            padding: 8px 12px;
        }

        .contact-info a {
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        footer {
            padding: 6px 10px;
        }

        .contact-info a {
            font-size: 12px;
        }

        .footer-copy {
            font-size: 8px;
        }
    }
</style>

<footer>
   

    <!-- Fila 2: Derechos reservados -->
    <div class="footer-copy">
        <p>&copy; 2025 <strong>JCCODE S.A.S</strong>. Todos los derechos reservados.</p>
    </div>
</footer>
