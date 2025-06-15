@extends('layouts.admin')

@section('title', 'แก้ไขหมวดหมู่ - Admin Dashboard')

@section('page-title', 'แก้ไขหมวดหมู่')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อหมวดหมู่</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    
                    @error('slug')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description', $category->description) }}</textarea>
                    
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">ไอคอน</label>
                    
                    @if($category->icon)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" class="h-16 w-16 object-cover rounded">
                            <p class="text-sm text-gray-500 mt-1">ไอคอนปัจจุบัน</p>
                        </div>
                    @endif
                    
                    <input type="file" name="icon" id="icon" accept="image/*" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-sm text-gray-500 mt-1">อัปโหลดเฉพาะเมื่อต้องการเปลี่ยนไอคอน</p>
                    
                    @error('icon')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">ลำดับการแสดงผล</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">ตัวเลขน้อยจะแสดงก่อน</p>
                    
                    @error('sort_order')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">เปิดใช้งาน</label>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <p>จำนวนคอร์สในหมวดหมู่นี้: <span class="font-medium">{{ $category->coursesCount() }}</span></p>
                    <p>คอร์สที่เปิดใช้งาน: <span class="font-medium">{{ $category->activeCoursesCount() }}</span></p>
                </div>
                
                <div class="flex items-center">
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">ยกเลิก</a>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </div>
        </form>
    </div>
@endsection