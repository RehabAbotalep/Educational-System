<?php


namespace App\Http\Interfaces;


interface QuestionInterface
{
    public function addQuestion($request);
    public function allQuestions($request);
    public function updateQuestion($request);
    public function deleteQuestion($request);


}
