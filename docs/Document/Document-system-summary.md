## 🔗 **Routes ที่ต้องเพิ่ม**

### Backend Routes (ใน administrator group)
```php
Route::middleware(['auth', 'can:admin.access'])->prefix('administrator')->as('administrator.')->group(function () {
    // ... routes อื่นๆ ที่มีอยู่แล้ว
    
    // Document Management Routes
    Route::get('/documents/categories', Backend\Document\CategoriesList::class)
        ->name('documents.categories');
    Route::get('/documents/types', Backend\Document\TypesList::class)
        ->name('documents.types');
    Route::get('/documents', Backend\Document\DocumentsList::class)
        ->name('documents.index');
    Route::get('/documents/statistics', Backend\Document\Statistics::class)
        ->name('documents.statistics');
});
```

### Frontend Routes (Public)
```php
// เพิ่มใน public routes
Route::get('/documents', Frontend\Document\DocumentsList::class)->name('documents.index');
Route::get('/documents/{document}', Frontend\Document\DocumentView::class)->name('documents.show');
Route::get('/documents/search', Frontend\Document\DocumentSearch::class)->name('documents.search');
Route::get('/categories/{category}', Frontend\Categories\Show::class)->name('categories.show');
```

## 🎯 **Navigation Menu ที่แนะนำ**

### Admin Navigation
```php
'เอกสาร' => [
    'icon' => 'document',
    'permission' => 'documents.view',
    'children' => [
        'รายการเอกสาร' => 'administrator.documents.index',
        'หมวดหมู่' => 'administrator.documents.categories',
        'ประเภทเอกสาร' => 'administrator.documents.types',
        'สถิติ' => 'administrator.documents.statistics',
    ]
]
```

### Public Navigation  
```php
'เอกสาร' => route('documents.index')
```## Livewire Component Examples (Structure Only)

### Backend Components (Dashboard Layout)
```php
#[Layout('components.layouts.dashboard')]
class Dashboard extends Component            // หน้าแดชบอร์ด
class Documents\Index extends Component      // รายการเอกสาร
class Documents\Create extends Component     // สร้างเอกสาร
class Documents\Edit extends Component       // แก้ไขเอกสาร
class Categories\Index extends Component     // จัดการหมวดหมู่
class Users\Index extends Component          // จัดการผู้ใช้
class Statistics extends Component           // สถิติและรายงาน
```

### Frontend Components (Public Layout)
```php
#[Layout('components.layouts.app')]
class Home extends Component                 // หน้าแรก
class Documents\Index extends Component      // รายการเอกสารสาธารณะ
class Documents\Show extends Component       // รายละเอียดเอกสาร
class Documents\Search extends Component     // ค้นหาเอกสาร
class Categories\Show extends Component      // เอกสารในหมวดหมู่
```

### Auth Components (Guest Layout)
```php
// ไม่ต้องสร้าง - ใช้ระบบ Auth ที่มีอยู่แล้ว
```## Configuration

### Livewire 3.6
```php
// config/livewire.php
return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'layouts.app',
    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => ['file', 'max:51200'], // 50MB
        'directory' => 'livewire-tmp',
        'middleware' => 'throttle:60,1',
        'preview_mimes' => ['png', 'gif', 'bmp', 'svg', 'wav', 'mp4', 'mov', 'avi', 'wmv', 'mp3', 'm4a', 'jpg', 'jpeg', 'mpga', 'webp', 'wma'],
        'max_upload_time' => 5,
    ],
];
```

