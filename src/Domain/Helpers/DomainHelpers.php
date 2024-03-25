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

    private static function createErrorResponse(string $message)
    {
        return new ResponseData(null, false, $message);
    }

    public static function generateErrorResponseData(\Throwable $th, Response $response): ResponseData
    {
        return self::createErrorResponse($th->getMessage());
    }
    /**
     * Generates an error ResponseData if any of the params is empty
     * otherwise returns false
     */
    public static function generateEmptyResponseData(array $array): bool|ResponseData
    {
        foreach ($array as $item) {
            if (empty ($item)) {
                return self::createEmptyParamsResponse();
            }
        }
        return false;
    }

    public static function generateOkResponseData($data): ResponseData
    {
        return new ResponseData(
            $data,
            true,
            ""
        );
    }

    public static function createJsonResponse(ResponseData $responseData, Response $response): Response
    {
        $jsonResponse = json_encode($responseData);
        $response->getBody()->write($jsonResponse);
        return $response->withHeader('Content-Type', 'application/json');
    }

}