<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\TeacherInterface;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\UserTrait;
use App\Models\Role;
use App\Models\User;
use App\Rules\ValidGroupId;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Hash;

class TeacherRepository implements TeacherInterface
{
    use ApiResponse, UserTrait;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAllTeachers()
    {
        $teachers = $this->user::staffTeacher(0, 1)->with('role')->get();
        return $this->apiResponse(200,'All Teachers',null, $teachers);
    }

    public function addTeacher($request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required',
        ]);

       $this->user::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('is_staff', 0)->where('is_teacher', 1)->value('id'),
       ]);

        return $this->apiResponse(200,'Added Successfully');
    }

    public function updateTeacher($request)
    {
        $validator = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id',
            'name' => 'required',
            'email' => ['required',
                Rule::unique('users')->ignore($request->teacher_id)
            ],
            'phone' => 'required',
            'password' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $teacher = $this->user::staffTeacher(0, 1)->find($request->teacher_id);

        if( !$teacher ){
            return $this->apiResponse(404,'Teacher NotFound');
        }
        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);
        return $this->apiResponse(200,'Updated Successfully');
    }

    public function getTeacher($request)
    {
        $validator = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $teacher = $this->user::where('id', $request->teacher_id)->staffTeacher(0, 1)->with('role')->first();
        if($teacher){
            return $this->apiResponse(200,'Teacher Data',null, $teacher);
        }
        return $this->apiResponse(404,'Teacher Not Found');


    }

    public function deleteTeacher($request)
    {
        $validator = Validator::make($request->all(),[
            'teacher_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }

        $teacher = $this->user::staffTeacher(0, 1)->find($request->teacher_id)->delete();
        return $this->apiResponse(200,'Deleted Successfully');

    }
}
