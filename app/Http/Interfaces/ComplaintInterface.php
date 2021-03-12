<?php


namespace App\Http\Interfaces;


interface ComplaintInterface
{
    public function allComplaints();

    public function getComplaint($request);

    public function deleteComplaint($request);

}
