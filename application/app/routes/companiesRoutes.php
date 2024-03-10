<?php
declare(strict_types=1);

use App\Application\Actions\company\ListcompanysAction;
use App\Application\Actions\company\ViewcompanyAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    //------------------------------------------------------------------------------------//
    $app->post('/company/register_step_one',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        
        $data = $request->getParsedBody();
     
        if ($data['birthdate'] !="") {
                                          
            $birthdate=$data['birthdate'];
            if ($data['company'] !="") {
                                          
                $company=$data['company'];
                $type='2';
                if ($data['CityID'] !="") {
                                          
                    $CityID=$data['CityID'];
                    $CountryID=$data['CountryID'];
                    if ($data['pass'] !="") {   
                        $pass=$data['pass'];
                    }else{
                        http_response_code(401);
                        $respon = array();
                        $respon['status']=401;
                        $respon['error']='true';
                        $respon['message']='Failed to receive  pass request';
                        $response->getBody()->write(json_encode($respon));
                        return $response;
                    }
                 
                }else{
                    http_response_code(401);
                    $respon = array();
                    $respon['status']=401;
                    $respon['error']='true';
                    $respon['message']='Failed to receive COMPANY request';
                    $response->getBody()->write(json_encode($respon));
                    return $response;
                }
            }else{
                http_response_code(401);
                $respon = array();
                $respon['status']=401;
                $respon['error']='true';
                $respon['message']='Failed to receive company request';
                $response->getBody()->write(json_encode($respon));
                return $response;
            }
        }else{
            http_response_code(401);
            $respon = array();
            $respon['status']=401;
            $respon['error']='true';
            $respon['message']='Failed to receive birthdate request';
            $response->getBody()->write(json_encode($respon));
            return $response;
        }
        #fin de obtencion de variables
        try {
                                                           
            $company_request = new User();
            $company_request->setUsername($company);
            $company_request->setPasswordHash($pass);
            $datacompany=$company_request->register_step_one($birthdate,$CityID,$CountryID);
            $respon=array();
            $saveLanguage='';
            $arrayLanguage=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($datacompany)) {
                    if (count($data['laguangesID']) >0){
                        $language= new Languages();
                        for ($i=0; $i <= count($data['laguangesID']) ; $i++) { 
                             $language->setLangID($data['laguangesID'][$i]);
                             $language->setPersonID($datacompany['PersonID']);
                             $saveLanguage= $language->save();
                             array_push($arrayLanguage,$saveLanguage['LanguageID']);
                        }
                      // if($i==count($arrayLanguage)){
                        
                       //}
                    }
                    if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$datacompany;
                    //echo json_encode($respon);
                    $response->getBody()->write(json_encode($respon));
                    return $response;}
             //   echo $response->withJson($respon,201);  //imprime un json con status 200: OK CREATED
                }
        }catch (Exception $e){
    
        http_response_code(401);
    
       $respon= array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        );
        echo json_encode($respon);
     //echo $response->withJson($respon,401);
    
         }
    });
    //---------------------------------STEP TWO---------------------------------------------------//
    $app->post('/company/register_step_two',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        
        $data = $request->getParsedBody();
     
        if ($data['Email'] !="") {
                                          
            $Email=$data['Email'];
            if ($data['Phone'] !="") {
                                          
                $Phone=$data['Phone'];
                $type='2';
                if ($data['PhotoProfile'] !="") {
                                          
                    $PhotoProfile=$data['PhotoProfile'];
                    $companyID=$data['companyID'];
                    $PersonID=$data['PersonID'];
                 
                }else{
                    http_response_code(401);
                    $respon = array();
                    $respon['status']=401;
                    $respon['error']='true';
                    $respon['message']='Failed to receive Photo Profile request';
                    $response->getBody()->write(json_encode($respon));
                    return $response;
                }
            }else{
                http_response_code(401);
                $respon = array();
                $respon['status']=401;
                $respon['error']='true';
                $respon['message']='Failed to receive phone request';
                $response->getBody()->write(json_encode($respon));
                return $response;
            }
        }else{
            http_response_code(401);
            $respon = array();
            $respon['status']=401;
            $respon['error']='true';
            $respon['message']='Failed to receive email request';
            $response->getBody()->write(json_encode($respon));
            return $response;
        }
        #fin de obtencion de variables
        try {
                                                           
            $company_request = new User();
            $company_request->setEmail($Email);
            $company_request->setUserID($companyID);
            $datacompany=$company_request->register_step_two($PersonID,$Phone,$PhotoProfile);
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($datacompany)) {
                    if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$datacompany;
                    //echo json_encode($respon);
                    $response->getBody()->write(json_encode($respon));
                    return $response;}
             //   echo $response->withJson($respon,201);  //imprime un json con status 200: OK CREATED
                }
        }catch (Exception $e){
    
        http_response_code(401);
    
       $respon= array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        );
        echo json_encode($respon);
     //echo $response->withJson($respon,401);
    
         }
    });
    //---------------------------------STEP ONE VALIDATE---------------------------------------------------//
    $app->post('/company/step_one',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        $data = $request->getParsedBody();
        
        if ($data['company'] !="") {
                                          
            $company=$data['company'];
            $type='2';
        }else{
            http_response_code(401);
            $respon = array();
            $respon['status']=401;
            $respon['error']='true';
            $respon['message']='Failed to receive company request';
            $response->getBody()->write(json_encode($respon));
            return $response;
        }
        #fin de obtencion de variables
        try {
                                                           
            $company_request = new User();
            $company_request->setUsername($company);
            $datacompany=$company_request->validate_step_one();
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($datacompany)) {
                    if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']=true;
                    $respon['data']=$datacompany;
                    //echo json_encode($respon);
                    $response->getBody()->write(json_encode($respon));
                    return $response;}
             //   echo $response->withJson($respon,201);  //imprime un json con status 200: OK CREATED
                }
        }catch (Exception $e){
    
        http_response_code(401);
    
       $respon= array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        );
        echo json_encode($respon);
     //echo $response->withJson($respon,401);
    
         }
    });
    //---------------------------------STEP TWO VALIDATE---------------------------------------------------//
    $app->post('/company/step_two',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        $data = $request->getParsedBody();
        
        if ($data['email'] !="") {
                                          
            $company=$data['email'];
            $type='2';
        }else{
            http_response_code(401);
            $respon = array();
            $respon['status']=401;
            $respon['error']=true;
            $respon['message']='Failed to receive company request';
            $response->getBody()->write(json_encode($respon));
            return $response;
        }
        $data = $request->getParsedBody();
        #fin de obtencion de variables
        try {
                                                           
            $company_request = new User();
            $company_request->setEmail($company);
            $datacompany=$company_request->validate_step_two();
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($datacompany)) {
                    if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$datacompany;
                    //echo json_encode($respon);
                    $response->getBody()->write(json_encode($respon));
                    return $response;}
             //   echo $response->withJson($respon,201);  //imprime un json con status 200: OK CREATED
                }
        }catch (Exception $e){
    
        http_response_code(401);
    
       $respon= array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        );
        echo json_encode($respon);
     //echo $response->withJson($respon,401);
    
         }
    });
      
    //------------------------------------------------------------------------------------//
    $app->post('/company/register',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        $uploadedFile= $request->getUploadedFiles();
        $data = $request->getParsedBody();
      /*   $response->getBody()->write(json_encode($data));
        return $response; */
        $email='';
        $user='';
        $pass='';
        $Birthdate =  (isset($data['Birthday']) ) ? $data['Birthday']['year'].'-'.$data['Birthday']['month'].'-'.$data['Birthday']['day'] : null;
        $CityID =  ($data['CityID'] !="") ? $data['CityID'] :  null;
        $CountryID =  ($data['CountryID'] !="") ? $data['CountryID'] :  null;
        $Phone =  ($data['Phone'] !="") ? $data['Phone'] : '';
        $language = $data['languages'];
       /*  $response->getBody()->write(json_encode($data['languages']));
        return $response;
        */
        if (empty($uploadedFile['imagen']) || !isset($uploadedFile['imagen'])) {
            $uploadedFile=False;
        }else{
            $uploadedFile=$uploadedFile['imagen'];
        }
        if ($data['email'] !="") {
                                          
            $email=$data['email'];
            if ($data['user'] !="") {
                                          
                $user=$data['user'];
                $type='2';
                                          
                    //$isCompany=$data['isCompany'];
                    if ($data['pass'] !="") {   
                        $pass=$data['pass'];
                    }else{
                        http_response_code(401);
                        $respon = array();
                        $respon['status']=401;
                        $respon['error']='true';
                        $respon['message']='Failed to receive  pass request';
                        $response->getBody()->write(json_encode($respon));
                        return $response;
                    }
            }else{
                http_response_code(401);
                $respon = array();
                $respon['status']=401;
                $respon['error']='true';
                $respon['message']='Failed to receive user request';
                $response->getBody()->write(json_encode($respon));
                return $response;
            }
        }else{
            http_response_code(401);
            $respon = array();
            $respon['status']=401;
            $respon['error']='true';
            $respon['message']='Failed to receive email request';
            $response->getBody()->write(json_encode($respon));
            return $response;
        }
        #fin de obtencion de variables
        try {
            /*-----------------------*/                                                           
            $user_request = new User();
            $user_request->setEmail($email);
            $user_request->setUsername($user);
            $user_request->setPasswordHash($pass);
            $data=$user_request->register_step_three($Birthdate,$CityID,$CountryID,$Phone,$language,1);
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($data)) {
                        if ($uploadedFile!=false){
                            //subir imagen//
                            if ($uploadedFile->getError() === UPLOAD_ERR_OK && strpos($uploadedFile->getClientMediaType(), 'image') !== false) {
                                $carpeta=__DIR__ . "/../public/picturesProfile";
                                $file_path='';
                                if (!file_exists($carpeta)) {
                                    mkdir($carpeta, 0777, true);// otorgando permiso para crear carpetas en el directorio
                                    $file_path=$carpeta;
                                }else{
                                    $file_path=$carpeta;
                                }
                                $rutaDestino = $file_path.'/'.$data['UserID'].'_'.$uploadedFile->getClientFilename();
                                try {
                                    $uploadedFile->moveTo($rutaDestino);
                                    //guardar ruta en la base de datos
                                    $user_request->setUserID($data['UserID']);
                                    $saveIMG = $user_request->upload_image($uploadedFile);
                                    // Aquí puedes guardar la ruta de la imagen en la base de datos
                                    //$respuesta = "Imagen subida correctamente.";
                                    // Aquí puedes insertar la ruta de la imagen en tu base de datos MySQL
                                    /* $response->getBody()->write($respuesta); */
                                    //return $response->withStatus(200);
                                    $imgPath='';
                                    if ($saveIMG['error']==false){
                                        $imgPath=$saveIMG['img'];
                                    }
                                    http_response_code(200);
                                    $respon['success']=true;
                                    $respon['data']=$data;
                                    $respon['img']=$imgPath;
                                    $response->getBody()->write(json_encode($respon));
                                    return $response;
                                } catch (Exception $e) {
                                    $response->getBody()->write("Error al subir la imagen: " . $e->getMessage());
                                    return $response->withStatus(500);
                                }


                            } else {
                                $response->getBody()->write("El archivo enviado no es una imagen válida.");
                                return $response->withStatus(400);
                            }  
                    /*-----------------------------------------------------------*/
                    }else{

                        http_response_code(200);
                        $respon['success']=true;
                        $respon['data']=$data;
                        $respon['img']='';
                        $response->getBody()->write(json_encode($respon));
                    }
                }
             //   echo $response->withJson($respon,201);  //imprime un json con status 200: OK CREATED
        }catch (Exception $e){
    
        http_response_code(401);
    
       $respon= array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        );
        echo json_encode($respon);
     //echo $response->withJson($respon,401);
    
         }
    });
    //-----------------------------------------------------------------------------------------------------//
};