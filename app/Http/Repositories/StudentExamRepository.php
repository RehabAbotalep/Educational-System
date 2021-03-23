<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\StudentExamInterface;
use App\Http\Resources\ExamQuestionResource;
use App\Http\Traits\ApiResponse;
use App\Models\Exam;
use App\Models\Question;
use App\Models\StudentExam;
use App\Models\StudentExamAnswer;
use App\Models\SystemAnswer;
use Validator;

class StudentExamRepository implements StudentExamInterface
{
    use ApiResponse;

    private $exam;
    private $question;
    private $studentExam;
    private $systemAnswer;

    public function __construct(Exam $exam, Question $question, StudentExam $studentExam, SystemAnswer $systemAnswer)
    {
        $this->exam = $exam;
        $this->question = $question;
        $this->studentExam = $studentExam;
        $this->systemAnswer = $systemAnswer;
    }

    public function newExams()
    {
        $newExams = $this->exam::closed(0)->whereHas('studentGroups', function($query){
            $query->where('student_id', auth()->id())->where('count', '>', 0);
        })->get();
        return $this->apiResponse(200, 'New Exams', null, $newExams);
    }

    public function oldExams()
    {
        // TODO: Implement oldExams() method.
    }

    public function newStudentExam($request)
    {
        $validator = Validator::make($request->all(),[
            'exam_id'  => 'required|exists:exams,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $exam = $this->exam::select('count')->find($request->exam_id);

        $questions =  $this->question::where('exam_id', $request->exam_id)
                                     ->limit($exam->count)
                                     ->with('image')
                                     ->get();

        return $this->apiResponse(200, 'new Student Exam', null, ExamQuestionResource::collection($questions));
    }

    public function storeStudentExam($request)
    {
        /*Validation*/
        $exam = $this->exam::automatedMarked(1)->closed(0)->find($request->exam_id);

        $studentExam = $this->addStudentExam($request->exam_id);

        /*True&false - Choice*/
        if($exam){
            $questionDegree = $exam->question_degree;
            $totalDegree = 0;

            foreach ($request->questions as $question){
                $questionSystemAnswer = $this->systemAnswer::where('question_id', $question['question'])->value('answer');

                if($question['answer'] == $questionSystemAnswer){
                    $degree = $questionDegree;
                    $totalDegree +=$questionDegree;
                }else{
                    $degree = 0;
                }
                StudentExamAnswer::create([
                    'student_exam_id' => $studentExam->id,
                    'question_id' => $question['question'],
                    'degree' => $degree,
                ]);
            }
            $studentExam->update([
                'total_degree' => $totalDegree
            ]);
        }
        /*Essays*/
        else{

        }
        return $this->apiResponse(200, 'Done', null, $totalDegree);
    }

    public function addStudentExam($exam_id)
    {
        $studentExam = $this->studentExam::create([
            'exam_id' => $exam_id,
            'student_id' => auth()->id(),
        ]);
        return $studentExam;
    }

}
