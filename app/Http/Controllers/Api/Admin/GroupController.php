<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\GroupInterface;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * @var GroupInterface
     */
    private $groupInterface;

    public function __construct(GroupInterface $groupInterface)
    {
        $this->groupInterface = $groupInterface;
    }

    public function getAllGroups()
    {
        return $this->groupInterface->getAllGroups();
    }

    public function addGroup(Request $request)
    {
        return $this->groupInterface->addGroup($request);
    }

    public function updateGroup(Request $request)
    {
        return $this->groupInterface->updateGroup($request);
    }

    public function getGroup(Request $request)
    {
        return $this->groupInterface->getGroup($request);
    }

    public function deleteGroup(Request $request)
    {
        return $this->groupInterface->deleteGroup($request);
    }
}
