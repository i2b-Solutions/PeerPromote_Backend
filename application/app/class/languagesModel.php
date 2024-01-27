<?php

require_once "config/conexion.php";
//* nuevos campos 
// LangIDID	LangIDPersonID	PasswordHash	Email	Blocked	IsCompany
class Languages extends Conexion
{
    private $LanguageID;
    private $PersonID;
    private $LangID;

    public function __construct()
    {
        parent::__construct(); //Llamada al constructor de la clase padre

        $this->LanguageID = "";
        $this->PersonID = "";
        $this->LangID = "";
    }



    public function getLanguageID()
    {
        return $this->LanguageID;
    }

    public function setLanguageID($id)
    {
        $this->LanguageID = $id;
    }

    public function getPersonID()
    {
        return $this->PersonID;
    }

    public function setPersonID($PersonID)
    {
        $this->PersonID = $PersonID;
    }

    public function getLangID()
    {
        return $this->LangID;
    }

    public function setLangID($LangID)
    {
        $this->LangID = $LangID;
    }
    //-------------------------------------------------------------------------------- Funciones --------------------------------------------------------------------------------//
public function save()
    {
        $query = "INSERT INTO Languages (LanguageID,PersonID,LangID)
                  values(NULL,'" . $this->PersonID . "'," . $this->LangID . ");";
        $save = $this->db->query($query);
        $_SESSION['mensaje'] = $this->db->error;
        if ($save == true) {
            $respon = array();
            $respon['error']='false';
            $respon['request']="";
            $respon['LanguageID']=$this->db->insert_id;
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
        $query = "UPDATE Languages SET LangID = '" . $this->LangID . "',PersonID='" . $this->PersonID . "',id_type= '$empleo' WHERE LanguageID = $this->LanguageID;";
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
        $query = "DELETE FROM Languages WHERE LanguageID='" . $this->LanguageID . "'";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }


    public function selectOne($codigo)
    {
        $query = "SELECT * FROM Languages WHERE LanguageID=" . $codigo . "";
        $selectall = $this->db->query($query);
        $ListLangID = $selectall->fetch_all(MYSQLI_ASSOC);
        return $ListLangID;
    }

    public function selectAll()
    {
        $query = "SELECT * FROM Languages";
        $selectall = $this->db->query($query);
        $ListLangID = $selectall->fetch_all(MYSQLI_ASSOC);
        $respon = array();
                $respon['error']='false';
                $respon['message']='Usuarios';
                $respon['request']=$ListLangID;
                return $respon;

    }
    //------------------------------------------------------------------//
    public function selectTipoLangID()
    {
        $query = "SELECT * FROM type";
        $selectall = $this->db->query($query);
        $ListLangID = $selectall->fetch_all(MYSQLI_ASSOC);
        return $ListLangID;
    }

}
