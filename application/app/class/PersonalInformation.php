<?php

require_once "config/conexion.php";
//* PersonID	UserID	FullName	Birthdate	CityID	CountryID	Phone	DocumentFrontImageFileName	DocumentBackImageFileName	SelfieWithIDImageFileName	BillingAddress	Verified	ProfilePhotoImageFileName
class PersonalInformation extends Conexion
{
    private $PersonID;
    private $UserID;
    private $FullName;
    private $Birthday;
    private $CityID;
    private $CountryID;
    private $Phone;
    private $DocumentFrontImageFileName;
    private $DocumentBackImageFileName;
    private $SelfieWithIDImageFileName;
    private $BillingAddress;
    private $Verified;
    private $ProfilePhotoImageFileName;

    public function __construct()
    {
        parent::__construct(); //Llamada al constructor de la clase padre

        $this->UserID = "";
        $this->FullName = "";
        $this->Birthday = "";
        $this->CityID = "";
        $this->CountryID = "";
        $this->Phone = "";
        $this->DocumentFrontImageFileName = "";
        $this->DocumentBackImageFileName = "";
        $this->SelfieWithIDImageFileName = "";
        $this->BillingAddress = "";
        $this->Verified = "";
        $this->ProfilePhotoImageFileName = "";
    }



    public function getUserID()
    {
        return $this->UserID;
    }

    public function setUserID($id)
    {
        $this->UserID = $id;
    }

    public function getFullName()
    {
        return $this->FullName;
    }

    public function setFullName($FullName)
    {
        $this->FullName = $FullName;
    }

    public function getBirthday()
    {
        return $this->Birthday;
    }

    public function setBirthday($Birthday)
    {
        $this->Birthday = $Birthday;
    }

    public function getCityID()
    {
        return $this->CityID;
    }

    public function setCityID($CityID)
    {
      $this->CityID = $CityID;
    }
    public function getPhone()
    {
        return $this->Phone;
    }

    public function setPhone($Phone)
    {
        $this->Phone = $Phone;
    }

    public function getCountryID()
    {
        return $this->CountryID;
    }

    public function setCountryID($CountryID)
    {
        $this->CountryID = $CountryID;
    }
    public function getDocumentFrontImageFileName()
    {
        return $this->DocumentFrontImageFileName;
    }

    public function setDocumentFrontImageFileName($DocumentFrontImageFileName)
    {
        $this->DocumentFrontImageFileName = $DocumentFrontImageFileName;
    }
    


    public function getDocumentBackImageFileName()
    {
        return $this->DocumentBackImageFileName;
    }

    public function setDocumentBackImageFileName($DocumentBackImageFileName)
    {
        $this->DocumentBackImageFileName = $DocumentBackImageFileName;
    }
    public function getSelfieWithIDImageFileName()
    {
        return $this->SelfieWithIDImageFileName;
    }

    public function setSelfieWithIDImageFileName($SelfieWithIDImageFileName)
    {
        $this->SelfieWithIDImageFileName = $SelfieWithIDImageFileName;

    }

    public function getBillingAddress()
    {
        return $this->BillingAddress;
    }

    public function setBillingAddress($BillingAddress)
    {
        $this->BillingAddress = $BillingAddress;

    }
    public function getVerified()
    {
        return $this->Verified;
    }

    public function setVerified($Verified)
    {
        $this->Verified = $Verified;

    }
    public function getProfilePhotoImageFileName()
    {
        return $this->ProfilePhotoImageFileName;
    }
    public function setProfilePhotoImageFileName($ProfilePhotoImageFileName)
    {
        $this->ProfilePhotoImageFileName = $ProfilePhotoImageFileName;
    }
    //-------------------------------------------------------------------------------- Funciones --------------------------------------------------------------------------------//
public function saveUsr()
    {
        $query = "INSERT INTO user (UserID,CityIDword,FullName,Phone,created_at)
                  values(NULL,'" . $this->CityID . "','" . $this->FullName . "'," . $this->Phone . ",NOW());";
        $save = $this->db->query($query);
        $_SESSION['mensaje'] = $this->db->error;
        if ($save == true) {
            $respon = array();
            $respon['error']='false';
            $respon['message']='¡Usuario Creado con Exito!';
            $respon['request']="";
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
        $query = "UPDATE user SET FullName='" . $this->FullName . "',Phone= '$empleo' WHERE UserID = $this->UserID;";
        $save = $this->db->query($query);
        $_SESSION['mensaje'] = $this->db->error;
        if ($save == true) {
            return true;
        } else {

            return false;
        }
    }
public function save()
    {
        $query = "INSERT INTO PersonalInformation (UserID, FullName, Birthdate, CityID,CountryID, Phone, DocumentFrontImageFileName, DocumentBackImageFileName, SelfieWithIDImageFileName, BillingAddress, Verified)
              values(" . $this->UserID . ",'" . $this->FullName . "','" . $this->Birthday . "'," . $this->CityID . "," . $this->CountryID . ",'" . $this->Phone . "','" . $this->DocumentFrontImageFileName . "','" . $this->DocumentBackImageFileName . "','" . $this->SelfieWithIDImageFileName . "','" . $this->BillingAddress . "','" . $this->Verified . "');";
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
        $query = "DELETE FROM user WHERE UserID='" . $this->UserID . "'";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }


    public function selectOne($codigo)
    {
        $query = "SELECT * FROM user WHERE UserID=" . $codigo . "";
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
        $query = "UPDATE user SET DocumentFrontImageFileName='$this->DocumentFrontImageFileName' WHERE UserID=" . $this->UserID . "";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }
    //-----------------------------------------------------------------------------------//
    


}
