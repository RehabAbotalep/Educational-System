<?php


namespace App\Http\Interfaces;


interface SessionInterface
{

    public function allSessions();
    public function addSession($request);
    public function deleteSession($request);
}
