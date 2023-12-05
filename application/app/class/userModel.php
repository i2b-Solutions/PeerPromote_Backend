<?php

require_once "config/conexion.php";
class Usuario extends Conexion
{
    private $id_usuario;
    private $nombre;
    private $usuario;
    private $pass;
    private $telefono;
    private $apellido;
    private $id_tipo_usuario;
    private $estado;

    public function __construct()
    {
        parent::__construct(); //Llamada al constructor de la clase padre

        $this->id_usuario = "";
        $this->nombre = "";
        $this->usuario = "";
        $this->pass = "";
        $this->telefono = "";
        $this->apellido = "";
        $this->id_tipo_usuario = "";
        $this->estado = "";
    }



    public function getId_usuario()
    {
        return $this->id_usuario;
    }

    public function setId_usuario($id)
    {
        $this->id_usuario = $id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass)
    {
        $password = hash('sha256', $pass);
        $this->pass = $password;
    }
    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    public function getId_tipo_usuario()
    {
        return $this->id_tipo_usuario;
    }

    public function setId_tipo_usuario($id_tipo_usuario)
    {
        $this->id_tipo_usuario = $id_tipo_usuario;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    //-------------------------------------------------------------------------------- Funciones --------------------------------------------------------------------------------//
public function saveUsr($empleo)
    {
        $query = "INSERT INTO usuario (id_usuario,usuario,pass,nombre,apellido,id_tipo_usuario,created_at)
                  values(NULL,'" . $this->usuario . "','" . $this->pass . "','" . $this->nombre . "','" . $this->apellido . "','$empleo',NOW());";
        $save = $this->db->query($query);
        $_SESSION['mensaje'] = $this->db->error;
        if ($save == true) {
            return true;
        } else {

            return false;
        }
    }
    public function updateUsr($empleo)
    {
        $query = "UPDATE usuario SET usuario = '" . $this->usuario . "',nombre='" . $this->nombre . "',apellido='" . $this->apellido . "',id_tipo_usuario= '$empleo' WHERE id_usuario = $this->id_usuario;";
        $save = $this->db->query($query);
        $_SESSION['mensaje'] = $this->db->error;
        if ($save == true) {
            return true;
        } else {

            return false;
        }
    }
public function login()
    {
        $query1 = "SELECT u.*, tu.tipo_usuario as tipo, a.id_perfil FROM usuario u INNER JOIN tipo_usuario tu ON tu.id_tipo_usuario=u.id_tipo_usuario LEFT JOIN perfil a ON u.id_usuario=a.id_usuario WHERE u.usuario='" . $this->usuario . "' AND u.pass='" . $this->pass . "'";
        $selectall1 = $this->db->query($query1);
        $ListUsuario = $selectall1->fetch_all(MYSQLI_ASSOC);

        if ($selectall1->num_rows != 0 ) {
            foreach ($ListUsuario as $key) {
                if ($key['estado'] == 'Activo') {
                    session_start();
                    $_SESSION['logged-in'] = true;
                    $_SESSION['Usuario'] = $key['usuario'];
                    $_SESSION['id_usuario'] = $key['id_usuario'];
                    $_SESSION['tipo'] = $key['id_tipo_usuario'];
                    $_SESSION['id_perfil'] = $key['id_perfil'];
                    $_SESSION['tiempo'] = time();
                    $_SESSION['acceso'] = '';
                }else {
                    session_start();
                    $_SESSION['logged-in'] = false;
                    $_SESSION['tiempo'] = 0;
                    return false;
                }
            }
            return true;
        } else {
            session_start();
            $_SESSION['logged-in'] = false;
            $_SESSION['tiempo'] = 0;
            return false;
        }
    }
public function save($empleo)
    {
        $query = "INSERT INTO usuario (id_usuario,usuario,pass,id_tipo_usuario,telefono,created_at)
              values(NULL,'" . $this->usuario . "','" . $this->pass . "',3,'" . $this->telefono . "',NOW());";
        $save = $this->db->query($query);

        $_SESSION['mensaje'] = $this->db->error;

        if ($save == true) {
            return true;
        } else {

            return false;
        }
    }

public function delete()
    {
        $query = "DELETE FROM usuario WHERE id_usuario='" . $this->id_usuario . "'";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }


    public function selectOne($codigo)
    {
        $query = "SELECT * FROM usuario WHERE id_usuario=" . $codigo . "";
        $selectall = $this->db->query($query);
        $ListUsuario = $selectall->fetch_all(MYSQLI_ASSOC);
        return $ListUsuario;
    }

    //------------------------------------------------------------------//
    public function selectTipoUsuario()
    {
        $query = "SELECT * FROM tipo_usuario";
        $selectall = $this->db->query($query);
        $ListUsuario = $selectall->fetch_all(MYSQLI_ASSOC);
        return $ListUsuario;
    }

    public function updateStatus()
    {
        $query = "UPDATE usuario SET estado='$this->estado' WHERE id_usuario=" . $this->id_usuario . "";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }

}
