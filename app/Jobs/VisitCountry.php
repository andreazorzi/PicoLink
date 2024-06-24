<?php

namespace App\Jobs;

use App\Models\Visit;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class VisitCountry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $visit;
    public $ip;

    /**
     * Create a new job instance.
     */
    public function __construct(Visit $visit, string $ip)
    {
        $this->visit = $visit;
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->visit->getCountry($this->ip);
    }
}
