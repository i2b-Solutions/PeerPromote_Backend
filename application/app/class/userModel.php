<?php
session_start();
require_once "config/conexion.php";
//* nuevos campos 
// UserID	UserUsername	PasswordHashwordHash	Email	Blocked	IsCompany
class User extends Conexion
{
    private $UserID;
    private $Username;
    private $user;
    private $PasswordHash;
    private $email;
    private $isCompany;
    private $Blocked;

    public function __construct()
    {
        parent::__construct(); //Llamada al constructor de la clase padre

        $this->UserID = "";
        $this->Username = "";
        $this->user = "";
        $this->PasswordHash = "";
        $this->email = "";
        $this->isCompany = "";
        $this->Blocked = "";
    }



    public function getUserID()
    {
        return $this->UserID;
    }

    public function setUserID($id)
    {
        $this->UserID = $id;
    }

    public function getUsername()
    {
        return $this->Username;
    }

    public function setUsername($Username)
    {
        $this->Username = $Username;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getPasswordHash()
    {
        return $this->PasswordHash;
    }

    public function setPasswordHash($PasswordHash)
    {
        $secret_key="i2b_solutions_dev";
        #$PasswordHashword = hash('sha256', $PasswordHash,False);
        $PasswordHashword = hash_hmac('sha256', $PasswordHash,$secret_key);
        $this->PasswordHash = $PasswordHashword;
    }
    public function getIsCompany()
    {
        return $this->isCompany;
    }

    public function setIsCompany($isCompany)
    {
        $this->isCompany = $isCompany;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getBlocked()
    {
        return $this->Blocked;
    }

    public function setBlocked($Blocked)
    {
        $this->Blocked = $Blocked;
    }
    //-------------------------------------------------------------------------------- Funciones --------------------------------------------------------------------------------//
public function saveUsr()
    {
        $query = "INSERT INTO user (UserID,PasswordHashword,Username,isCompany,created_at)
                  values(NULL,'" . $this->PasswordHash . "','" . $this->Username . "'," . $this->isCompany . ",NOW());";
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
        $query = "UPDATE user SET Username='" . $this->Username . "',isCompany= '$empleo' WHERE UserID = $this->UserID;";
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
        $query1 = "SELECT u.*, tu.tipo FROM user u INNER JOIN user_type tu ON tu.id=u.sCompany WHERE u.user='" . $this->user . "' AND u.PasswordHashword='" . $this->PasswordHash . "'";
        $selectall1 = $this->db->query($query1);
        $ListUser = $selectall1->fetch_all(MYSQLI_ASSOC);

        if ($selectall1->num_rows != 0 ) {
            foreach ($ListUser as $key) {
                    session_start();
                    $_SESSION['logged-in'] = true;
                    $_SESSION['User'] = $key['user'];
                    $_SESSION['UserID'] = $key['id'];
                    $_SESSION['tipo'] = $key['isCompany'];
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
        $query = "INSERT INTO user (UserID,user,PasswordHash,IsCompany,email,created_at)
              values(NULL,'" . $this->Username . "','" . $this->PasswordHash . "'," . $this->isCompany . ",'" . $this->email . "',NOW());";
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
        $query = "UPDATE user SET Blocked='$this->Blocked' WHERE UserID=" . $this->UserID . "";
        $delete = $this->db->query($query);
        if ($delete == true) {
            return true;
        } else {
            return false;
        }
    }
    //-----------------------------------------------------------------------------------//

    public function login_auth()
    {
        $query1 = "SELECT u.Username, u.UserID, u.IsCompany FROM Users u WHERE u.Username='" . $this->Username . "' AND u.PasswordHash='" . $this->PasswordHash . "'";
        $selectall1 = $this->db->query($query1);
        $ListUser = $selectall1->fetch_all(MYSQLI_ASSOC);
        $comany=False;
        if ($selectall1->num_rows != 0 ) {
            foreach ($ListUser as $key) {
                    $_SESSION['logged-in'] = True;
                    $_SESSION['User'] = $key['Username'];
                    $_SESSION['UserID'] = $key['UserID'];
                    $_SESSION['tiempo'] = time();
                    $_SESSION['acceso'] = '';
                    $company =  ($key['IsCompany']==1) ? True : False;
            } 
            $respon = array();
            $respon['errorAuth']=false;
            $respon['logged-in']=True;
            $respon['IsCompany']=$company;
            $respon['message']='Bienvenido de nuevo.';
            $respon['request']=$ListUser;
            return $respon;
        } else {
            $_SESSION['logged-in'] = false;
            $_SESSION['tiempo'] = 0;
            $respon = array();
            $respon['errorAuth']=True;
            $respon['logged-in']=False;
            $respon['message']='Error al iniciar sesion consulte con su proveedor.'.$this->db->error;;
            $respon['request']=$ListUser;
            return $respon;
        }
    }
//----------------------REGISTER STEP 1 -------------------------------------------/
/* Username = users.Username
Password = users.PasswordHash
Edad= PersonalInformation.birthdate
Pais = PersonalInformation.countryID
Departamento = PersonalInformation.cityID
Idioma = languages.personID--
                Languages.IDlang */
    public function register_step_one($birthdate,$city_id,$country_id)
                {
                    $query = "SELECT COUNT(UserID) as cantidad FROM user WHERE Username='$this->Username'";
                    $selectall = $this->db->query($query);
                    $ListUser = $selectall->fetch_all(MYSQLI_ASSOC);
                    foreach ($ListUser as $key) {
                        $numRow=$key['cantidad'];
                    }
                    if ($numRow>=1) {
                        $respon = array();
                        $respon['error']='true';
                        $respon['message']='¡Usuario ya registrado!';
                        $respon['request']=$this->db->error;
                        return $respon;
                    }else{
                        $query = "INSERT INTO Users (UserID,PasswordHashword,Username,created_at)
                                values(NULL,'" . $this->PasswordHash . "','" . $this->Username . "',NOW());";
                        $save = $this->db->query($query);
                        $_SESSION['mensaje'] = $this->db->error;
                        $userID = $this->db->insert_id;
                        if ($save == true) {
                            //GUARDAR LA INFORMACION PERSONAL DEL USUARIO REGISTRADO
                            
                        $queryPI = "INSERT INTO PersonalInformation(UserID, Birthdate, CityID,CountryID)
                        values($userID,'" . $birthdate. "'," . $city_id . "," . $country_id . ");";
                        $save_step1 = $this->db->query($queryPI);
                        $_SESSION['mensaje'] = $this->db->error;
                        $personalInfo=$this->db->insert_id;
                            if ($save_step1 == true) {
                            $respon = array();
                            $respon['error']='false';
                            $respon['message']='¡Excelente! Pasemos al siguiente';
                            $respon['request']="";
                            $respon['UserID']=$userID; 
                            $respon['PersonID']=$personalInfo; 
                            return $respon;
                            }
                        } else {
                            
                            $respon = array();
                            $respon['error']='true';
                            $respon['message']='¡Usuario Error al guardar, verifica la información proporcionada e intenta de nuevo!';
                            $respon['request']=$this->db->error;
                            return $respon;
                            #return false;
                        }
                    }
                }
    //----------------------------------------------------------------------------------------------------------
                public function register_step_two($PersonID,$phone,$ProfilePhotoImageFileName)
                            {
                                $query = "UPDATE Users SET Email='$this->email' WHERE UserID=" . $this->UserID . ";";
                                $save = $this->db->query($query);
                                $_SESSION['mensaje'] = $this->db->error;
                                if ($save == true) {
                                    //GUARDAR LA INFORMACION PERSONAL DEL USUARIO REGISTRADO
                                    
                                $queryPI = "UPDATE PersonalInformation SET Phone = '$phone',ProfilePhotoImageFileName='$ProfilePhotoImageFileName' WHERE PersonID=$PersonID ";
                                $save_step1 = $this->db->query($queryPI);
                                $_SESSION['mensaje'] = $this->db->error;
                                    if ($save_step1 == true) {
                                    $respon = array();
                                    $respon['error']='false';
                                    $respon['message']='¡Excelente! Pasemos al siguiente';
                                    $respon['request']="";
                                    $respon['UserID']=$this->UserID; 
                                    $respon['PersonID']=$PersonID; 
                                    return $respon;
                                    }
                                } else {
                                    
                                    $respon = array();
                                    $respon['error']='true';
                                    $respon['message']='¡Usuario Error al guardar, verifica la información proporcionada e intenta de nuevo!';
                                    $respon['request']=$this->db->error;
                                    return $respon;
                                    return false;
                                }
                            }
    // ----------------------------------------------------------------------------------------------------
    public function validate_step_one()
                {
                    $query = "SELECT COUNT(UserID) as cantidad FROM Users WHERE Username='$this->Username'";
                    $selectall = $this->db->query($query);
                    $ListUser = $selectall->fetch_all(MYSQLI_ASSOC);
                    foreach ($ListUser as $key) {
                        $numRow=$key['cantidad'];
                    }
                    if ($numRow>=1) {
                        $respon = array();
                        $respon['error']='false';
                        $respon['register']=true;
                        $respon['message']='¡Usuario ya registrado!';
                        //$respon['request']=$this->db->error;
                        return $respon;
                    }else{
                        $respon = array();
                        $respon['error']='false';
                        $respon['register']=false;
                        $respon['message']='¡Usuario No registrado!';
                        //$respon['request']=$this->db->error;
                        return $respon;
                    }
                }
    public function validate_step_two()
                {
                    $query = "SELECT COUNT(UserID) as cantidad FROM Users WHERE Email='$this->email'";
                    $selectall = $this->db->query($query);
                    $ListUser = $selectall->fetch_all(MYSQLI_ASSOC);
                    foreach ($ListUser as $key) {
                        $numRow=$key['cantidad'];
                    }
                    if ($numRow>=1) {
                        $respon = array();
                        $respon['error']='false';
                        $respon['register']=true;
                        $respon['message']='¡Email ya registrado!';
                        //$respon['request']=$this->db->error;
                        return $respon;
                    }else{
                        $respon = array();
                        $respon['error']='false';
                        $respon['register']=false;
                        $respon['message']='¡Email No registrado!';
                        //$respon['request']=$this->db->error;
                        return $respon;
                    }
                }
    /* ---------------------------------------------------------------------------------------------*/
    
    public function register_step_three($birthdate,$city_id,$country_id,$phone,$langs)
                {       /* OBTENER VALOR DE COUNTRIE */
                            $query = "SELECT CountryID FROM Countries WHERE CountryISO='$country_id'";
                            $selectall = $this->db->query($query);
                            $ListUser = $selectall->fetch_all(MYSQLI_ASSOC);
                            foreach ($ListUser as $key) {
                                $CountryID=$key['CountryID'];
                            }
                        
                            #print_r($langs);
                        $query = "INSERT INTO Users (UserID,PasswordHash,Username,Email,created_at,IsCompany)
                                values(NULL,'" . $this->PasswordHash . "','" . $this->Username . "','" . $this->email . "',NOW(),".$this->isCompany.");";
                        $save = $this->db->query($query);
                        $_SESSION['mensaje'] = $this->db->error;
                        $userID = $this->db->insert_id;
                        if ($save == true) {
                            //GUARDAR LA INFORMACION PERSONAL DEL USUARIO REGISTRADO
                            
                        $queryPI = "INSERT INTO PersonalInformation(UserID, Birthdate, CityID,CountryID,Phone)
                        values($userID,'" .$birthdate. "'," . $city_id . "," . $CountryID . ",'" . $phone. "');";
                        $save_step1 = $this->db->query($queryPI);
                        $_SESSION['mensaje'] = $this->db->error;
                        $personalInfo=$this->db->insert_id;
                        //------------------------------------------------------------------------------------------------------//
                        /* OBTENER LENGUAJES */
                        for ($i=0; $i <  count($langs) ; $i++) { 

                            $queryL = "SELECT IDLang FROM LANGS WHERE Language='".$langs[$i]['lang']."'";
                            //echo $queryL;
                            $selectallLan = $this->db->query($queryL);
                            $ListUser = $selectallLan->fetch_all(MYSQLI_ASSOC);
                            foreach ($ListUser as $key) {
                                $lenguaje=$key['IDLang'];
                                $queryLang = "INSERT INTO Languages(PersonID,IDLang)
                                values($personalInfo," . $lenguaje. ");";
                                $save_lang = $this->db->query($queryLang);
                            }
                            
                        }
                        //------------------------------------------------------------------------------------------------------//
                            if ($save_step1 == true) {
                            
                            $_SESSION['UserID']=$userID; 
                            $_SESSION['PersonID']=$personalInfo; 
                            $_SESSION['Username']=$this->Username; 
                            $respon = array();
                            $respon['register']=True;
                            $respon['message']='¡Usuario Registrado!';
                            $respon['request']="";
                            $respon['UserID']=$userID; 
                            $respon['PersonID']=$personalInfo; 
                            $respon['Username']=$this->Username; 
                            return $respon;
                            }else {
                            
                                $respon = array();
                                $respon['register']=False;
                                $respon['message']='¡Usuario Error al guardar Informacion Personal, verifica la información proporcionada e intenta de nuevo!';
                                $respon['request']=$this->db->error;
                                #$respon['request2']=$langs[0]['lang'];
                                return $respon;
                                #return false;
                            }
                        } else {
                            
                            $respon = array();
                            $respon['register']=False;
                            $respon['message']='¡Usuario Error al guardar, verifica la información proporcionada e intenta de nuevo!';
                            $respon['request']=$this->db->error;
                            #$respon['request2']=$langs[0]['lang'];
                            return $respon;
                            #return false;
                        }
                }

                /*------------------------------------------------------------------------------------------------------------------------------------------------*/
                //ProfilePhotoImageFileName
                
                public function upload_image($img)
                {
                    
                    $query = "UPDATE PersonalInformation SET ProfilePhotoImageFileName='$img' WHERE UserID=" . $this->UserID . ";";
                    $save = $this->db->query($query);
                    if ($save==true) {
                        $respon = array();
                        $respon['error']=false;
                        $respon['message']='Fotografía actualizada';
                        $respon['img']=$img;
                        //$respon['request']=$this->db->error;
                        return $respon;
                    }else{
                        $respon = array();
                        $respon['error']=true;
                        $respon['register']=false;
                        $respon['message']='Ocurrio un error';
                        //$respon['request']=$this->db->error;
                        return $respon;
                    }
                }
}
