<?php
namespace Domain\Helpers;

use Data\Entities\ResponseData;
use Domain\Constants\DomainConstants;

class DomainHelpers
{
    public static function createEmptyParamsResponse()
    {
        return new ResponseData(null, false, DomainConstants::$EMPTY_PARAMS_RESPONSE);
    }

}