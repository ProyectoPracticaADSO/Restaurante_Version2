<?php
require_once "../config/connection.php";
class User extends ConnectionDB
{


  public function insertUser($nombre, $tipoDocumento, $numero, $numero_telefono, $correo, $contrasena, $perfil, $estado)
  {
    $sql = "INSERT INTO usuarios (nombre_usuario, cedula_usuario, numero_usuario, numero_telefono ,correo_usuario, contraseña_usuario, fk_id_perfil, fk_id_estado) 
        VALUES ( :nombre, :tipoDocumento, :numero, :numero_telefono, :correo, :contrasena, :perfil, :estado)";

    $query = parent::connection()->prepare($sql);
    // $query->bindParam(':id', $id);
    $query->bindParam(':nombre', $nombre);
    $query->bindParam(':tipoDocumento', $tipoDocumento);
    $query->bindParam(':numero', $numero);
    $query->bindParam(':numero_telefono', $numero_telefono);
    $query->bindParam(':correo', $correo);
    $query->bindParam(':contrasena', $contrasena);
    $query->bindParam(':perfil', $perfil);
    $query->bindParam(':estado', $estado);

    return $query->execute();
  }
  public function guardarToken($correo, $token, $expira)
  {
    $sql = "UPDATE usuarios SET token_recuperacion = :token, token_expiracion = :expira WHERE correo_usuario = :correo";
    $query = parent::connection()->prepare($sql);
    $query->bindParam(':token', $token);
    $query->bindParam(':expira', $expira);
    $query->bindParam(':correo', $correo);
    return $query->execute();
  }

  public function verificarToken($token)
  {
    $sql = "SELECT * FROM usuarios WHERE token_recuperacion = :token AND token_expiracion > NOW()";
    $query = parent::connection()->prepare($sql);
    $query->bindParam(':token', $token);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC) !== false;
  }

  public function actualizarContrasenaConToken($token, $nuevaContrasena)
  {
    $sql = "UPDATE usuarios SET contraseña_usuario = :nuevaContrasena, token_recuperacion = NULL, token_expiracion = NULL WHERE token_recuperacion = :token";
    $query = parent::connection()->prepare($sql);
    $query->bindParam(':nuevaContrasena', $nuevaContrasena);
    // $query->bindParam(':nuevaContrasena', password_hash($nuevaContrasena, PASSWORD_BCRYPT));
    $query->bindParam(':token', $token);
    return $query->execute();
  }
  public function existCorreo($correo)
  {
    $sql = "SELECT COUNT(*) AS count FROM usuarios WHERE correo_usuario = :correo";
    $query = parent::connection()->prepare($sql);
    $query->bindParam(':correo', $correo);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result['count'] > 0;
  }
  public function getUser()
  {
    $query = parent::connection()->prepare("SELECT u.id, u.nombre_usuario, u.cedula_usuario, u.numero_usuario, u.numero_telefono ,u.correo_usuario, p.nombre_perfil, e.nombre_estado
        FROM usuarios u
        JOIN perfiles p ON u.fk_id_perfil = p.id
        JOIN estados e ON u.fk_id_estado = e.id");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUserById($id)
  {
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public function existCedula($cedula)
  {
    $sql = "SELECT COUNT(*) AS count FROM usuarios WHERE cedula_usuario = :cedula";
    $query = parent::connection()->prepare($sql);
    $query->bindParam(':cedula', $cedula);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result['count'] > 0;
  }

  public function deleteUser($id)
  {
    $sql = "DELETE FROM usuarios WHERE id = :id";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);

    return $query->execute();
  }

  public function updateUser($id, $nombre, $tipoDocumento, $numero, $numero_telefono, $correo, $contrasena, $perfil, $estado)
  {
    $sql = "UPDATE usuarios SET 
                nombre_usuario = :nombre, 
                cedula_usuario = :tipoDocumento, 
                numero_usuario = :numero, 
                numero_telefono = :numero_telefono,
                correo_usuario = :correo, 
                contraseña_usuario = :contrasena, 
                fk_id_perfil = :perfil, 
                fk_id_estado = :estado
            WHERE id = :id";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);
    $query->bindParam(':nombre', $nombre);
    $query->bindParam(':tipoDocumento', $tipoDocumento);
    $query->bindParam(':numero', $numero);
    $query->bindParam(':numero_telefono', $numero_telefono);
    $query->bindParam(':correo', $correo);
    $query->bindParam(':contrasena', $contrasena);
    $query->bindParam(':perfil', $perfil);
    $query->bindParam(':estado', $estado);

    return $query->execute();
  }
}