### Tailwind 4.1
```js
// tailwind.config.js
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: {# ระบบจัดการเกสาร (Document Management System)

## ภาพรวมระบบ

ระบบจัดการเกสารเป็น**ส่วนเสริม**ในเว็บไซต์ที่มีอยู่แล้ว สำหรับให้บริการ**บุคคลทั่วไป**และ**เจ้าหน้าที่**ในการดาวน์โหลดเอกสาร/แบบฟอร์มต่างๆ

### กลุ่มผู้ใช้ (ใช้ระบบ Auth ที่มีอยู่)
- **Staff** - เจ้าหน้าที่ (มีสิทธิ์จัดการเอกสาร)
- **Public** - บุคคลทั่วไป (ดาวน์โหลดเอกสาร)
- **Anonymous** - ผู้เยี่ยมชม (ดูเอกสารสาธารณะ)

---

## เทคโนโลยีที่ใช้

- **Framework**: Laravel 12
- **Frontend**: Livewire 3.6 (Component-based)
- **Styling**: Tailwind CSS 4.1
- **Authentication**: Laravel Breeze with Livewire
- **File Management**: Spatie Media Library
- **Permission**: Spatie Laravel Permission
- **Database**: MySQL 8.0+
- **File Upload**: Livewire File Upload + Filepond (optional)

---

## โครงสร้างฐานข้อมูล

### ตารางหลัก

#### 1. **departments** - แผนก/หน่วยงาน
```sql
- id, name, slug, description
- is_active, timestamps
```

#### 2. **users** - ผู้ใช้งาน (เพิ่มเติม)
```sql
- user_type ENUM('staff', 'public')
- department_id (FK to departments)
```

#### 3. **document_categories** - หมวดหมู่เอกสาร
```sql
- id, name, slug, description
- parent_id (self-reference), sort_order
- is_active, timestamps
```

#### 4. **document_types** - ประเภทเอกสาร
```sql
- id, name, slug, description
- allowed_extensions JSON
- is_active, timestamps
```

#### 5. **documents** - เอกสารหลัก
```sql
- id, document_number, title, description
- document_category_id, document_type_id
- created_by, department_id
- status ENUM('draft', 'published', 'archived')
- access_level ENUM('public', 'registered')
- document_date, published_at
- download_count, view_count
- version, parent_document_id
- tags JSON, keywords, reference_number
- is_featured, is_new
- file_size, mime_type, original_filename
- timestamps, soft_deletes
```

### ตารางสนับสนุน

#### 6. **document_histories** - ประวัติการเปลี่ยนแปลง
```sql
- document_id, action, old_values JSON, new_values JSON
- performed_by, created_at
```

#### 7. **document_downloads** - สถิติการดาวน์โหลด
```sql
- document_id, user_id, user_type
- ip_address, user_agent, referer
- downloaded_at
```

#### 8. **public_documents** - View
```sql
เอกสารที่เผยแพร่และเป็นสาธารณะ
```

---

## Models และ Relationships

### Document.php (หลัก)
```php
// Relationships
belongsTo: category, type, creator, department, parentDocument
hasMany: versions, histories, downloads

// Scopes
published(), public(), featured(), new(), search()

// Methods
publish(), archive(), incrementDownloadCount()
canBeAccessedBy(), generateDocumentNumber()

// Media Library Integration
registerMediaCollections(), registerMediaConversions()
```

### User.php
```php
// Relationships
belongsTo: department
hasMany: documents, documentHistories, documentDownloads

// Accessors
is_staff, is_public, full_name

// Methods
canAccessDocument(), canManageDocument()
updateLastLogin()
```

### DocumentCategory.php
```php
// Hierarchical Structure
belongsTo: parent
hasMany: children, documents

// Methods
getAllDescendants(), getDocumentCount()
getBreadcrumbAttribute()
```

---

## ระบบสิทธิ์ (Spatie Permission)

### Permissions
```php
// Document Management
'documents.create'     // สร้างเอกสาร
'documents.edit'       // แก้ไขเอกสาร
'documents.delete'     // ลบเอกสาร
'documents.publish'    // เผยแพร่เอกสาร
'documents.view-stats' // ดูสถิติ

