<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\StudentInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @var StudentInterface
     */
    private $studentInterface;

    public function __construct(StudentInterface $studentInterface)
    {
        $this->studentInterface = $studentInterface;
    }

    public function getAllStudents()
    {
        return $this->studentInterface->getAllStudents();
    }

    public function addStudent(Request $request)
    {
        return $this->studentInterface->addStudent($request);
    }

    public function updateStudent(Request $request)
    {
        return $this->studentInterface->updateStudent($request);
    }

    public function getStudent(Request $request)
    {
        return $this->studentInterface->getStudent($request);
    }

    public function deleteStudent(Request $request)
    {
        return $this->studentInterface->deleteStudent($request);
    }
}
