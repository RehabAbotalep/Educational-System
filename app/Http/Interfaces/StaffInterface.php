<?php


namespace App\Http\Interfaces;


interface StaffInterface
{
    public function getAllStaff();
    public function addStaff($request);
    public function updateStaff($request);
    public function deleteStaff($request);
    public function getStaff($request);
}
