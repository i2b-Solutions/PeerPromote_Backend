<?php
namespace Data\Services;

use Data\Dependencies\DependencyManager;
use Data\Entities\UserEntities\RegisterUserRequestData;
use Data\Entities\UserEntities\RegisterUserResponseData;

class UserServices
{
    private static function queryUserDataByField(string $fieldName, string $fieldValue)
    {
        $db = DependencyManager::getDatabase();
        $query = "SELECT * FROM Users WHERE $fieldName = :value";
        return $db->query($query, [':value' => $fieldValue])->fetch();
    }

    public static function queryUsernameData(string $username)
    {
        return self::queryUserDataByField('Username', $username);
    }

    public static function queryEmailData(string $email)
    {
        return self::queryUserDataByField('Email', $email);
    }

    public static function queryRegisterUserData(RegisterUserRequestData $user): RegisterUserResponseData
    {
        $db = DependencyManager::getDatabase();

        try {
            $db->pdo->beginTransaction();

            $passwordHash = password_hash($user->pass, PASSWORD_DEFAULT);

            $userData = [
                'Username' => $user->user,
                'PasswordHash' => $passwordHash,
                'Email' => $user->email,
                'Blocked' => 0,
                'IsCompany' => $user->isCompany,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->insert('Users', $userData);

            $userID = $db->id();

            $personalInformationData = [
                'UserID' => $userID,
                'Birthdate' => $user->birthdate,
                'CityID' => $user->cityId,
                'CountryID' => $user->countryId,
                'Phone' => $user->phone,
                "PhoneArea" => $user->area,
                "ProfilePhotoImageFileName" => $user->selfieLocation . '/' . $userID . ".JPEG"
            ];

            $db->insert('PersonalInformation', $personalInformationData);

            $personID = $db->id();

            $languagesData = [];
            foreach ($user->languages as $language) {
                $languagesData[] = [
                    'PersonID' => $personID,
                    'IDLang' => $language['lang'],
                ];
            }

            $db->insert('Languages', $languagesData);

            $db->pdo->commit();

            return new RegisterUserResponseData(intval($userID), $user->user);

        } catch (\Exception $e) {
            $db->pdo->rollBack();
            throw new \Exception("Error when registering the user" . $e->getMessage());
        }
    }

}