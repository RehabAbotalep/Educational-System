<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\ExamInterface;
use App\Http\Resources\GroupResource;
use App\Http\Traits\ApiResponse;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\GroupStudent;
use Validator;

class ExamRepository implements ExamInterface
{
    use ApiResponse;

    private $examType;
    private $exam;
    private $groupStudent;

    public function __construct(ExamType $examType, Exam $exam, GroupStudent $groupStudent)
    {
        $this->examType = $examType;
        $this->exam = $exam;
        $this->groupStudent = $groupStudent;
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
            'type_id'  => 'required|exists:exam_types,id',
            'group_id'  => 'required|exists:groups,id',

        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $this->exam->create([
            'name' => $request->name,
            'start' => $request->start,
            'end' => $request->end,
            'time' => $request->time,
            'degree' => $request->degree,
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
        $exam = $this->exam::find($request->exam_id)->delete();

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

}