// Administrative
'admin.access'         // เข้าถึงหน้า Admin
'categories.manage'    // จัดการหมวดหมู่
'departments.manage'   // จัดการแผนก
'users.manage'         // จัดการผู้ใช้
'system.settings'      // การตั้งค่าระบบ
```

### Roles
```php
'Super Admin'  // ทุกสิทธิ์
'Admin'        // จัดการระบบ
'Editor'       // จัดการเอกสาร
'Staff'        // เจ้าหน้าที่ทั่วไป
'Public User'  // บุคคลทั่วไป
```

---

## การทำงานของระบบ

### การเข้าถึงเอกสาร

#### Public Documents
- ทุกคนดาวน์โหลดได้ (ไม่ต้อง login)
- แสดงในหน้าแรก

#### Registered Documents
- ต้อง login เท่านั้น
- ทั้ง staff และ public user

### การแสดงผลตามแผนก
```php
// เจ้าหน้าที่เห็นเอกสารในแผนกตัวเอง
if ($user->user_type === 'staff' && $user->department_id) {
    $documents = Document::where('department_id', $user->department_id);
}
```

---

## 📁 **ไฟล์ที่สร้างแล้ว**

### 🗄️ **Database**
```
database/migrations/
├── 2024_01_01_000001_create_departments_table.php
├── 2024_01_01_000002_add_department_to_users_table.php
├── 2024_01_01_000003_create_document_categories_table.php
├── 2024_01_01_000004_create_document_types_table.php
├── 2024_01_01_000005_create_documents_table.php
├── 2024_01_01_000006_create_document_histories_table.php
├── 2024_01_01_000007_create_document_downloads_table.php
├── 2024_01_01_000008_create_public_documents_view.php
├── 2024_01_01_000009_create_additional_indexes.php
└── 2024_01_01_000010_seed_initial_data.php

database/seeders/
└── DocumentPermissionsSeeder.php
```

### 🏗️ **Models (ออกแบบแล้ว)**
```
app/Models/
├── Department.php
├── DocumentCategory.php
├── DocumentType.php
├── Document.php
├── DocumentHistory.php
└── DocumentDownload.php
```

### ⚡ **Livewire Components**
```
app/Livewire/Backend/Document/
├── CategoriesList.php ✅
└── TypesList.php ✅

resources/views/livewire/backend/document/
├── categories-list.blade.php ✅
└── types-list.blade.php ✅
```

---

## ฟีเจอร์ที่พร้อมใช้งาน

### ✅ การจัดการเอกสาร
- อัพโหลด/ดาวน์โหลดไฟล์
- จัดหมวดหมู่และประเภท
- ระบบเวอร์ชัน
- สถานะการเผยแพร่

### ✅ ระบบสิทธิ์
- แยกตาม Role & Permission
- การเข้าถึงตามแผนก
- Guest/Registered access

### ✅ การติดตาม
- ประวัติการเปลี่ยนแปลง
- สถิติการดาวน์โหลด
- การนับยอดเข้าชม

### ✅ การค้นหา
- Full-text search
- Filter ตามหมวดหมู่/ประเภท
- แท็กและคำค้นหา

---

## สิ่งที่ต้องพัฒนาต่อ

### Backend (Admin/Staff)
- [ ] Livewire Components with Dashboard Layout
- [ ] Form Request Validation
- [ ] Policies (DocumentPolicy, etc.)
- [ ] Jobs (File processing, notifications)
- [ ] Observers (auto-logging)

### Frontend (Public)
- [ ] Livewire Components with Public Layout
- [ ] Guest Document Access
- [ ] Search & Filter Components
- [ ] Responsive Design

### Livewire Components Structure
```
app/Livewire/
├── Backend/                     // Admin/Staff (#[Layout('components.layouts.dashboard')])
│   ├── Documents/
│   │   ├── Index.php           // รายการเอกสาร
│   │   ├── Create.php          // สร้างเอกสาร
│   │   ├── Edit.php            // แก้ไขเอกสาร
│   │   └── Show.php            // รายละเอียดเอกสาร
│   ├── Categories/
│   │   ├── Index.php           // จัดการหมวดหมู่
│   │   └── Form.php            // ฟอร์มหมวดหมู่
│   └── Statistics.php          // สถิติเอกสาร
├── Frontend/                   // Public (#[Layout('components.layouts.app')])
│   ├── Documents/
│   │   ├── Index.php           // รายการเอกสารสาธารณะ
│   │   ├── Show.php            // รายละเอียดเอกสาร
│   │   └── Search.php          // ค้นหาเอกสาร
│   └── Categories/
│       └── Show.php            // เอกสารในหมวดหมู่
└── Components/                 // Shared Components
    ├── FileUpload.php          // อัพโหลดไฟล์
    ├── TagInput.php            // ป้อนแท็ก
    └── Modal.php               // Modal Dialog
