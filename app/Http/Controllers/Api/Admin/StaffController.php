<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\StaffInterface;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * @var StaffInterface
     */
    private $staffInterface;

    public function __construct(StaffInterface $staffInterface)
    {
        $this->staffInterface = $staffInterface;
    }

    /* get all staff */
    public function getAllStaff()
    {
        return $this->staffInterface->getAllStaff();
    }

    /* add new staff */
    public function addStaff(Request $request)
    {
        return $this->staffInterface->addStaff($request);
    }

    /* getStaffById */
    public function getStaff(Request $request)
    {
        return $this->staffInterface->getStaff($request);
    }

    /* update staff */
    public function updateStaff(Request $request)
    {
        return $this->staffInterface->updateStaff($request);
    }

    /* delete staff */
    public function deleteStaff(Request $request)
    {
        return $this->staffInterface->deleteStaff($request);
    }
}
