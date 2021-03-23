<?php


namespace App\Http\Interfaces;


interface ExamInterface
{
    public function examTypes();
    public function addExam($request);
    public function allExams();
    public function deleteExam($request);
    public function updateExam($request);
    public function updateExamStatus($request);

}
