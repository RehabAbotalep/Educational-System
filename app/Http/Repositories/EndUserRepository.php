<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\EndUserInterface;
use App\Http\Traits\ApiResponse;
use App\Models\Comment;
use App\Models\Complaint;
use App\Models\Discussion;
use App\Models\Group;
use App\Models\GroupDate;
use Validator;

class EndUserRepository implements EndUserInterface
{
    use ApiResponse;

    private $complaint;
    private $discussion;
    private $group;

    public function __construct(Complaint $complaint, Group $group, Discussion $discussion)
    {
        $this->complaint = $complaint;
        $this->group = $group;
        $this->discussion = $discussion;
    }

    public function sendComplaint($request)
    {
        $validation = Validator::make($request->all(),[
            'title' => 'required|string|min:5',
            'body' => 'required|string|min:5',
        ]);
        if($validation->fails()){
            return $this->apiResponse(422, 'Error', $validation->errors());
        }
        $this->complaint->create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);


        return $this->apiResponse(200, 'send successfully');
    }

    public function schedule($request)
    {
        $user = auth()->user();
        $userRole = $user->role->name;
        $userId =  $user->id;

        if($userRole == 'Teacher'){
            $userSchedule  = $this->group::where('teacher_id', $userId);

        }else if($userRole == 'Student'){

            $userSchedule  = $this->group::whereHas('groupStudents', function($query) use ($userId){
                $query->where('student_id', $userId);
            });

        }
        $userSchedule = $userSchedule->with('dates', 'teacher:id,name')->get();

        return $this->apiResponse(200, 'User Sceduale', null, $userSchedule );
    }

    public function groupTimeline($request)
    {
        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails()){
            return $this->apiResponse(422,'Error',$validation->errors());
        }
        if($this->validateUserGroup($request->group_id)){
            $groupTimeline = GroupDate::where('group_id', $request->group_id)->get();
            return $this->apiResponse(200,'Timeline', null, $groupTimeline);

        }
        return $this->apiResponse(422,'You have no access to this group');

    }

    public function addDiscussion($request)
    {
        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id',
            'title'    => 'required',
        ]);

        if($validation->fails()){
            return $this->apiResponse(422,'Error',$validation->errors());
        }

        if($this->validateUserGroup($request->group_id)){
            $this->discussion::create([
                'user_id' => auth()->id(),
                'title'   => $request->title,
                'group_id' => $request->group_id
            ]);
            return $this->apiResponse(200,'Added Successfully');
        }
        return $this->apiResponse(422,'You have no access to this group');
    }

    private function validateUserGroup($group_id)
    {
        $user = auth()->user();
        $userRole = $user->role->name;
        $userId =  $user->id;

        if($userRole == 'teacher'){
            $userGroup  = $this->group::where('teacher_id', $userId)->find($group_id);

        }else if($userRole == 'student'){

            $userGroup  = $this->group::whereHas('groupStudents', function($query) use ($userId){
                $query->where('student_id', $userId);
            })->find($group_id);

        }
        return $userGroup ;
    }

    public function allDiscussion($request)
    {
        $validation = Validator::make($request->all(),[
            'group_id' => 'required|exists:groups,id',
        ]);

        if($validation->fails()){
            return $this->apiResponse(422,'Error',$validation->errors());
        }

        if($this->validateUserGroup($request->group_id)){
            $discussions = $this->discussion::where('group_id', $request->group_id)->get();

            return $this->apiResponse(200,'All Group Discussions', null, $discussions);
        }
        return $this->apiResponse(422,'You have no access to this group');
    }

    public function discussionComment($request)
    {
        $validation = Validator::make($request->all(),[
            'discussion_id' => 'required|exists:discussions,id',
            'comment' => 'required',
        ]);

        if($validation->fails()){
            return $this->apiResponse(422,'Error',$validation->errors());
        }
        $discussion = $this->discussion->find($request->discussion_id);

        if($this->validateUserGroup($discussion->group_id)){
            Comment::create([
                'discussion_id' => $discussion->id,
                'comment' => $request->comment,
                'user_id' => auth()->id(),
            ]);
            return $this->apiResponse(200,'Added Successfully');

        }
        return $this->apiResponse(422,'You have no access to this group');
    }
}
