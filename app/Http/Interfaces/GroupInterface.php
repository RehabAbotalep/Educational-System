<?php


namespace App\Http\Interfaces;


interface GroupInterface
{
    public function getAllGroups();
    public function addGroup($request);
    public function updateGroup($request);
    public function getGroup($request);
    public function deleteGroup($request);
}
