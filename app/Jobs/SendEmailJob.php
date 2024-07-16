<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyMail;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
// use function Illuminate\Support\Carbon\now;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
     protected $emailDetails;

    public function __construct($emailDetails)
    {
        $this->emailDetails = $emailDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "Enviando email a: " . $this->emailDetails['email'] . "\n";
        $cacheKey = "email_quota_daily_limit";
        $quotaLimit = env('EMAIL_QUOTA_LIMIT', 2);

        $currentHour = Carbon::now()->hour;

        if ($currentHour < 8 || $currentHour > 20) {
            Log::info('Email sending time is outside of 8am to 7pm. Job released back to the queue for retry in 10 seconds.');
            // Delay 4 hours 
            return $this->release(14400);
        }
    
        Log::info('Sending email to: ' . $this->emailDetails['email']);
    
        if (Cache::has($cacheKey)) {
            $emailCount = Cache::get($cacheKey, 0);
            Log::info('Email count: ' . $emailCount);
    
            if ($emailCount < $quotaLimit) {
                Log::info('Sending email now.');
                Mail::to($this->emailDetails['email'])->send(new VerifyMail($this->emailDetails));
                Cache::put($cacheKey, $emailCount + 1, Carbon::now()->endOfDay());
                
                
                // Replace for Testing with 5 minutes
                // Cache::put($cacheKey, $emailCount + 1, Carbon::now()->addMinute(5));

                // end of the day
                Log::info('Email sent and quota updated. New email count: ' . ($emailCount + 1) . ' at ' . Carbon::now()->endOfDay());
            } else {
                Log::info('Email quota limit reached at ' . Carbon::now());
                Log::info('Job released back to the queue for retry in - seconds.');
                return $this->release(300);
            }
        } else {
            Log::info('Cache key does not exist. Sending first email and initializing quota.');
            Mail::to($this->emailDetails['email'])->send(new VerifyMail($this->emailDetails));
            Cache::put($cacheKey, 1, Carbon::now()->addDay());
            Log::info('Email sent and quota initialized. New email count: 1');
        }
    }


    public function failed(\Exception $exception)
    {
        Log::error('Error in sending email: ' . $exception->getMessage());
        // Retry the job in 10 minutes
        return $this->release(600);
    }
}
