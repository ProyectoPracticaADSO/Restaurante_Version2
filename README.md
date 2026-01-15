# 🍽️ Proyecto Restaurante – Prácticas ADSO

Proyecto académico desarrollado en PHP bajo el patrón MVC, utilizando MySQL y XAMPP para trabajar en entorno local.  
El objetivo del proyecto es practicar el desarrollo backend, manejo de base de datos y trabajo colaborativo con Git.

---

## 📌 Tecnologías utilizadas

- PHP
- MySQL
- XAMPP
- phpMyAdmin
- Composer
- Git
- HTML / CSS / JavaScript (básico)

---

## 📋 Requisitos previos

- XAMPP
- PHP (incluido en XAMPP)
- MySQL (phpMyAdmin)
- Composer
- Git
- Visual Studio Code (recomendado)

---

## 📁 Ubicación del proyecto

El proyecto debe estar ubicado dentro de:

C:\xampp\htdocs\

## ⚙️ Configuración del entorno local

### 1️⃣ Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>

2️⃣ Configurar la base de datos

Abrir phpMyAdmin

Crear la base de datos del proyecto

Importar el archivo SQL (si aplica)

El proyecto trabaja con MySQL en el puerto 3307(Entorno local)

3️⃣ Configurar conexión a la base de datos

Editar el archivo de conexión:

config/connection.php

Parámetros básicos:

Host: localhost

Puerto: 3307

Usuario: según configuración local

Contraseña: según configuración local

Base de datos: nombre de la base creada

4️⃣ Instalar dependencias PHP

Desde la raíz del proyecto ejecutar:

composer install (Si no tiene instalado COMPOSER realizar la instalación y luego ejecutar el comando mencionado)


Esto generará automáticamente la carpeta vendor.

▶️ Ejecutar el proyecto

Iniciar Apache y MySQL desde XAMPP

Abrir el navegador

Acceder a:

http://localhost/Restaurante_Version2/public

🧪 Verificación

Probar operaciones CRUD desde la aplicación

Confirmar que los cambios se reflejan en phpMyAdmin

Verificar conexión correcta a la base de datos

👥 Trabajo en equipo

No modificar la estructura MVC

No subir la carpeta vendor

No subir archivos con credenciales

Cada desarrollador debe ejecutar composer install

Realizar commits solo cuando una funcionalidad esté funcionando
```

# Validación de permisos en repositorio OK
# Validación de repositorio Yovanny Giraldo
# Validación de repositorio  Clara Maria