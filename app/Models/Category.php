<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    // เมื่อสร้าง slug อัตโนมัติจากชื่อ
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            // อัพเดท slug เมื่อมีการเปลี่ยนชื่อ
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
    
    // ความสัมพันธ์กับ Course
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    
    // ฟังก์ชันสำหรับดึงเฉพาะหมวดหมู่ที่เปิดใช้งาน
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // นับจำนวนคอร์สในหมวดหมู่
    public function coursesCount()
    {
        return $this->courses()->count();
    }
    
    // นับจำนวนคอร์สที่เปิดใช้งานในหมวดหมู่
    public function activeCoursesCount()
    {
        return $this->courses()->where('is_active', true)->where('status', 'published')->count();
    }
}