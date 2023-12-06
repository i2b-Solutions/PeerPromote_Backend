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
};
