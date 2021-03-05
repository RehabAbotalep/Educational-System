<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\TeacherInterface;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * @var TeacherInterface
     */
    private $teacherInterface;

    public function __construct(TeacherInterface $teacherInterface)
    {
        $this->teacherInterface = $teacherInterface;
    }

    public function getAllTeachers()
    {
        return $this->teacherInterface->getAllTeachers();
    }

    public function addTeacher(Request $request)
    {
        return $this->teacherInterface->addTeacher($request);
    }

    public function updateTeacher(Request $request)
    {
        return $this->teacherInterface->updateTeacher($request);
    }

    public function getTeacher(Request $request)
    {
        return $this->teacherInterface->getTeacher($request);
    }

    public function deleteTeacher(Request $request)
    {
        return $this->teacherInterface->deleteTeacher($request);
    }

}
