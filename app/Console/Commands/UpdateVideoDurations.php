<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Log;

class UpdateVideoDurations extends Command
{
    protected $signature = 'videos:update-durations';
    protected $description = 'Update all course video durations using FFMpeg';

    public function handle()
    {
        $courses = Course::whereNotNull('video_path')->get();
        $count = 0;
        
        foreach ($courses as $course) {
            try {
                $fullPath = storage_path('app/public/' . $course->video_path);
                
                if (!file_exists($fullPath)) {
                    $this->warn("File not found: {$course->video_path}");
                    continue;
                }
                
                $ffprobe = app('ffprobe');
                $duration = $ffprobe->format($fullPath)->get('duration');
                
                if (is_numeric($duration)) {
                    $durationSeconds = (int) $duration;
                    $course->duration_seconds = $durationSeconds;
                    $course->save();
                    
                    $this->info("Updated course ID {$course->id}: {$durationSeconds} seconds");
                    $count++;
                }
            } catch (\Exception $e) {
                $this->error("Error processing course ID {$course->id}: {$e->getMessage()}");
                Log::error("Error updating video duration: " . $e->getMessage());
            }
        }
        
        $this->info("Successfully updated {$count} courses.");
    }
}