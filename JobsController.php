<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    //this method will show job page
    public function index(Request $request){

       $categories =  Category::where('status', 1)->get();
       $jobTypes =  JobType::where('status', 1)->get();
        
       $jobs = Job::where('status',1);
       
       //search using keyword
       if(!empty($request->keyword)){
            $jobs = $jobs->where(function($query)use($request){
                $query->orWhere('title','like','%'. $request->keyword. '%');
                $query->orWhere('keywords','like','%'. $request->keyword. '%');
            });
       }

       //search using location
       if(!empty($request->location)){
        $jobs = $jobs->where('location', $request->location);
       }

       //search using category
       if(!empty($request->category)){
        $jobs = $jobs->where('category_id', $request->category);
       }

       $jobTypeArray = [];
       //search using JobType
       if(!empty($request->jobType)){
        //e.g. 1,2,3
       $jobTypeArray = explode(',', $request->jobType);
       $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
       }

       //search using Experience
       if(!empty($request->experience)){
       $jobs = $jobs->where('experience',$request->experience);
       }

       
       $jobs = $jobs->with(['jobType','category']);
       if($request->sort == '0' ){
        $jobs= $jobs->orderBy('created_at','ASC');
       }else{
        $jobs= $jobs->orderBy('created_at','DESC');
       }
       
       
       $jobs = $jobs->paginate(9);

       
        return  view('front.jobs',[
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray
        ]);
    }

    //ths method will show job detail page
    public function detail($id){
        $job  = Job::where([
            'id' => $id,
            'status' => 1
        ])->with(['jobType','category'])->first();

        if($job == null){
            abort(404);
        }
        
        $count = 0;
        if(Auth::user()){
            $count =  SavedJob::where([
                'user_id' =>Auth::user()->id,
                'job_id' => $id
            ])->count();
        }

        //FETCH APPLICANTS
        $applications = JobApplication::where('job_id',$id)->with('user')->get();
        // dd($applications);
       

        
        return view('front.jobDetail',[
            'job' =>$job,
            'count' => $count,
            'applications' => $applications
        ]);
    }

    public function applyJob(Request $request){
        $id = $request->id;
        $job = Job::where('id',$id)->first();
        //if job not found in db
        if($job == null){
            session()->flash('error','Job does not exist');
            return response()->json([
                'status' => false,
                'message' => 'Job does not exist'
            ]);
        }
        
        //you can not apply on your own job
        $employer_id = $job->user_id;
        if($employer_id == Auth::user()->id){
            session()->flash('error','You cannot apply on your own job');
            return response()->json([
                'status' => false,
                'message' => 'You cannot apply on your own job'
            ]);
        }
        //You can not apply on job more than once
        $jobApplicationCount = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if($jobApplicationCount > 0){
            session()->flash('error','You already applied on this job.');
            return response()->json([
                'status' => false,
                'message' => 'You already applied on this job.'
            ]);
        }

        
        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date= now();
        $application->save();

        //send notification Email to the Employer
        $employer =User::where('id', $employer_id)->first();
        $mailData =[
            'employer' =>$employer,
            'user' => Auth::user(),
            'job' => $job
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        session()->flash('success','You have successfully Applied');
            return response()->json([
                'status' => true,
                'message' => 'You have successfully Applied'
            ]);
    }

    public function saveJob(Request $request){
        $id = $request->id;
        //check if job is empty
        $job =Job::find($id);
        if($job == null){
            session()->flash('error','Job Not Found.');
            return response()->json([
                'status' => false,
                'message' => 'Job Not Found.'
            ]);
        }
        
        //check if user already save the job
       $count =  SavedJob::where([
            'user_id' =>Auth::user()->id,
            'job_id' => $id
        ])->count();

        if($count >0){
            session()->flash('error','You Already Saved this Job.');
            return response()->json([
                'status' => false,
                'message' => 'Job Not Found.'
            ]);
        }

        $savedJob = new SavedJob;
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();
        session()->flash('success','You have successfully saved this job.');
        return response()->json([
            'status' => true,
            'message' => 'You have successfully saved this job.'
        ]);
        
        
        
    }
    
}