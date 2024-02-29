<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {  
    //---------------------------------STEP TWO VALIDATE---------------------------------------------------//
    $app->post('/countries/register',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        $data = $request->getParsedBody();
        
        #fin de obtencion de variables
        try {
            $datos = file_get_contents( "countries.json" );
            $id= 66;
            $objetos = json_decode($datos, true);

            foreach ($objetos as $objeto) {
          //  echo "<a href='".(($id === 1)?$objeto["data"]["proceso"]:$objeto["data"]["detalle"])."'>hola</a>";
                if ($objeto["id"]==$id) {
                    $country_request = new Country();
                    $country_request->setCountryID($objeto["id"]);
                    $country_request->setCountryName($objeto["name"]);
                    $country_request->setCountryISO($objeto["iso2"]);
                    $dataUser=$country_request->save();
                }
           
            }                                            
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($dataUser)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$dataUser;
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
    //---------------------------------STEP CITIES---------------------------------------------------//
    $app->post('/cities/register',function(Request $request, Response $response)use($app){
        #obtengo las variables y sus datos
        $data = $request->getParsedBody();
        
        #fin de obtencion de variables
        try {
            $datos = file_get_contents( "https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/states.json" );
            $id= 66;
            $objetos = json_decode($datos, true);

            foreach ($objetos as $objeto) {
          //  echo "<a href='".(($id === 1)?$objeto["data"]["proceso"]:$objeto["data"]["detalle"])."'>hola</a>";
                if ($objeto["country_id"]==$id) {
                    $country_request = new Country();
                    $country_request->setCountryID($objeto["country_id"]);
                    $dataUser=$country_request->saveCity($objeto["id"],$objeto["name"]);
                }
           
            }                                            
            $respon=array();
            //$data['Headers']= $app->response->headers['Content-type'] ;
            //$app->response->setStatus(201);
                if (!empty($dataUser)) {
                    http_response_code(200);
                    $respon['success']='true';
                    $respon['data']=$dataUser;
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
};