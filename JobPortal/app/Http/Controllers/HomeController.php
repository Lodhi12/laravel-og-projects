<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This method will show our index page
    public function index(){

        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->take(8)->get();

        $newCategories = Category::where('status', 1)->orderBy('name', 'ASC')->get();

        $featuredJobs = Job::where('status', 1)->orderBy('created_at', 'DESC')->with('jobType')->where('isFeatured', 1)->take(6)->get();
        
        $latestJobs = Job::where('status', 1)->orderBy('created_at', 'DESC')->where('isFeatured', 1)->take(6)->get();
        
        return view('home', [
            'categories' => $categories,
            'featuredJobs' => $featuredJobs,
            'latestJobs' => $latestJobs,
            'newCategories'=> $newCategories,
        ]);
    }
}
