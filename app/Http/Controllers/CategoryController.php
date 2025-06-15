<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * แสดงรายการหมวดหมู่ทั้งหมด
     */
    public function index()
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * แสดงหน้าสร้างหมวดหมู่ใหม่
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * บันทึกข้อมูลหมวดหมู่ใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|max:1024',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $category->description = $request->description;
        $category->is_active = $request->has('is_active');
        $category->sort_order = $request->sort_order ?? 0;
        
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('categories', 'public');
            $category->icon = $iconPath;
        }
        
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'สร้างหมวดหมู่ ' . $category->name . ' เรียบร้อยแล้ว');
    }

    /**
     * แสดงหน้าแก้ไขข้อมูลหมวดหมู่
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * อัปเดตข้อมูลหมวดหมู่
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string',
            'icon' => 'nullable|image|max:1024',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $category->name = $request->name;
        
        if ($request->slug) {
            $category->slug = Str::slug($request->slug);
        }
        
        $category->description = $request->description;
        $category->is_active = $request->has('is_active');
        $category->sort_order = $request->sort_order ?? 0;
        
        if ($request->hasFile('icon')) {
            // ลบไฟล์เก่า
            if ($category->icon) {
                Storage::disk('public')->delete($category->icon);
            }
            
            $iconPath = $request->file('icon')->store('categories', 'public');
            $category->icon = $iconPath;
        }
        
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'อัปเดตข้อมูลหมวดหมู่ ' . $category->name . ' เรียบร้อยแล้ว');
    }

    /**
     * ลบหมวดหมู่
     */
    public function destroy(Category $category)
    {
        // ตรวจสอบว่ามีคอร์สในหมวดหมู่นี้หรือไม่
        $coursesCount = $category->courses()->count();
        
        if ($coursesCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'ไม่สามารถลบหมวดหมู่นี้ได้ เนื่องจากมีคอร์สในหมวดหมู่นี้ ' . $coursesCount . ' คอร์ส');
        }
        
        // ลบไฟล์ icon
        if ($category->icon) {
            Storage::disk('public')->delete($category->icon);
        }
        
        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'ลบหมวดหมู่ ' . $categoryName . ' เรียบร้อยแล้ว');
    }
}