```

### Layout ที่ใช้ (ใช้ Layout ที่มีอยู่แล้ว)
- **Dashboard Layout**: `components.layouts.dashboard` (สำหรับ Backend)
- **App Layout**: `components.layouts.app` (สำหรับ Frontend)

### Tailwind 4.1 Features
- [ ] Container Queries
- [ ] Dynamic Variants
- [ ] Enhanced Color Palette
- [ ] Improved Dark Mode Support
- [ ] New Animation Utilities

### เพิ่มเติม
- [ ] Email Notifications
- [ ] File Preview (PDF, images)
- [ ] Bulk Operations
- [ ] Export/Import
- [ ] Audit Logs
- [ ] System Settings

---

## Livewire Component Examples

### Public Components

#### DocumentList.php
```php
class DocumentList extends Component
{
    public $search = '';
    public $category = '';
    public $type = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    
    public function render()
    {
        $documents = Document::published()
            ->public()
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->category, fn($q) => $q->byCategory($this->category))
            ->when($this->type, fn($q) => $q->byType($this->type))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);
            
        return view('livewire.public.document-list', compact('documents'));
    }
}
```

#### DocumentViewer.php
```php
class DocumentViewer extends Component
{
    public Document $document;
    public $showDownloadModal = false;
    
    public function mount(Document $document)
    {
        $this->document = $document;
        $this->document->incrementViewCount();
    }
    
    public function download()
    {
        if (!$this->document->canBeAccessedBy(auth()->user())) {
            return $this->redirect('/login');
        }
        
        DocumentDownload::create([
            'document_id' => $this->document->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        $this->document->incrementDownloadCount();
        
        return response()->download($this->document->getFirstMediaPath('documents'));
    }
}
```

### Admin Components

#### DocumentManager.php
```php
class DocumentManager extends Component
{
    public $search = '';
    public $status = '';
    public $department = '';
    public $showDeleteModal = false;
    public $documentToDelete = null;
    
    protected $listeners = ['documentCreated' => '$refresh'];
    
    public function deleteDocument($documentId)
    {
        $this->documentToDelete = $documentId;
        $this->showDeleteModal = true;
    }
    
    public function confirmDelete()
    {
        $document = Document::find($this->documentToDelete);
        $document->delete();
        
        $this->showDeleteModal = false;
        $this->documentToDelete = null;
        
        session()->flash('message', 'เอกสารถูกลบเรียบร้อยแล้ว');
    }
}
```

#### DocumentForm.php
```php
class DocumentForm extends Component
{
    public Document $document;
    public $isEditing = false;
    
    public $title = '';
    public $description = '';
    public $document_category_id = '';
    public $document_type_id = '';
    public $access_level = 'public';
    public $document_date;
    public $tags = [];
    public $file;
    
    protected $rules = [
        'title' => 'required|max:500',
        'description' => 'nullable',
        'document_category_id' => 'required|exists:document_categories,id',
        'document_type_id' => 'required|exists:document_types,id',
        'access_level' => 'required|in:public,registered',
        'document_date' => 'required|date',
        'file' => 'required_without:isEditing|file|max:51200', // 50MB
    ];
    
    public function save()
    {
        $this->validate();
        
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'document_category_id' => $this->document_category_id,
            'document_type_id' => $this->document_type_id,
            'access_level' => $this->access_level,
            'document_date' => $this->document_date,
            'tags' => $this->tags,
            'created_by' => auth()->id(),
            'department_id' => auth()->user()->department_id,
        ];
        
        if ($this->isEditing) {
            $this->document->update($data);
        } else {
            $document = Document::create($data);
            
            if ($this->file) {
                $document->addMediaFromUpload($this->file)
                    ->toMediaCollection('documents');
            }
        }
        
        $this->emit('documentCreated');
        session()->flash('message', 'บันทึกเอกสารเรียบร้อยแล้ว');
    }
}
```

## Livewire Routes

### Public Routes
```php
// routes/web.php
Route::get('/', App\Livewire\Public\DocumentList::class)->name('home');
Route::get('/documents', App\Livewire\Public\DocumentList::class)->name('documents.index');
Route::get('/documents/{document}', App\Livewire\Public\DocumentViewer::class)->name('documents.show');
Route::get('/categories/{category}', App\Livewire\Public\CategoryBrowser::class)->name('categories.show');
Route::get('/search', App\Livewire\Public\DocumentSearch::class)->name('search');
```

### Admin Routes
```php
Route::middleware(['auth', 'can:admin.access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/documents', App\Livewire\Admin\DocumentManager::class)->name('documents');
    Route::get('/documents/create', App\Livewire\Admin\DocumentForm::class)->name('documents.create');
    Route::get('/documents/{document}/edit', App\Livewire\Admin\DocumentForm::class)->name('documents.edit');
    Route::get('/categories', App\Livewire\Admin\CategoryManager::class)->name('categories');
    Route::get('/users', App\Livewire\Admin\UserManager::class)->name('users');
    Route::get('/statistics', App\Livewire\Admin\Statistics::class)->name('statistics');
});
```

### API Endpoints (สำหรับ Mobile App หรือ External Integration)

#### Public API
```php
GET    /api/documents              // รายการเอกสารสาธารณะ
GET    /api/documents/{id}         // รายละเอียดเอกสาร
GET    /api/documents/{id}/download // ดาวน์โหลดเอกสาร
GET    /api/categories             // รายการหมวดหมู่
GET    /api/search                 // ค้นหาเอกสาร
```

#### Authenticated API
```php
GET    /api/user/documents         // เอกสารที่เข้าถึงได้
POST   /api/user/downloads         // บันทึกการดาวน์โหลด
```

#### Admin API
```php
GET    /api/admin/documents        // จัดการเอกสาร
POST   /api/admin/documents        // สร้างเอกสาร
PUT    /api/admin/documents/{id}   // แก้ไขเอกสาร
DELETE /api/admin/documents/{id}   // ลบเอกสาร
GET    /api/admin/statistics       // สถิติการใช้งาน
```

---

## Configuration

### Media Library
```php
// config/media-library.php
'disk_name' => 'documents',
'max_file_size' => 50 * 1024 * 1024, // 50MB
'allowed_mime_types' => [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]
```

### Permissions
```php
// DatabaseSeeder.php
$permissions = [
    'documents.create', 'documents.edit', 'documents.delete',
    'documents.publish', 'admin.access', 'categories.manage'
];

