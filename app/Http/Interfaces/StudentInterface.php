<?php


namespace App\Http\Interfaces;


interface StudentInterface
{
    public function getAllStudents();
    public function addStudent($request);
    public function updateStudent($request);
    public function getStudent($request);
    public function deleteStudent($request);
    public function saveAttendance($request);
}
