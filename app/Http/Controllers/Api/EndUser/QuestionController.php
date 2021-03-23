<?php

namespace App\Http\Controllers\Api\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\QuestionInterface;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    private $questionInterface;

    public function __construct(QuestionInterface $questionInterface)
    {
        $this->questionInterface = $questionInterface;
    }

    public function addQuestion(Request $request)
    {
        return $this->questionInterface->addQuestion($request);
    }

    public function allQuestions(Request $request)
    {
        return $this->questionInterface->allQuestions($request);
    }

    public function updateQuestion(Request $request)
    {
        return $this->questionInterface->updateQuestion($request);
    }

    public function deleteQuestion(Request $request)
    {
        return $this->questionInterface->deleteQuestion($request);
    }
}
