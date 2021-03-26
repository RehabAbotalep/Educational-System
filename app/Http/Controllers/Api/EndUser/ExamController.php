<?php

namespace App\Http\Controllers\Api\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\ExamInterface;
use Illuminate\Http\Request;

class ExamController extends Controller
{

    private $examInterface;

    public function __construct(ExamInterface $examInterface)
    {
        $this->examInterface = $examInterface;
    }

    public function examTypes()
    {
        return $this->examInterface->examTypes();
    }

    public function allExams()
    {
        return $this->examInterface->allExams();
    }

    public function addExam(Request $request)
    {
        return $this->examInterface->addExam($request);
    }

    public function updateExam(Request $request)
    {
        return $this->examInterface->updateExam($request);
    }

    public function deleteExam(Request $request)
    {
        return $this->examInterface->deleteExam($request);
    }

    public function updateExamStatus(Request $request)
    {
        return $this->examInterface->updateExamStatus($request);
    }

    public function examStudents(Request $request)
    {
        return $this->examInterface->examStudents($request);
    }

    public function examStudentDetails(Request $request)
    {
        return $this->examInterface->examStudentDetails($request);
    }


    public function markStudentEssayExamAnswers(Request $request)
    {
        return $this->examInterface->markStudentEssayExamAnswers($request);
    }

}
