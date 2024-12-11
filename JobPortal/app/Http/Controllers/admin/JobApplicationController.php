<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(){
        $applications = JobApplication::orderBy("created_at","desc")->with('job', 'user', 'employer')->paginate(10);

        return view('jobApplicationsInAdmin', [
            'applications'=> $applications,
        ]);
    }

    public function destroy(Request $request){
        $id = $request->id;

        $jobApplication = JobApplication::find($id);

        if ($jobApplication == null){
            session()->flash('error', 'Job Application not found.');
            return response()->json([
                'status'=> false,
            ]);
        }

        $jobApplication->delete();
        session()->flash('success', 'Job Application has been deleted successfully.');
        return response()->json([
            'status'=> true,
        ]);
    }
}
