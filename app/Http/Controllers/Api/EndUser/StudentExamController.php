<?php

namespace App\Http\Controllers\Api\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\StudentExamInterface;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{

    private $studentExamInterface;

    public function __construct(StudentExamInterface $studentExamInterface)
    {
        $this->studentExamInterface = $studentExamInterface;
    }

    public function newExams()
    {
        return $this->studentExamInterface->newExams();
    }

    public function oldExams()
    {
        return $this->studentExamInterface->oldExams();
    }

    public function newStudentExam(Request $request)
    {
        return $this->studentExamInterface->newStudentExam($request);
    }

    public function storeStudentExam(Request $request)
    {
        return $this->studentExamInterface->storeStudentExam($request);
    }

}
