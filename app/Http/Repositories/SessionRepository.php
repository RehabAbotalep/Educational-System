<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\SessionInterface;
use App\Http\Traits\ApiResponse;
use App\Models\Attendance;
use App\Models\GroupSession;
use App\Models\GroupStudent;
use Validator;

class SessionRepository implements SessionInterface
{
    use ApiResponse;

    private $groupSession;
    private $groupStudent;

    public function __construct(GroupSession $groupSession, GroupStudent $groupStudent)
    {
        $this->groupSession = $groupSession;
        $this->groupStudent = $groupStudent;
    }

    public function allSessions()
    {
        $sessions = $this->groupSession::whereIsDeleted(0)->with('group:id,name')->get();
        return $this->apiResponse(200, 'All Sessions', null, $sessions);
    }

    public function addSession($request)
    {
        $validation = Validator::make($request->all(),[
            'name' => 'required|string',
            'link' => 'required|url',
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from',
            'group_id' => 'required|exists:groups,id',
        ]);
        if($validation->fails()){
            return $this->apiResponse(422, 'Error', $validation->errors());
        }

        $this->groupSession::create([
            'name' => $request->name,
            'link' => $request->link,
            'from' => $request->from,
            'to' => $request->to,
            'group_id' => $request->group_id,
        ]);
        $this->groupStudent::where('group_id', $request->group_id)
                    ->where('count', '>' , 0)
                    ->decrement("count");

        return $this->apiResponse(200, 'Added successfully');
    }

    //dont hard delete it(soft-delete)
    public function deleteSession($request)
    {
        $validator = Validator::make($request->all(),[
            'session_id' => 'required|exists:group_sessions,id',
        ]);

        if($validator->fails()){
            return $this->apiResponse(422,'Error',$validator->errors());
        }
        $session = $this->groupSession->find($request->session_id);

        $sessionAttendance = Attendance::where('session_id', $session->id )->count();

        if(! $this->validateAvailableTimeToDeleteSession($session)|| $sessionAttendance > 0){
            return $this->apiResponse(422, 'can\'t delete this session');
        }
        $session->update(['is_deleted' => 1]);
        $this->groupStudent::where('group_id', $session->group_id)
                            ->increment("count");

        return $this->apiResponse(200,'Deleted Successfully');
    }

    public function validateAvailableTimeToDeleteSession($session): bool
    {
        $currentDateTime = now();
        $currentTime = $currentDateTime->format('H:i');
        $currentDate = $currentDateTime->format('Y-m-d');

        $sessionDate = $session->created_at->format('Y-m-d');

        /* Validate that time is available to delete session*/
        if( $currentDate == $sessionDate && $currentTime >= $session->from  && $currentTime <= $session->to){
            return false;
        }
        return true;
    }
}
