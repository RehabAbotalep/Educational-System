<?php


namespace App\Http\Repositories;


use App\Http\Interfaces\ComplaintInterface;
use App\Http\Traits\ApiResponse;
use App\Models\Complaint;
use Illuminate\Support\Facades\Validator;

class ComplaintRepository implements ComplaintInterface
{
    use ApiResponse;

    private $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function allComplaints()
    {
        $complaints = $this->complaint::with('sender:id,name')->get();
        return $this->apiResponse(200, 'Complaints', null, $complaints);
    }

    public function getComplaint($request)
    {
        $validation = Validator::make($request->all(),[
            'complaint_id' => 'required|exists:complaints,id',
        ]);

        if($validation->fails()){
            return $this->apiResponse(422,'Error',$validation->errors());
        }
        $complaint = $this->complaint::with('sender')->find($request->complaint_id);

        return $this->apiResponse(200,'Complaint Data', null, $complaint);
    }

    public function deleteComplaint($request)
    {
        $validation = Validator::make($request->all(),[
            'complaint_id' => 'required|exists:complaints,id',
        ]);

        if($validation->fails()){
            return $this->apiResponse(422,'Error',$validation->errors());
        }
        $complaint = $this->complaint::find($request->complaint_id)->delete();

        return $this->apiResponse(200,'Deleted');
    }
}
