<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });
    
   /* $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });*/
    //------------------------------------------------------------------------------------//
    $app->get('/user',function(Request $request, Response $response)use($app){
        try {
                                                           
            $user_request = new User();
            $data=$user_request->selectAll();
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$data;
                    //echo json_encode($respon);
                    $response->getBody()->write(json_encode($respon));
            return $response;
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

    //------------------------------------------------------------------------------------------//
    
    //------------------------------------------------------------------------------------//
    $app->post('/user/register',function(Request $request, Response $response)use($app){
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
        $IsCompany =  (isset($data['IsCompany'])) ? $data['IsCompany'] : 0;
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
            $user_request->setIsCompany($IsCompany);
            $data=$user_request->register_step_three($Birthdate,$CityID,$CountryID,$Phone,$language,0);
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
    //------------------------------------------------------------------------------------//
    $app->post('/user/auth',function(Request $request, Response $response)use($app){
            #obtengo las variables y sus datos
            
            $data = $request->getParsedBody();
            
            #$response->getBody()->write(json_encode($data));
                            #return $response;
            $user='';
            $pass='';
                if ($data['user'] !="") {
                    $user=$data['user'];
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
            #fin de obtencion de variables
            try {
                                                               
                $user_request = new User();
                $user_request->setUsername($user);
                $user_request->setPasswordHash($pass);
                $data=$user_request->login_auth();
                $respon=array();
                //$data['Headers']= $app->response->headers['Content-type'] ;
                //$app->response->setStatus(201);
                    if (!empty($data)) {
                        http_response_code(200);
                        $respon['success']='true';
                        $respon['data']=$data;
                        //echo json_encode($respon);
                        $response->getBody()->write(json_encode($respon));
                return $response;
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
    //------------------------------------------------------------------------------------------//
    $app->get('/datasocial',function(Request $request, Response $response)use($app){
        try {
            $ch = curl_init();

            $headers = array(
                'query: socialblade',
                'history: default',
                'clientid: cli_5c5ad4a1d817cf48a0287ad8',
                'token: ac783f848a0f8aa8e410adeab3762b4013edd7603793595cbca7aaf8b3d904858d0d9fb0b8670c250eeba7f9f5ff40cae7f31d275de27b7a136ca3b4f75b59c1'
            );

            $options = array(
                CURLOPT_URL => 'https://matrix.sbapis.com/b/youtube/statistics',
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
            );

            curl_setopt_array($ch, $options);

            $responcurl = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch);
            }

            curl_close($ch);
            
            $data=json_decode($responcurl);
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($data)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$data;
                    //echo json_encode($respon);
                    $response->getBody()->write(json_encode($respon));
            return $response;
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
    //------------------------------------------------------------------------------------------//
};
