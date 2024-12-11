<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\SavedJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    //This method will show the user registration page
    public function register() {
        return view('register');
    }

    //This method will save a user
    public function processRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:password_confirmation',
            'password_confirmation' => 'required'
        ]);

        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'You have registered successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        }

        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //This method will show the user login page
    public function login() {
        return view('login');
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('profile');
            } 
            
            else {
                return redirect()->route('login')->with('error', 'Email or password is incorrect');
            }
        }

        else{
            return redirect()->route('login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    public function profile(){

        $id = Auth::user()->id;

        $user = User::find($id);

        return view('profile', ['user' => $user]);
    }

    public function updateProfile(Request $request){
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);   

        if($validator->passes()) {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            session()->flash('success', 'Profile updated successfully');

            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        }

        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public function updateProfilePicture(Request $request){

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validator->passes()) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile_pic'), $imageName);

            //Create a small thumbnail
            $sourcePath = public_path('/profile_pic/' . $imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);

            //Crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel

            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile_pic/thumb/' . $imageName));

            //Delete Old profile pic

            File::delete(public_path('/profile_pic/thumb/'.Auth::user()->image));
            File::delete(public_path('/profile_pic/'.Auth::user()->image));

            User::where('id', $id)->update(['image'=> $imageName]);

            session()->flash('success','Profile Picture Updated Successfully.');

            return response()->json([
                'status'=> true,
                'errors'=> [],
            ]);
        }

        else{
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors(),
            ]);
        }
    }

    public function createJob(){

        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();

        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();
        
        return view('createJob', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
        ]);
    }

    public function saveJob(Request $request){

        $rules = [
            'title' => 'required|min:5|max:200', 
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->passes()){
            $job = new Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;

            $job->save();

            session()->flash('success', 'Job Added Successfully.');

            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        }

        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    
    }

    public function myJobs(){

        $jobs = Job::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);

        return view('myJobs', [
            'jobs' => $jobs,
        ]);
    }

    public function editJob(Request $request, $id){

        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();

        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if($job == null){
            abort(404);
        }

        return view('editJob', [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,
        ]);
    }

    public function updateJob(Request $request, $id){

        $rules = [
            'title' => 'required|min:5|max:200', 
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->passes()){
            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;

            $job->save();

            session()->flash('success', 'Job Updated Successfully.');

            return response()->json([
                'status' => true,
                'errors' => [],
            ]);
        }

        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function deleteeJob(Request $request){

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId,
        ])->first();

        if ($job == null){
            session()->flash('error', 'Job Not Found');

            return response()->json([
                'status' => true
            ]);
        }

        Job::where('id', $request->jobId)->delete();

        session()->flash('success', 'Job Deleted Successfully.');

        return response()->json([
            'status' => true
        ]);
    }

    public function myJobApplications(){
        $jobApplications = JobApplication::where('user_id', Auth::user()->id)->with(['job', 'job.jobType', 'job.applications'])->paginate(10);
        return view('myJobApplications', [
            'jobApplications' => $jobApplications
        ]);
    }

    public function removeJobs(Request $request){
        $jobApplication = JobApplication::where(['id', $request->id, 'user_id' => Auth::user()->id])->first();

        if($jobApplication == null){
            session()->flash('error','Job application not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        JobApplication::find($request->id)->delete();

        session()->flash('success','Job application removed successfully.');
        return response()->json([
            'status'=> true,
        ]);
    }

    public function savedJobs(){

        $savedJobs = SavedJob::where('user_id', Auth::user()->id)->with(['job', 'job.jobType', 'job.applications'])->orderBy('created_at', 'DESC')->paginate(10);

        return view('savedJobs',[
            'savedJobs' => $savedJobs,
        ]);
    }
    
    public function removeSavedJob(Request $request){
        $removeSavedJob = SavedJob::where(['id', $request->id, 'user_id' => Auth::user()->id])->first();

        if($removeSavedJob == null){
            session()->flash('error','Saved job not found.');
            return response()->json([
                'status' => false,
            ]);
        }

        SavedJob::find($request->id)->delete();

        session()->flash('success','Saved Job has been removed successfully.');
        
        return response()->json([
            'status'=> true,
        ]);
    }

    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        if (Hash::check($request->old_password, Auth::user()->password) == false) {

            session()->flash('error', 'Your old password is incorrect.');
            return response()->json([
                'status' => false,
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('success', 'Password Updated Successfully.');
        return response()->json([
            'status'=> true,
        ]);
    }

    public function forgotPassword(){
        return view('forgotPassword');
    }

    public function processForgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email'=> 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->route('forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(length: 60);

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        //Send Email here
        $user = User::where('email', $request->email)->first();
        $mailData = [
            'token' => $token,
            'user' => $user,
            'subject' => 'You have requested to change your password.',
        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgotPassword')->with('success', 'Reset Password Email has been sent to your inbox.');
    }

    public function resetPassword($tokenString){
        $token = \DB::table('password_reset_tokens')->where('token', $tokenString)->first();

        if ($token == null){
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token');
        }

        return view('account.resetPassword', [
            'tokenString'=> $tokenString
        ]);
    }

    public function processResetPassword(Request $request){

        $token = \DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if ($token == null){
            return redirect()->route('account.forgotPassword')->with('error', 'Invalid token');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword', $request->token)->withErrors($validator);
        }

        User::where('email', $token->email)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('account.login')->with('success', 'You have successfully changed your password.')
    }
}
