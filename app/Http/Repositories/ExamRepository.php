<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\ExamInterface;
use App\Http\Resources\GroupResource;
use App\Http\Traits\ApiResponse;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamType;
use App\Models\GroupStudent;
use App\Models\StudentExam;
use App\Models\StudentExamAnswer;
use Validator;

class ExamRepository implements ExamInterface
{
    use ApiResponse;

    private $examType;
    private $exam;
    private $groupStudent;
    private $studentExam;
    private $studentExamAnswer;
    private $examMark;

    public function __construct(ExamType $examType,
                                Exam $exam,
                                GroupStudent $groupStudent,
                                StudentExam $studentExam,
                                StudentExamAnswer $studentExamAnswer,
                                ExamMark $examMark
    )
    {
        $this->examType = $examType;
        $this->exam = $exam;
        $this->groupStudent = $groupStudent;
        $this->studentExam = $studentExam;
        $this->studentExamAnswer = $studentExamAnswer;
        $this->examMark = $examMark;
    }

    public function examTypes()
    {
        $examTypes = $this->examType::get();
        return $this->apiResponse(200, 'ExamTypes data', null, $examTypes);

    }

    public function addExam($request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'start'  => 'required',
            'end'  => 'required',
            'time'  => 'required',
            'degree'  => 'required',
            'count' => 'required',
            'type_id'  => 'required|exists:exam_types,id',
            'group_id'  => 'required|exists:groups,id',

        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $this->exam::create([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
            'count' => $request->count,
            'type_id' => $request->type_id,
            'group_id' => $request->group_id,
            'teacher_id' => auth()->id(),
        ]);
        return $this->apiResponse(200, 'Added Successfully');
    }

    public function allExams()
    {
        $user = auth()->user();
        $userRole = $user->role->name;

        if($userRole == 'Teacher'){
            $exams = $this->exam::where('teacher_id', $user->id)->get();
        }elseif ($userRole == 'Student'){
            $userGroups = $this->groupStudent::where('student_id', $user->id)
                ->where('count', '>', 0)
                ->pluck('group_id')->toArray();

            $exams = $this->exam::whereIn('group_id', $userGroups)->get();
        }
        return $this->apiResponse(200, 'Exams', null, $exams);
    }

    public function deleteExam($request)
    {
        $validator = Validator::make($request->all(),[
            'exam_id' => 'required|exists:exams,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $this->exam::find($request->exam_id)->delete();

        return $this->apiResponse(200,'Deleted');
    }

    public function updateExam($request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'start'  => 'required',
            'end'  => 'required',
            'time'  => 'required',
            'degree'  => 'required',
            'count' => 'required',
            'exam_id'  => 'required|exists:exams,id',
            'group_id'  => 'required|exists:groups,id',

        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $exam  = $this->exam->find($request->exam_id);

        $exam->update([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
            'count' => $request->count,
            'group_id' => $request->group_id,
            'teacher_id' => auth()->id(),
        ]);
        return $this->apiResponse(200, 'Updated Successfully');
    }

    public function updateExamStatus($request)
    {
        $validator = Validator::make($request->all(),[
            'exam_id' => 'required|exists:exams,id',
            'status'  => 'required|in:0,1'
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $exam = $this->exam::find($request->exam_id)->update([
            "is_closed" => $request->status,
        ]);

        return $this->apiResponse(200,'updated');
    }

    public function examStudents($request)
    {
        $validator = Validator::make($request->all(),[
            'exam_id' => 'required|exists:exams,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }

        $examStudents = $this->studentExam::where('exam_id', $request->exam_id)
                                          ->with('student')
                                          ->get();

        return $this->apiResponse(200, 'Exam Students', null, $examStudents);
    }

    public function examStudentDetails($request)
    {
        $validator = Validator::make($request->all(),[
            'student_exam_id' => 'required|exists:student_exams,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $markedExam = $this->studentExam::where('id', $request->student_exam_id)
            ->whereHas('exam', function ($query){
                $query->whereHas('type', function ($q){
                    $q->where('automated_marked', 1);
                });
            })->first();

        if($markedExam){

            $data = $this->studentExamAnswer::where('student_exam_id', $request->student_exam_id)
                                            ->with('question.systemAnswer')
                                            ->get();
        }else{
            $markedExam = $this->examMark::where('student_exam_id', $request->student_exam_id)->first();
            if($markedExam){
                $data = $this->studentExamAnswer::where('student_exam_id', $request->student_exam_id)
                                                 ->with('question.systemAnswer')
                                                  ->get();

            }else{
                $data = $this->studentExamAnswer::where('student_exam_id', $request->student_exam_id)
                                                 ->with('question.systemAnswer')
                                                 ->get(['id', 'question_id', 'answer']);

            }
        }
        return $this->apiResponse(200, 'details', null, $data);
    }


    public function markStudentEssayExamAnswers($request)
    {
        $validator = Validator::make($request->all(), [
            "degrees"    => "required|array|min:2",
            'degrees.*.student_answer_id' => 'required|exists:student_exam_answers,id',
            'degrees.*.degree' => 'required|numeric',
            'student_exam_id' => 'required|exists:student_exams,id'
        ]);
        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $student_exam_id = $request->student_exam_id;

        /** get exam to validate that degree is not greater than degree of question in exam*/
        $exam = $this->exam::whereHas('studentExams', function ($query) use($student_exam_id){
            $query->where('id', $student_exam_id);
        })->first();

        /** check if this exam is marked or not */
        $markedExam = $this->examMark::where('student_exam_id', $student_exam_id)->first();

        if($markedExam){
            return $this->apiResponse(422,'Error', 'This exam has been marked before');
        }
        $totalDegree = 0;

        foreach ($request->degrees as $degree){
            if($degree['degree'] > $exam->question_degree){
                return $this->apiResponse(422,'Error', 'Invalid degree');
            }
            $studentExamAnswer = $this->studentExamAnswer::find($degree['student_answer_id'])
                                                         ->update(['degree' => $degree['degree']]);
            $totalDegree += $degree['degree'];
        }
        /** update total degree*/
        $this->studentExam::find($student_exam_id)->update(["total_degree" => $totalDegree]);

        /** make this exam marked*/
        $this->examMark::create([
            'student_exam_id' => $student_exam_id,
        ]);

        return $this->apiResponse(200, 'Marked Successfully');


    }



}