$roles = [
    'Super Admin' => $permissions,
    'Admin' => ['admin.access', 'categories.manage'],
    'Editor' => ['documents.create', 'documents.edit']
];
```

---

## การติดตั้งและใช้งาน

### 1. Install Dependencies (เพิ่มเติมในโปรเจคที่มีอยู่)
```bash
composer require spatie/laravel-medialibrary
composer require spatie/laravel-permission
```

### 2. Run Migrations (เพิ่มใหม่)
```bash
php artisan migrate
```

### 3. Publish Config (ถ้ายังไม่เคย)
```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

## 📋 **สรุปงานที่เสร็จแล้ว**

### ✅ **Phase 1: Foundation (เสร็จสมบูรณ์)**
- Database Schema & Migrations
- Permissions & Seeders  
- Models Design
- DocumentCategory CRUD
- DocumentType CRUD

### 🎯 **Phase 2: Core Features (ต่อไป)**
- Document Management (สร้าง/แก้ไข/ลบเอกสาร)
- File Upload Integration
- Frontend Public Pages

### 🚀 **Phase 3: Advanced (ภายหลัง)**
- Statistics Dashboard
- Search Functionality
- Download Tracking
- User Experience Improvements

## 💡 **หมายเหตุสำคัญ**

1. **ทั้ง CategoriesList และ TypesList** ใช้แนวทาง **All-in-One CRUD**
2. **Extensions Management** - TypesList มีระบบเลือกนามสกุลไฟล์แบบ Visual
3. **Responsive Design** - ใช้ Tailwind CSS 4.1 ทั้งหมด
4. **Permission System** - ตรวจสอบสิทธิ์ทุก action
5. **Real-time Features** - Search, Filter, Loading states

## 🎬 **พร้อมสำหรับขั้นตอนต่อไป!**
ระบบพื้นฐานพร้อมแล้ว สามารถเริ่มพัฒนา Document Management หรือ Frontend ได้เลย! 🚀
