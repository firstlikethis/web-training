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
                // ใช้ PHP-FFMpeg โดยไม่ต้องระบุพาธของ binary โดยตรง
                // PHP-FFMpeg จะพยายามค้นหา binary ให้อัตโนมัติ
                $config = [
                    'timeout'          => 3600,
                    'ffmpeg.threads'   => 12,
                ];
                
                return FFMpeg::create($config);
            } catch (\Exception $e) {
                Log::error('Failed to create FFMpeg instance: ' . $e->getMessage());
                
                // แทนที่จะ throw exception ให้สร้าง mock object ที่สามารถใช้งานได้ในระดับหนึ่ง
                return new class {
                    public function __call($method, $args) {
                        Log::warning("FFMpeg mock: Called $method but FFMpeg is not available");
                        return $this;
                    }
                };
            }
        });
        
        $this->app->singleton('ffprobe', function ($app) {
            try {
                $config = [
                    'timeout' => 60,
                ];
                
                return FFProbe::create($config);
            } catch (\Exception $e) {
                Log::error('Failed to create FFProbe instance: ' . $e->getMessage());
                
                // สร้าง mock object สำหรับ FFProbe
                return new class {
                    public function format($path) {
                        Log::warning("FFProbe mock: Called format() but FFProbe is not available");
                        return $this;
                    }
                    
                    public function get($property) {
                        // กรณีที่มีการเรียกใช้ get('duration')
                        if ($property === 'duration') {
                            return 0; // คืนค่า 0 วินาที
                        }
                        return null;
                    }
                };
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