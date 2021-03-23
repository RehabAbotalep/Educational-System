<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\QuestionInterface;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\Upload;
use App\Models\Exam;
use App\Models\Question;
use App\Models\SystemAnswer;
use Validator;

class QuestionRepository implements QuestionInterface
{
    use ApiResponse, Upload;


    private $question;
    private $exam;
    private $systemAnswer;

    public function __construct(Question $question, Exam $exam, SystemAnswer $systemAnswer)
    {
        $this->question = $question;
        $this->exam = $exam;
        $this->systemAnswer = $systemAnswer;
    }

    public function addQuestion($request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'exam_id'  => 'required|exists:exams,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Errors',$validator->errors());
        }
        $question = $this->question::create([
            'title' => $request->title,
            'exam_id' => $request->exam_id,
        ]);
        $exam = $this->exam::where('id', $request->exam_id)->AutomatedMarked(1)->first();
        if($exam){
            $validator = Validator::make($request->all(),[
                'answer' => 'required',
            ]);

            if($validator->fails()){
                return $this->apiResponse(422,'Errors',$validator->errors());
            }
            $this->addQuestionAnswer($request->answer, $question->id);
        }
        if($request->has('image')){
            $this->addQuestionImage($question, $request->image);
        }
        return $this->apiResponse(200, 'Added Successfully');
    }


    private function addQuestionAnswer($answer, $question_id)
    {
        $this->systemAnswer::create([
            'question_id' => $question_id,
            'answer' => $answer,
        ]);
    }


    private function addQuestionImage($question, $requestedImage)
    {
        $image = $this->upload('questions', $requestedImage);
        $question->image()->create([
            'image' => $image,
        ]);
    }

    public function allQuestions($request)
    {
        $validator = Validator::make($request->all(),[
            'exam_id' => 'required|exists:exams,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $questions = $this->question::where('exam_id', $request->exam_id)->get();

        return $this->apiResponse(200,'Questions', null, $questions);
    }

    public function updateQuestion($request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'question_id'  => 'required|exists:questions,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Errors',$validator->errors());
        }
        $question = $this->question::find($request->question_id);
        $question->update([
            'title' => $request->title,
        ]);

        if($request->has('answer')){
            $this->systemAnswer::where('question_id', $question->id)->update([
                'answer' => $request->answer,
            ]);
        }
        if($request->has('image')){
            $this->deleteFile('images/questions/' . $question->image->image);
            $this->addQuestionImage($question, $request->image);
        }
        return $this->apiResponse(200, 'Added Successfully');
    }

    public function deleteQuestion($request)
    {
        $validator = Validator::make($request->all(),[
            'question_id' => 'required|exists:questions,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $this->question::find($request->question_id)->delete();

        return $this->apiResponse(200,'Deleted');
    }
}
