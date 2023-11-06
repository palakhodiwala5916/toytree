<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\RequestBodyInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UserProfileBody implements RequestBodyInterface
{
    public ?UploadedFile $file = null;

    public ?string $aboutYourself = null;

    public ?string $torontoOnCanada = null;

    public ?string $city = null;

    public ?string $toronto = null;

    public ?string $english = null;


    public function __construct(Request $request)
    {
        $this->file = $request->files->get('file');
        $this->aboutYourself = $request->get('aboutYourself');
        $this->torontoOnCanada = $request->get('torontoOnCanada');
        $this->city = $request->get('city');
        $this->toronto = $request->get('toronto');
        $this->english = $request->get('english');
    }
}
