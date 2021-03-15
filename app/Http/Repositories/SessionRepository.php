<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\SessionInterface;
use App\Http\Traits\ApiResponse;
use App\Models\GroupSession;
use App\Models\GroupStudent;
use Validator;

class SessionRepository implements SessionInterface
{
    use ApiResponse;

    /**
     * @var GroupSession
     */
    private $groupSession;

    public function __construct(GroupSession $groupSession)
    {
        $this->groupSession = $groupSession;
    }

    public function allSessions()
    {
        $sessions = $this->groupSession->with('group:id,name')->get();
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
        $this->groupSession->create([
            'name' => $request->name,
            'link' => $request->link,
            'from' => $request->from,
            'to' => $request->to,
            'group_id' => $request->group_id,
        ]);
        GroupStudent::where('group_id', $request->group_id)->decrement("count");
        return $this->apiResponse(200, 'Added successfully');
    }

    /* can't understand scenario behind session in general*/
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
        if(! $this->validateAvailableTimeToDeleteSession($session)){
            return $this->apiResponse(422, 'can\'t delete this session');
        }
        $session->delete();
        return $this->apiResponse(200,'Deleted Successfully');
    }

    private function validateAvailableTimeToDeleteSession($session): bool
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
