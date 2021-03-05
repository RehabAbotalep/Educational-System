<?php

namespace App\Http\Traits;
use App\Models\Role;
use Validator;
use Hash;
use App\Http\Traits\ApiResponse;

trait UserTrait{

    use ApiResponse;

    public function addTeacherStudent($request, $user){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $user->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);
        return $this->apiResponse(200,'Added Successfully');

    }
    public function getRoleId($is_staff, $is_teacher)
    {
        return Role::where('is_staff', $is_staff)->where('is_teacher', $is_teacher)->value('id');
    }

}
