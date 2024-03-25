<?php
namespace Data\Entities\UserEntities;

class RegisterUserRequestData
{
    public string $user;
    public string $pass;
    public string $confirmPassword;
    public string $countryId;
    public int $isCompany;
    public int $cityId;
    public array $languages;
    public string $birthdate;
    public string $email;
    public string $area;
    public string $phone;
    public $selfie;

    public $selfieLocation;

    function __construct(
        string $user,
        string $pass,
        string $countryId,
        int $isCompany,
        int $cityId,
        array $languages,
        string $birthdate,
        string $email,
        string $area,
        string $phone,
        $selfie,
        string $selfieLocation
    ) {
        $this->user = $user;
        $this->pass = $pass;
        $this->countryId = $countryId;
        $this->isCompany = $isCompany;
        $this->cityId = $cityId;
        $this->languages = $languages;
        $this->birthdate = $birthdate;
        $this->email = $email;
        $this->area = $area;
        $this->phone = $phone;
        $this->selfie = $selfie;
        $this->selfieLocation = $selfieLocation;
    }

    public function toArray(): array
    {
        return [
            $this->user,
            $this->pass,
            $this->countryId,
            $this->isCompany,
            $this->cityId,
            $this->languages,
            $this->birthdate,
            $this->email,
            $this->area,
            $this->phone,
            $this->selfie,
            $this->selfieLocation
        ];
    }
}
