<?php

namespace App\Http\Controllers\Api\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\StudentInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @var StudentInterface
     */
    private $studentInterface;

    public function __construct(StudentInterface $studentInterface)
    {
        $this->studentInterface = $studentInterface;
    }

    public function saveAttendance(Request $request)
    {
        return $this->studentInterface->saveAttendance($request);
    }
}
