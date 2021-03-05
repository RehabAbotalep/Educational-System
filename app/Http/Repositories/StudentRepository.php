<?php


namespace App\Http\Repositories;

use App\Http\Interfaces\StudentInterface;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\UserTrait;
use App\Models\GroupStudent;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Validator;
use Hash;

class StudentRepository implements StudentInterface
{
    use ApiResponse , UserTrait;

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAllStudents()
    {
        $students = $this->user::staffTeacher(0, 0)->withCount('groups')->get();
        return $this->apiResponse(200,'All Students',null, $students);
    }

    public function addStudent($request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }

        $student = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('is_staff', 0)->where('is_teacher', 0)->value('id'),
        ]);
        if($request->has('groups')){
            foreach($request->groups as $group){

                $this->addStudentToGroup($group, $student->id);
            }

        }

        return $this->apiResponse(200,'Added Successfully');
    }

    private function addStudentToGroup($group, $student_id)
    {
        $addedGroup = explode(',', $group);
        GroupStudent::create([
            'student_id' => $student_id,
            'group_id'   => $addedGroup[0],
            'count'      => $addedGroup[1],
            'price'      => $addedGroup[2],
        ]);
    }

    public function updateStudent($request)
    {
        $validator = Validator::make($request->all(),[
            'student_id' => 'required|exists:users,id',
            'name' => 'required',
            'email' => ['required',
                Rule::unique('users')->ignore($request->student_id)
            ],
            'phone' => 'required',
            'password' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $student = $this->user::staffTeacher(0, 0)->find($request->student_id);

        if( !$student ){
            return $this->apiResponse(404,'Student NotFound');
        }
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);
        if($request->has('groups')){
            foreach($request->groups as $group){
                $addedGroup = explode(',', $group);
                if($addedGroup[3] == 1){
                    GroupStudent::where('student_id', $student->id)
                                ->where('group_id', $addedGroup[0])
                                ->delete();
                }else{
                    GroupStudent::updateOrCreate(
                        ['student_id' => $student->id, 'group_id' => $addedGroup[0]],
                        ['count' => $addedGroup[1], 'price' => $addedGroup[2]],
                    );
                }
            }
        }
        return $this->apiResponse(200,'Updated Successfully');
    }

    public function getStudent($request)
    {
        $validator = Validator::make($request->all(),[
            'student_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $student = $this->user::where('id', $request->student_id)->staffTeacher(0, 0)
                                ->with('role')
                                ->with('groups.group')
                                ->first();
        if($student){
            return $this->apiResponse(200,'Student Data',null, $student);
        }
        return $this->apiResponse(404,'Student Not Found');
    }

    public function deleteStudent($request)
    {
        $validator = Validator::make($request->all(),[
            'student_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }

        $student = $this->user::staffTeacher(0, 0)->find($request->student_id)->delete();
        return $this->apiResponse(200,'Deleted Successfully');
    }
}
