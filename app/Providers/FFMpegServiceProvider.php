<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;

class FFMpegServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('ffmpeg', function () {
            return FFMpeg::create([
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',   // ปรับเปลี่ยนตามตำแหน่งที่ติดตั้ง
                'ffprobe.binaries' => '/usr/bin/ffprobe',  // ปรับเปลี่ยนตามตำแหน่งที่ติดตั้ง
                'timeout'          => 3600,                // คือ timeout ในหน่วยวินาที
                'ffmpeg.threads'   => 12,                  // จำนวน threads ที่ใช้
            ]);
        });
        
        $this->app->singleton('ffprobe', function () {
            return FFProbe::create([
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',   // ปรับเปลี่ยนตามตำแหน่งที่ติดตั้ง
                'ffprobe.binaries' => '/usr/bin/ffprobe',  // ปรับเปลี่ยนตามตำแหน่งที่ติดตั้ง
                'timeout'          => 60,                  // timeout ในหน่วยวินาที
            ]);
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