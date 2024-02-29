<?php

require_once "config/conexion.php";
//* nuevos campos 
// CountryISOID	CountryISOCountryName	PasswordHash	Email	Blocked	IsCompany
class Country extends Conexion
{
    private $CountryID;
    private $CountryName;
    private $CountryISO;

    public function __construct()
    {
        parent::__construct(); //Llamada al constructor de la clase padre

        $this->CountryID = "";
        $this->CountryName = "";
        $this->CountryISO = "";
    }



    public function getCountryID()
    {
        return $this->CountryID;
    }

    public function setCountryID($id)
    {
        $this->CountryID = $id;
    }

    public function getCountryName()
    {
        return $this->CountryName;
    }

    public function setCountryName($CountryName)
    {
        $this->CountryName = $CountryName;
    }

    public function getCountryISO()
    {
        return $this->CountryISO;
    }

    public function setCountryISO($CountryISO)
    {
        $this->CountryISO = $CountryISO;
    }
    //-------------------------------------------------------------------------------- Funciones --------------------------------------------------------------------------------//
public function save()
    {   
        $query = "SELECT CountryID FROM Countries WHERE CountryID=" . $this->CountryID . "";
        $selectall = $this->db->query($query);
        $ListCountryISO = $selectall->fetch_all(MYSQLI_ASSOC);
        if(count($ListCountryISO)>0){
            $query = "UPDATE Countries SET CountryISO = '" . $this->CountryISO . "',CountryName='" . $this->CountryName . "'WHERE CountryID = $this->CountryID;";
            $save = $this->db->query($query);
            $_SESSION['mensaje'] = $this->db->error;
            if ($save == true) {
                $respon = array();
                $respon['error']='false';
                $respon['request']="";
                $respon['CountryID']=$this->db->insert_id;
                return $respon;
            } else {
                $respon = array();
                $respon['error']='true';
                $respon['message']='¡Error al guardar Pais, verifica la información proporcionada e intenta de nuevo!';
                $respon['request']=$this->db->error;
                return $respon;
            }
        }else{
        //----------------------------------------------------------------------------------------------------
            $query = "INSERT INTO Countries (CountryID,CountryName,CountryISO)
                    values($this->CountryID,'" . $this->CountryName . "','" . $this->CountryISO . "');";
            $save = $this->db->query($query);
            $_SESSION['mensaje'] = $this->db->error;
            if ($save == true) {
                $respon = array();
                $respon['error']='false';
                $respon['request']="";
                $respon['CountryID']=$this->db->insert_id;
                return $respon;
            } else {
                
                $respon = array();
                $respon['error']='true';
                $respon['message']='¡Error al guardar Pais, verifica la información proporcionada e intenta de nuevo!';
                $respon['request']=$this->db->error;
                return $respon;
            }
        }
    }
    public function updateUsr()
    {
        $query = "UPDATE Countries SET CountryISO = '" . $this->CountryISO . "',CountryName='" . $this->CountryName . "'WHERE CountryID = $this->CountryID;";
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
        $query = "DELETE FROM Countries WHERE CountryID='" . $this->CountryID . "'";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }


    public function selectOne($codigo)
    {
        $query = "SELECT * FROM Countries WHERE CountryID=" . $codigo . "";
        $selectall = $this->db->query($query);
        $ListCountryISO = $selectall->fetch_all(MYSQLI_ASSOC);
        return $ListCountryISO;
    }

    public function selectAll()
    {
        $query = "SELECT * FROM Countries";
        $selectall = $this->db->query($query);
        $ListCountryISO = $selectall->fetch_all(MYSQLI_ASSOC);
        $respon = array();
                $respon['error']='false';
                $respon['message']='Usuarios';
                $respon['request']=$ListCountryISO;
                return $respon;

    }
    //------------------------------------------------------------------//
    public function selectTipoCountryISO()
    {
        $query = "SELECT * FROM type";
        $selectall = $this->db->query($query);
        $ListCountryISO = $selectall->fetch_all(MYSQLI_ASSOC);
        return $ListCountryISO;
    }
    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------//
    public function saveCity($CityID,$City)
    {   
        $query = "SELECT CityID FROM Cities WHERE CityID=" . $CityID . "";
        $selectall = $this->db->query($query);
        $ListCountryISO = $selectall->fetch_all(MYSQLI_ASSOC);
        if(count($ListCountryISO)>0){
            $query = "UPDATE Cities SET CountryID = '" . $this->CountryID. "',City='" . $City . "'WHERE CityID = $CityID;";
            $save = $this->db->query($query);
            $_SESSION['mensaje'] = $this->db->error;
            if ($save == true) {
                $respon = array();
                $respon['error']='false';
                $respon['request']="";
                $respon['CountryID']=$this->db->insert_id;
                return $respon;
            } else {
                $respon = array();
                $respon['error']='true';
                $respon['message']='¡Error al guardar Ciudad, verifica la información proporcionada e intenta de nuevo!';
                $respon['request']=$this->db->error;
                return $respon;
            }
        }else{
        //----------------------------------------------------------------------------------------------------
            $query = "INSERT INTO Cities (CityID,CountryID,City)
                    values($CityID,$this->CountryID,'" . $City . "');";
            $save = $this->db->query($query);
            $_SESSION['mensaje'] = $this->db->error;
            if ($save == true) {
                $respon = array();
                $respon['error']='false';
                $respon['request']="";
                $respon['CountryID']=$this->db->insert_id;
                return $respon;
            } else {
                
                $respon = array();
                $respon['error']='true';
                $respon['message']='¡Error al guardar Ciudad, verifica la información proporcionada e intenta de nuevo!';
                $respon['request']=$this->db->error;
                return $respon;
            }
        }
    }

}
