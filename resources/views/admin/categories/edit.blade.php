@extends('layouts.admin')

@section('title', 'แก้ไขหมวดหมู่ - Admin Dashboard')

@section('page-title', 'แก้ไขหมวดหมู่')

@section('content')
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">แก้ไขหมวดหมู่</h2>
            <p class="text-gray-600">แก้ไขข้อมูลหมวดหมู่ {{ $category->name }}</p>
        </div>
        
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อหมวดหมู่</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-folder text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Slug จะถูกสร้างอัตโนมัติจากชื่อหมวดหมู่</p>
                    
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (อ่านอย่างเดียว)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-link text-gray-400"></i>
                        </div>
                        <input type="text" id="slug" value="{{ $category->slug }}" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 bg-gray-100 rounded-lg shadow-sm" readonly>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Slug จะถูกสร้างอัตโนมัติเมื่อบันทึก</p>
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $category->description) }}</textarea>
                    
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">ลำดับการแสดงผล</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-sort-numeric-down text-gray-400"></i>
                        </div>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">ตัวเลขน้อยจะแสดงก่อน</p>
                    
                    @error('sort_order')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">เปิดใช้งาน</label>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <p>จำนวนคอร์สในหมวดหมู่นี้: <span class="font-medium">{{ $category->coursesCount() }}</span></p>
                    <p>คอร์สที่เปิดใช้งาน: <span class="font-medium">{{ $category->activeCoursesCount() }}</span></p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        ยกเลิก
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection