<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\StaffInterface;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\UserTrait;
use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Validator;
use Hash;

class StaffRepository implements StaffInterface
{
    use ApiResponse, UserTrait;

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAllStaff()
    {
        //filter by role
        $staff = $this->user::staffTeacher(1, 0)->with('role')->get();

        return $this->apiResponse(200,'All Staff',null, $staff);
    }


    public function addStaff($request)
    {
        return $this->addUser($request, $this->user);
    }


    public function updateStaff($request)
    {
        $validator = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id',
            'name' => 'required',
            'email' => ['required',
                    Rule::unique('users')->ignore($request->staff_id)
            ],
            'phone' => 'required',
            'password' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $staff = $this->user::staffTeacher(1, 0)->find($request->staff_id);

        if( !$staff ){
            return $this->apiResponse(404,'Staff NotFound');
        }
        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);
        return $this->apiResponse(200,'Updated Successfully');
    }

    /*delete Staff*/
    public function deleteStaff($request)
    {
        $validator = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Validation Error',$validator->errors());
        }

        $staff = $this->user::staffTeacher(1, 0)->find($request->staff_id)->delete();

        return $this->apiResponse(200,'Deleted Successfully');
    }


    public function getStaff($request)
    {
        $validator = Validator::make($request->all(),[
            'staff_id' => 'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Invalid staff id',$validator->errors());
        }
        $staff = $this->user::where('id', $request->staff_id)->staffTeacher(1, 0)->with('role')->first();

        if($staff){
            return $this->apiResponse(200,'Staff Data',null, $staff);
        }

        return $this->apiResponse(404,'Staff Not Found');
    }
}
