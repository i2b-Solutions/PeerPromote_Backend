<?php

require_once "config/conexion.php";
//* nuevos campos 
// UserID	Username	PasswordHash	Email	Blocked	IsCompany
class User extends Conexion
{
    private $id_user;
    private $name;
    private $user;
    private $pass;
    private $email;
    private $id_type;
    private $estado;

    public function __construct()
    {
        parent::__construct(); //Llamada al constructor de la clase padre

        $this->id_user = "";
        $this->name = "";
        $this->user = "";
        $this->pass = "";
        $this->email = "";
        $this->id_type = "";
        $this->estado = "";
    }



    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($id)
    {
        $this->id_user = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass)
    {
        $secret_key="i2b_solutions_dev";
        #$password = hash('sha256', $pass,False);
        $password = hash_hmac('sha256', $pass,$secret_key);
        $this->pass = $password;
    }
    public function getId_type()
    {
        return $this->id_type;
    }

    public function setId_type($id_type)
    {
        $this->id_type = $id_type;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
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
        $query = "INSERT INTO user (id,user,password,name,id_type,created_at)
                  values(NULL,'" . $this->user . "','" . $this->pass . "','" . $this->name . "',$empleo,NOW());";
        $save = $this->db->query($query);
        $_SESSION['mensaje'] = $this->db->error;
        if ($save == true) {
            $respon = array();
            $respon['error']='false';
            $respon['message']='¡Usuario Creado con Exito!';
            $respon['request']="";
            $respon['UserID']=$this->db->insert_id;
            return $respon;
        } else {
            
            $respon = array();
            $respon['error']='true';
            $respon['message']='¡Usuario Error al guardar, verifica la información proporcionada e intenta de nuevo!';
            $respon['request']=$this->db->error;
            return $respon;
            return false;
        }
    }
    public function updateUsr($empleo)
    {
        $query = "UPDATE user SET user = '" . $this->user . "',name='" . $this->name . "',id_type= '$empleo' WHERE id_user = $this->id_user;";
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
        $query1 = "SELECT u.*, tu.tipo FROM user u INNER JOIN user_type tu ON tu.id=u.id_type WHERE u.user='" . $this->user . "' AND u.password='" . $this->pass . "'";
        $selectall1 = $this->db->query($query1);
        $ListUser = $selectall1->fetch_all(MYSQLI_ASSOC);

        if ($selectall1->num_rows != 0 ) {
            foreach ($ListUser as $key) {
                    session_start();
                    $_SESSION['logged-in'] = true;
                    $_SESSION['User'] = $key['user'];
                    $_SESSION['id_user'] = $key['id'];
                    $_SESSION['tipo'] = $key['id_type'];
                    $_SESSION['tiempo'] = time();
                    $_SESSION['acceso'] = '';
                    
                  
           
            } 
            $respon = array();
            $respon['error']='false';
            $respon['message']='Bienvenido de nuevo.';
            $respon['request']=$ListUser;
            return $respon;
        } else {
            session_start();
            $_SESSION['logged-in'] = false;
            $_SESSION['tiempo'] = 0;
            $respon = array();
            $respon['error']=True;
            $respon['message']='Error al iniciar sesion consulte con su proveedor.'.$this->db->error;;
            $respon['request']=$ListUser;
            return $respon;
        }
    }
public function save($empleo)
    {
        $query = "INSERT INTO user (id_user,user,pass,id_type,email,created_at)
              values(NULL,'" . $this->user . "','" . $this->pass . "',3,'" . $this->email . "',NOW());";
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
        $query = "DELETE FROM user WHERE id_user='" . $this->id_user . "'";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }


    public function selectOne($codigo)
    {
        $query = "SELECT * FROM user WHERE id_user=" . $codigo . "";
        $selectall = $this->db->query($query);
        $ListUser = $selectall->fetch_all(MYSQLI_ASSOC);
        return $ListUser;
    }

    public function selectAll()
    {
        $query = "SELECT * FROM user";
        $selectall = $this->db->query($query);
        $ListUser = $selectall->fetch_all(MYSQLI_ASSOC);
        $respon = array();
                $respon['error']='false';
                $respon['message']='Usuarios';
                $respon['request']=$ListUser;
                return $respon;

    }
    //------------------------------------------------------------------//
    public function selectTipoUser()
    {
        $query = "SELECT * FROM type";
        $selectall = $this->db->query($query);
        $ListUser = $selectall->fetch_all(MYSQLI_ASSOC);
        return $ListUser;
    }

    public function updateStatus()
    {
        $query = "UPDATE user SET estado='$this->estado' WHERE id_user=" . $this->id_user . "";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }

}
