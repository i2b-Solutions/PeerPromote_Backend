<?php
namespace Domain\Helpers;

use Data\Entities\ResponseData;
use Domain\Constants\DomainConstants;
use Psr\Http\Message\ResponseInterface as Response;

class DomainHelpers
{
    private static function createEmptyParamsResponse()
    {
        return new ResponseData(null, false, DomainConstants::$EMPTY_PARAMS_RESPONSE);
    }

    /**
     * Generates an error ResponseData if any of the params is empty
     * otherwise returns false
     */
    public static function generateEmptyResponse(array $array)
    {
        foreach ($array as $item) {
            if (empty ($item)) {
                return self::createEmptyParamsResponse();
            }
        }
        return false;
    }

    public static function createResponseWithError(callable $dataFunction): ResponseData
    {
        try {
            return new ResponseData(
                $dataFunction(),
                true,
                ""
            );
        } catch (\Throwable $th) {
            return new ResponseData(
                null,
                false,
                $th->getMessage()
            );
        }
    }

    public static function createJsonResponse($responseData, Response $response)
    {
        $jsonResponse = json_encode($responseData);
        $response->getBody()->write($jsonResponse);
        return $response->withHeader('Content-Type', 'application/json');
    }

}