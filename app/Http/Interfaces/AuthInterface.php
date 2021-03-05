<?php


namespace App\Http\Interfaces;


interface AuthInterface
{
    public function login($request);
    public function updatePassword($request);
}
