<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Log;

class FFMpegServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('ffmpeg', function ($app) {
            try {
                $config = [
                    'ffmpeg.binaries'  => env('FFMPEG_PATH', 'ffmpeg'),
                    'ffprobe.binaries' => env('FFPROBE_PATH', 'ffprobe'),
                    'timeout'          => 3600,
                    'ffmpeg.threads'   => 12,
                ];
                
                return FFMpeg::create($config);
            } catch (\Exception $e) {
                Log::error('Failed to create FFMpeg instance: ' . $e->getMessage());
                throw $e;
            }
        });
        
        $this->app->singleton('ffprobe', function ($app) {
            try {
                $config = [
                    'ffmpeg.binaries'  => env('FFMPEG_PATH', 'ffmpeg'),
                    'ffprobe.binaries' => env('FFPROBE_PATH', 'ffprobe'),
                    'timeout'          => 60,
                ];
                
                return FFProbe::create($config);
            } catch (\Exception $e) {
                Log::error('Failed to create FFProbe instance: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}