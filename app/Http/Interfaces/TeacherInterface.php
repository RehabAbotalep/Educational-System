<?php


namespace App\Http\Interfaces;


interface TeacherInterface
{
    public function getAllTeachers();
    public function addTeacher($request);
    public function updateTeacher($request);
    public function getTeacher($request);
    public function deleteTeacher($request);
}
