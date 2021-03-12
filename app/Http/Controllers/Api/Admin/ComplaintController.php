<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\ComplaintInterface;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * @var ComplaintInterface
     */
    private $complaintInterface;

    public function __construct(ComplaintInterface $complaintInterface)
    {
        $this->complaintInterface = $complaintInterface;
    }

    public function allComplaints()
    {
        return $this->complaintInterface->allComplaints();
    }

    public function getComplaint(Request $request)
    {
        return $this->complaintInterface->getComplaint($request);
    }

    public function deleteComplaint(Request $request)
    {
        return $this->complaintInterface->deleteComplaint($request);
    }
}
