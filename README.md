training-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # จัดการ login/logout
│   │   │   ├── AdminController.php      # หน้า Admin Dashboard
│   │   │   ├── CourseController.php     # จัดการคอร์ส
│   │   │   ├── QuestionController.php   # จัดการคำถาม
│   │   │   └── UserController.php       # จัดการผู้ใช้
│   │   │
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php      # ตรวจสอบสิทธิ์ Admin
│   │   │   └── UserMiddleware.php       # ตรวจสอบสิทธิ์ User
│   │
│   ├── Models/
│   │   ├── User.php                     # โมเดลผู้ใช้
│   │   ├── Course.php                   # โมเดลคอร์ส
│   │   ├── Question.php                 # โมเดลคำถาม
│   │   ├── Answer.php                   # โมเดลตัวเลือกคำตอบ
│   │   ├── UserProgress.php             # โมเดลความคืบหน้าในการดูวิดีโอ
│   │   └── UserAnswer.php               # โมเดลคำตอบของผู้ใช้
│   │
│   └── Services/
│       └── VideoProgressService.php     # Service สำหรับจัดการความคืบหน้าวิดีโอ
│
├── config/                              # ไฟล์คอนฟิก
│
├── database/
│   └── migrations/                      # ไฟล์สร้างโครงสร้างฐานข้อมูล
│
├── public/
│   ├── css/                             # CSS สำหรับ custom style
│   ├── js/                              # JavaScript files
│   │   ├── video-player.js              # จัดการเล่นวิดีโอและการควบคุม
│   │   └── quiz-handler.js              # จัดการแสดงคำถามและรับคำตอบ
│   │
│   └── videos/                          # ที่เก็บไฟล์วิดีโอ
│
├── resources/
│   ├── views/
│   │   ├── auth/                        # หน้า login
│   │   │   ├── login.blade.php
│   │   │   └── admin-login.blade.php    # login สำหรับ admin
│   │   │
│   │   ├── admin/                       # หน้า admin dashboard
│   │   │   ├── dashboard.blade.php
│   │   │   ├── users/                   # จัดการผู้ใช้
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   │
│   │   │   ├── courses/                 # จัดการคอร์ส
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   │
│   │   │   └── questions/               # จัดการคำถาม
│   │   │       ├── index.blade.php
│   │   │       ├── create.blade.php
│   │   │       └── edit.blade.php
│   │   │
│   │   ├── courses/                     # หน้าเรียนคอร์ส
│   │   │   ├── index.blade.php          # Landing page แสดงคอร์สทั้งหมด
│   │   │   ├── show.blade.php           # หน้าเรียนวิดีโอพร้อมคำถาม
│   │   │   └── summary.blade.php        # หน้าสรุปผลคะแนน
│   │   │
│   │   ├── layouts/                     # Template layouts
│   │   │   ├── app.blade.php            # Layout หลัก
│   │   │   ├── admin.blade.php          # Layout สำหรับ admin
│   │   │   └── auth.blade.php           # Layout สำหรับหน้า login
│   │   │
│   │   └── components/                  # Reusable components
│   │       ├── video-player.blade.php
│   │       └── quiz-modal.blade.php
│
├── routes/
│   └── web.php                          # กำหนด routes ทั้งหมด
│
└── .env                                 # ตั้งค่า environment