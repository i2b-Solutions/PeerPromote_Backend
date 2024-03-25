<?php
namespace Domain\Helpers;

// Import the DateTime class
use DateTime;

class UserHelpers
{
    public static function determineRangeOfSelfie($userId)
    {
        return floor($userId / 1000) * 1000;
    }

    public static function generateSelfieLocation()
    {
        $currentDateTime = new DateTime();
        $formattedDateTime = $currentDateTime->format('Y/m/d/H');

        return $formattedDateTime;
    }

    public static function saveSelfie(string $selfieLocation, int $userId, $selfieFile)
    {
        $rangeOfSelfie = self::determineRangeOfSelfie($userId);
        $directory = __DIR__ . '/uploads/selfies/' . $selfieLocation . '/' . $rangeOfSelfie;
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $selfieFileName = $userId . '.JPEG';
        $selfiePath = $directory . '/' . $selfieFileName;
        $selfieFile->moveTo($selfiePath);
    }
}
