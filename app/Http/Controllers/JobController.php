<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\Job;


use Illuminate\Http\Request;

class JobController extends Controller
{
    //
    public function get_all_jobs()
    {
        $jobs = Job::select(
            'id',
            'about_company',
            'job_title',
            'job_link',
            'job_location',
            'work_type',
            'job_category',
            'primary_objectives',
            'job_description',
            'job_responsibilities',
            'reports_to',
            'minimum_qualification',
            'job_code',
            'created_at',
            'updated_at'
        )
            ->paginate(5);
        $jobs->getCollection()->transform(function ($job) {
            return [
                'id' => Crypt::encrypt($job->id),
                'aboutCompany' => $job->about_company,
                'jobTitle' => $job->job_title,
                'jobLink' => $job->job_link,
                'jobLocation' => $job->job_location,
                'workType' => $job->work_type,
                'jobCategory' => $job->job_category,
                'primaryObjectives' => json_decode($job->primary_objectives, true),
                'jobDescription' => $job->job_description,
                'jobResponsibilities' => json_decode($job->job_responsibilities, true),
                'reportsTo' => $job->reports_to,
                'minimumQualification' => json_decode($job->minimum_qualification, true),
                'jobCode' => $job->job_code,
                'datePosted' => $job->created_at->toDateString(),
                'dateUpdated' => $job->updated_at->toDateString(),
            ];
        });

        return response()->json([
            'message' => 'Request Successful!',
            'jobs' => $jobs,
        ], 200);
    }

    public function get_job_by_id($encryptedId)
    {
        try {
            $jobId = Crypt::decrypt($encryptedId);
            $job = Job::select(
                'id',
                'about_company',
                'job_title',
                'job_location',
                'work_type',
                'job_category',
                'primary_objectives',
                'job_description',
                'job_responsibilities',
                'reports_to',
                'minimum_qualification',
                'job_code',
                'created_at',
                'updated_at'
            )->where('id', $jobId)->first();

            if (!$job) {
                return response()->json([
                    'message' => 'Job not Found',
                ], 404);
            }

            $formattedJob = [
                'id' => Crypt::encrypt($job->id),
                'aboutCompany' => $job->about_company,
                'jobTitle' => $job->job_title,
                'jobLink' => $job->job_link,
                'jobLocation' => $job->job_location,
                'workType' => $job->work_type,
                'jobCategory' => $job->job_category,
                'primaryObjectives' => json_decode($job->primary_objectives, true),
                'jobDescription' => $job->job_description,
                'jobResponsibilities' => json_decode($job->job_responsibilities, true),
                'reportsTo' => $job->reports_to,
                'minimumQualification' => json_decode($job->minimum_qualification, true),
                'jobCode' => $job->job_code,
                'datePosted' => $job->created_at->toDateString(),
                'dateUpdated' => $job->updated_at->toDateString(),
            ];
            return response()->json([
                'message' => 'Request Successful!',
                'job' => $formattedJob,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function submit_job_application(Request $request)
    {

        // $rules = [
        //     'id'=> 'required|string',
        //     'gRecaptchaResponse' => 'required|string',
        //     'firstName' => 'required|string|max:255',
        //     'lastName' => 'required|string|max:255',
        //     'dob' => 'required|date',
        //     'gender' => 'required|string', 
        //     'address' => 'required|string|max:255',
        //     'location' => 'required|string|max:255',
        //     'referee' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'nationality' => 'required|string|max:255',
        //     'resume' => 'required|file|mimes:pdf|max:5120',
        
        //     'workExperience' => 'required|array|min:1',
        //     'workExperience.*.companyName' => 'required|string|max:255',
        //     'workExperience.*.jobPosition' => 'required|string|max:255',
        //     'workExperience.*.jobDescription' => 'nullable|string',
        //     'workExperience.*.startDate' => 'required|date|before_or_equal:workExperience.*.endDate',
        //     'workExperience.*.endDate' => 'nullable|date|after_or_equal:workExperience.*.startDate',
        
        //     'professionalQualification' => 'required|array|min:1',
        //     'professionalQualification.*.certificationName' => 'required|string|max:255',
        //     'professionalQualification.*.issuingAgency' => 'required|string|max:255',
        //     'professionalQualification.*.dateIssued' => 'required|date',
        //     'professionalQualification.*.certificationNumber' => 'nullable|string|max:255',
        
        //     'educationalHistory' => 'required|array|min:1',
        //     'educationalHistory.*.institutionName' => 'required|string|max:255',
        //     'educationalHistory.*.degreeType' => 'required|string|max:255',
        //     'educationalHistory.*.degreeClassification' => 'nullable|string|max:255',
        //     'educationalHistory.*.startDate' => 'required|date|before_or_equal:educationalHistory.*.endDate',
        //     'educationalHistory.*.endDate' => 'required|date|after_or_equal:educationalHistory.*.startDate',
        //     'educationalHistory.*.courseOfStudy' => 'required|string|max:255',
        // ];
        

        // $validator = Validator::make($request->all(), $rules);


        // if ($validator->fails()) {
        //     $messages = $validator->getMessageBag();
        //     return response()->json([
        //         'error' => $messages->first(),
        //     ], 422);
        // }

        // $secretKey = env('RECAPTCHA_SECRET_KEY');
        // $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        //     'secret' => $secretKey,
        //     'response' => $request->input('gRecaptchaResponse'),
        // ]);

        // $recaptchaData = $recaptchaResponse->json();

        // if (!$recaptchaData['success'] || $recaptchaData['score'] < 0.7) {
        //     return response()->json([
        //         'message' => 'reCAPTCHA validation failed',
        //     ], 400);
        // }


        dd($request->id);
        
        // Proceed with saving the application or performing other tasks
        // Example: saving to the database
        // JobApplication::create([
        //     'name' => $validated['name'],
        //     'email' => $validated['email'],
        //     'resume_path' => $request->file('resume')->store('resumes'),
        // ]);

        return response()->json([
            'message' => 'Application submitted successfully',
        ], 200);
    }

    }

