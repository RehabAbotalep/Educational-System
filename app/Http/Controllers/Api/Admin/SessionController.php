<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\SessionInterface;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * @var SessionInterface
     */
    private $sessionInterface;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->sessionInterface = $sessionInterface;
    }

    public function allSessions()
    {
        return $this->sessionInterface->allSessions();
    }

    public function addSession(Request $request)
    {
        return $this->sessionInterface->addSession($request);
    }

    public function deleteSession(Request $request)
    {
        return $this->sessionInterface->deleteSession($request);
    }
}
