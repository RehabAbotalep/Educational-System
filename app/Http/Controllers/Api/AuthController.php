<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\AuthInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * @var AuthInterface
     */
    private $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        return $this->authInterface->login($request);
    }

    public function updatePassword(Request $request)
    {
        return $this->authInterface->updatePassword($request);
    }
}
