<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    //------------------------------------------------------------------------------------//
    $app->post('/user/register_step_one',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        
        $data = $request->getParsedBody();
     
        if ($data['birthdate'] !="") {
                                          
            $birthdate=$data['birthdate'];
            if ($data['user'] !="") {
                                          
                $user=$data['user'];
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
                $respon['message']='Failed to receive user request';
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
                                                           
            $user_request = new User();
            $user_request->setUsername($user);
            $user_request->setPasswordHash($pass);
            $dataUser=$user_request->register_step_one($birthdate,$CityID,$CountryID);
            $respon=array();
            $saveLanguage='';
            $arrayLanguage=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($dataUser)) {
                    if (count($data['laguangesID']) >0){
                        $language= new Languages();
                        for ($i=0; $i <= count($data['laguangesID']) ; $i++) { 
                             $language->setLangID($data['laguangesID'][$i]);
                             $language->setPersonID($dataUser['PersonID']);
                             $saveLanguage= $language->save();
                             array_push($arrayLanguage,$saveLanguage['LanguageID']);
                        }
                      // if($i==count($arrayLanguage)){
                        
                       //}
                    }
                    if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$dataUser;
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
    $app->post('/user/register_step_two',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        
        $data = $request->getParsedBody();
     
        if ($data['Email'] !="") {
                                          
            $Email=$data['Email'];
            if ($data['Phone'] !="") {
                                          
                $Phone=$data['Phone'];
                $type='2';
                if ($data['PhotoProfile'] !="") {
                                          
                    $PhotoProfile=$data['PhotoProfile'];
                    $UserID=$data['UserID'];
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
                                                           
            $user_request = new User();
            $user_request->setEmail($Email);
            $user_request->setUserID($UserID);
            $dataUser=$user_request->register_step_two($PersonID,$Phone,$PhotoProfile);
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($dataUser)) {
                    if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$dataUser;
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
    $app->post('/user/step_one',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        $data = $request->getParsedBody();
        
        if ($data['user'] !="") {
                                          
            $user=$data['user'];
            $type='2';
        }else{
            http_response_code(401);
            $respon = array();
            $respon['status']=401;
            $respon['error']='true';
            $respon['message']='Failed to receive user request';
            $response->getBody()->write(json_encode($respon));
            return $response;
        }
        #fin de obtencion de variables
        try {
                                                           
            $user_request = new User();
            $user_request->setUsername($user);
            $dataUser=$user_request->validate_step_one();
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($dataUser)) {
                    if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']=true;
                    $respon['data']=$dataUser;
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
    $app->post('/user/step_two',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        $data = $request->getParsedBody();
        
        if ($data['email'] !="") {
                                          
            $user=$data['email'];
            $type='2';
        }else{
            http_response_code(401);
            $respon = array();
            $respon['status']=401;
            $respon['error']=true;
            $respon['message']='Failed to receive user request';
            $response->getBody()->write(json_encode($respon));
            return $response;
        }
        $data = $request->getParsedBody();
        #fin de obtencion de variables
        try {
                                                           
            $user_request = new User();
            $user_request->setEmail($user);
            $dataUser=$user_request->validate_step_two();
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($dataUser)) {
                    if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$dataUser;
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
  
    //-----------------------------------------------------------------------------------------------------//
    /* OBTENER LA FOTO DE PERFIL */
    $app->post('/user/pictureProfile',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        $data = $request->getParsedBody();
        
        if ($data['UserID'] !="") {
                                          
            $user=$data['UserID'];
            $type='2';
        }else{
            http_response_code(401);
            $respon = array();
            $respon['status']=401;
            $respon['error']='true';
            $respon['message']='Failed to receive UserID request';
            $response->getBody()->write(json_encode($respon));
            return $response;
        }
        #fin de obtencion de variables
        try {
                                                           
            $user_request = new User();
            $user_request->setUserID($user);
            $dataUser=$user_request->get_pictureProfile();
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($dataUser)) {

                    if (!empty($data)) {
                    //dibujar imagen 
                    
                    $rutaImagen="./picturesProfile/".$user."_".$dataUser['img'];
                    http_response_code(200);
                    $imagen = file_get_contents($rutaImagen);

                    // Establecer la respuesta como el contenido de la imagen
                    $response->getBody()->write($imagen);

                    // Establecer el tipo de contenido de la respuesta
                    return $response->withHeader('Content-Type', mime_content_type($rutaImagen));

                   /*  $respon['success']=true;
                    $respon['data']=$dataUser;
                    //echo json_encode($respon);
                    $response->getBody()->write(json_encode($respon));
                    return $response; */
                }
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
};