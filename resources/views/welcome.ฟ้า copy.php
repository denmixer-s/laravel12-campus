<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>มทร.อีสาน-วิทยาเขตสกลนคร Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700;800&display=swap');
        body { font-family: 'Kanit', sans-serif; }
        .dropdown:hover .dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-menu { opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease; }
        .mega-menu { width: auto; left: 0; transform: none; min-width: 600px; max-width: 900px; }
        .mega-menu-2col { min-width: 500px; max-width: 700px; }
        .mega-menu-3col { min-width: 650px; max-width: 850px; }
        .mega-menu-4col { min-width: 800px; max-width: 1000px; }
        .mobile-menu { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        .tab-skewed {
            transform: skewX(-45deg);
            margin-right: 2px;
        }
        .tab-skewed span {
            transform: skewX(45deg);
            display: block;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Bar -->
    <div class="bg-blue-900 text-white py-2 px-4 text-sm">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span><i class="fas fa-phone mr-2"></i>042-761-000</span>
                <span><i class="fas fa-envelope mr-2"></i>info@skc.rmuti.ac.th</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="#" class="hover:text-blue-200"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-blue-200"><i class="fab fa-youtube"></i></a>
                <a href="#" class="hover:text-blue-200"><i class="fab fa-line"></i></a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-4">
                    <img src="https://via.placeholder.com/60x60/1e40af/ffffff?text=RMUTI" alt="RMUTI Logo" class="h-16 w-16">
                    <div>
                        <h1 class="text-xl font-bold text-blue-900">มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน</h1>
                        <p class="text-sm text-gray-600">วิทยาเขตสกลนคร</p>
                    </div>
                </div>

                <!-- Right Side - Language & Mobile Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative dropdown">
                        <button class="flex items-center space-x-2 px-3 py-2 rounded-md hover:bg-gray-100 transition-colors">
                            <i class="fas fa-globe text-blue-900"></i>
                            <span class="text-sm font-medium">ไทย</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-menu absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg border">
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                                <img src="https://via.placeholder.com/20x15/ff0000/ffffff?text=TH" alt="Thai" class="mr-2 rounded">
                                ไทย
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                                <img src="https://via.placeholder.com/20x15/0000ff/ffffff?text=EN" alt="English" class="mr-2 rounded">
                                English
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                        <i class="fas fa-bars text-xl text-blue-900"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="bg-blue-900 text-white">
            <div class="container mx-auto px-4">
                <!-- Desktop Menu -->
                <ul class="hidden lg:flex items-center">
                    <!-- Home -->
                    <li><a href="#" class="block px-6 py-4 hover:bg-blue-800 transition-colors">หน้าแรก</a></li>

                    <!-- About Mega Menu -->
                    <li class="relative dropdown">
                        <a href="#" class="flex items-center px-6 py-4 hover:bg-blue-800 transition-colors">
                            เกี่ยวกับเรา <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </a>
                        <div class="dropdown-menu absolute top-full mega-menu mega-menu-4col bg-white text-gray-800 shadow-xl border-t-4 border-blue-600">
                            <div class="px-6 py-6">
                                <div class="grid grid-cols-4 gap-8">
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4">ประวัติความเป็นมา</h3>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-sm hover:text-blue-600">ประวัติมหาวิทยาลัย</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600">วิสัยทัศน์ พันธกิจ</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600">ปรัชญา อุดมการณ์</a></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4">ผู้บริหาร</h3>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-sm hover:text-blue-600">อธิการบดี</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600">รองอธิการบดี</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600">ผู้ช่วยอธิการบดี</a></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4">โครงสร้าง</h3>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-sm hover:text-blue-600">โครงสร้างองค์กร</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600">คณะและสำนักงาน</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600">ศูนย์และหน่วยงาน</a></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4">ข้อมูลทั่วไป</h3>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-sm hover:text-blue-600">ที่ตั้งและการเดินทาง</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600">ติดต่อเรา</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600">แผนที่มหาวิทยาลัย</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Academics Mega Menu (3 columns) -->
                    <li class="relative dropdown">
                        <a href="#" class="flex items-center px-6 py-4 hover:bg-blue-800 transition-colors">
                            การศึกษา <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </a>
                        <div class="dropdown-menu absolute top-full mega-menu mega-menu-3col bg-white text-gray-800 shadow-xl border-t-4 border-blue-600">
                            <div class="px-6 py-6">
                                <div class="grid grid-cols-3 gap-8">
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4 flex items-center">
                                            <i class="fas fa-graduation-cap mr-2"></i>หลักสูตรการศึกษา
                                        </h3>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ปริญญาตรี</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ปริญญาโท</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ปริญญาเอก</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>หลักสูตรนานาชาติ</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>หลักสูตรระยะสั้น</a></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4 flex items-center">
                                            <i class="fas fa-building mr-2"></i>คณะและสาขาวิชา
                                        </h3>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>คณะวิศวกรรมศาสตร์</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>คณะเทคโนโลยีการเกษตร</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>คณะบริหารธุรกิจ</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>คณะศิลปศาสตร์</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>วิทยาลัยแพทยศาสตร์</a></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4 flex items-center">
                                            <i class="fas fa-calendar-alt mr-2"></i>บริการการศึกษา
                                        </h3>
                                        <ul class="space-y-2">
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ปฏิทินการศึกษา</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ระเบียบการศึกษา</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>การลงทะเบียน</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ระบบสารสนเทศ</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ใบปริญญา</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Research Mega Menu (2 columns) -->
                    <li class="relative dropdown">
                        <a href="#" class="flex items-center px-6 py-4 hover:bg-blue-800 transition-colors">
                            วิจัย <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </a>
                        <div class="dropdown-menu absolute top-full mega-menu mega-menu-2col bg-white text-gray-800 shadow-xl border-t-4 border-blue-600">
                            <div class="px-6 py-6">
                                <div class="grid grid-cols-2 gap-8">
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4 flex items-center">
                                            <i class="fas fa-flask mr-2"></i>งานวิจัยและนวัตกรรม
                                        </h3>
                                        <ul class="space-y-3">
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>นโยบายการวิจัย</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ยุทธศาสตร์การวิจัย</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ผลงานวิจัยเด่น</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ฐานข้อมูลงานวิจัย</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>จรรยาบรรณการวิจัย</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>สิทธิบัตรและนวัตกรรม</a></li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-blue-900 mb-4 flex items-center">
                                            <i class="fas fa-users mr-2"></i>ศูนย์และหน่วยงานวิจัย
                                        </h3>
                                        <ul class="space-y-3">
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ศูนย์เครื่องมือวิทยาศาสตร์</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ศูนย์วิจัยเทคโนโลยี</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ศูนย์บ่มเพาะวิสาหกิจ</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>งานทรัพย์สินทางปัญญา</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>ห้องปฏิบัติการวิจัย</a></li>
                                            <li><a href="#" class="text-sm hover:text-blue-600 flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>เครือข่ายความร่วมมือ</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Student Services -->
                    <li class="relative dropdown">
                        <a href="#" class="flex items-center px-6 py-4 hover:bg-blue-800 transition-colors">
                            กิจการนักศึกษา <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </a>
                        <div class="dropdown-menu absolute top-full left-0 w-64 bg-white text-gray-800 shadow-xl">
                            <ul class="py-2">
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">ชมรมและองค์กร</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">ทุนการศึกษา</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">กิจกรรมนักศึกษา</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">บริการนักศึกษา</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- News -->
                    <li><a href="#" class="block px-6 py-4 hover:bg-blue-800 transition-colors">ข่าวสาร</a></li>

                    <!-- Contact -->
                    <li><a href="#" class="block px-6 py-4 hover:bg-blue-800 transition-colors">ติดต่อเรา</a></li>
                </ul>

                <!-- Mobile Menu -->
                <div id="mobile-menu" class="mobile-menu lg:hidden bg-blue-800">
                    <ul class="py-4">
                        <li><a href="#" class="block px-6 py-3 border-b border-blue-700 hover:bg-blue-700">หน้าแรก</a></li>
                        <li>
                            <button class="w-full text-left px-6 py-3 border-b border-blue-700 hover:bg-blue-700 flex items-center justify-between" onclick="toggleMobileSubmenu('about')">
                                เกี่ยวกับเรา <i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="about-submenu" class="hidden bg-blue-700">
                                <a href="#" class="block px-10 py-2 hover:bg-blue-600">ประวัติมหาวิทยาลัย</a>
                                <a href="#" class="block px-10 py-2 hover:bg-blue-600">ผู้บริหาร</a>
                                <a href="#" class="block px-10 py-2 hover:bg-blue-600">โครงสร้างองค์กร</a>
                            </div>
                        </li>
                        <li>
                            <button class="w-full text-left px-6 py-3 border-b border-blue-700 hover:bg-blue-700 flex items-center justify-between" onclick="toggleMobileSubmenu('academics')">
                                การศึกษา <i class="fas fa-chevron-down"></i>
                            </button>
                            <div id="academics-submenu" class="hidden bg-blue-700">
                                <a href="#" class="block px-10 py-2 hover:bg-blue-600">หลักสูตรที่เปิดสอน</a>
                                <a href="#" class="block px-10 py-2 hover:bg-blue-600">คณะและภาควิชา</a>
                                <a href="#" class="block px-10 py-2 hover:bg-blue-600">ปฏิทินการศึกษา</a>
                            </div>
                        </li>
                        <li><a href="#" class="block px-6 py-3 border-b border-blue-700 hover:bg-blue-700">วิจัย</a></li>
                        <li><a href="#" class="block px-6 py-3 border-b border-blue-700 hover:bg-blue-700">ข่าวสาร</a></li>
                        <li><a href="#" class="block px-6 py-3 hover:bg-blue-700">ติดต่อเรา</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-r from-blue-900 to-blue-700 text-white py-20">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-4xl md:text-6xl font-bold mb-6">มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน</h2>
                <p class="text-xl md:text-2xl mb-8">วิทยาเขตสกลนคร</p>
                <button class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    เรียนรู้เพิ่มเติม
                </button>
            </div>
        </section>

        <!-- News and Gallery Section -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid lg:grid-cols-10 gap-8">
                    <!-- News Section - 70% -->
                    <div class="lg:col-span-7 bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-blue-900 border-b-3 border-orange-400 pb-2">ข่าวประชาสัมพันธ์</h3>
                        </div>

                        <!-- Tab Navigation -->
                        <div class="flex border-b border-gray-200 mb-6">
                            <button class="tab-skewed px-6 py-2 text-orange-600 border-b-2 border-orange-600 font-medium bg-orange-50">
                                <span>ประชาสัมพันธ์</span>
                            </button>
                            <button class="tab-skewed px-6 py-2 text-gray-600 hover:text-orange-600 font-medium hover:bg-gray-50">
                                <span>บริการนักศึกษา</span>
                            </button>
                            <button class="tab-skewed px-6 py-2 text-gray-600 hover:text-orange-600 font-medium hover:bg-gray-50">
                                <span>ข่าวรับสมัครงาน</span>
                            </button>
                            <button class="tab-skewed px-6 py-2 bg-orange-400 text-white font-medium">
                                <span>จัดซื้อจัดจ้าง/ราคากลาง</span>
                            </button>
                        </div>

                        <!-- News List -->
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศผู้มีสิทธิการสอบรากข่าวเจ้าพนักงานปฏิบัติงานการเรียนรวมและการอเนกประสงค์ ตำแหน่งไคโนน อำเภอไคโนน จังหวัดสกลนคร จำ...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศผู้มีสิทธิการสอบรากข่าวเจ้าพนักงานปฏิบัติงานการศึกษาทั่วไป ตำแหน่งไคโนน อำเภอไคโนน จังหวัดสกลนคร จำนวน 1 รายการ</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศ เลที่เก่าประการรายชื่อผู้ขันการสอบรากา รายการประปฏิบัติงานการศึกษาทั่วไป ตำแหน่งไคโนน อำเภอไคโนน จังหวัดสกลนคร จำ...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศ เลที่เก่าประการรายชื่อผู้ขันการสอบรากา รายการประปฏิบัติงานการเรียนรวมและการอเนกประสงค์ ตำแหน่งไคโนน อำเภอไคโนน...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศผู้มีสิทธิการสอบรากา ประกวดราคาจ้างเหมารัวปรับปรุงการเรียนรวมและการอเนกประสงค์ เลขที่ 21/2568</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศผู้มีสิทธิการสอบราคาประกวดราคาจ้างเหมารัวปรับปรุงการศึกษาทั่วไป ต.ไคโนน อ.ไคโนน จ.สกลนคร จำนวน 1 รายการ ด้ว...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศประกวดราคาจ้างเหมารัวปรับปรุงการศึกษาทั่วไป ตำแหน่งไคโนน อำเภอไคโนน จังหวัดสกลนคร จำนวน 1 รายการ ด้วยวิธีประกา...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศ ประกวดราคาจ้างเหมารัวปรับปรุงการเรียนรวมและการอเนกประสงค์ ตำแหน่งไคโนน อำเภอไคโนน จังหวัดสกลนคร จำนวน...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">(ร่าง)ประกาศประกวดราคาจ้างปรับปรุงการศึกษาทั่วไป ตำแหน่งไคโนน อำเภอไคโนน จังหวัดสกลนคร จำนวน 1 รายการ ด้วยวิธีประกา...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศ เลขที่เก่าประการรายชื่อผู้ขัน ประจำปีงปประนาม พ.ศ.2568 รายการประปฏิบัติงานการศึกษาทั่วไป ตำแหน่งไคโนน อำเภอไคโนน จั...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศ เลขแพทย์แผนการจัดซื้อจัดจ้าง ประจำปีงปประนาม พ.ศ.2568 รายการประปฏิบัติงานการเรียนรวมและการอเนกประสงค์ ตำแหน่งไคว...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศผู้มีประกวดราคากรรกรรษิทรียาการ ยุคกรรกรรกเสริมราวกลียวทำผู้เรียนสำหรับการเป็นผู้ประกอบการในอาหาร จำนวน...</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3 py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-file-alt text-blue-600 mt-1 text-sm"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-800 leading-relaxed hover:text-blue-600 cursor-pointer">ประกาศ เชิญชวนแวทยพแทนการจัดซื้อจัดจ้าง ประจำปีงปประนาม พ.ร.2568</p>
                                </div>
                            </div>
                        </div>

                        <!-- View More Button -->
                        <div class="text-center mt-6">
                            <button class="bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-full font-medium transition-colors duration-300">
                                ★ อ่านข่าวอื่นนอก
                            </button>
                        </div>
                    </div>

                    <!-- Gallery Section - 30% -->
                    <div class="lg:col-span-3 bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-blue-900 border-b-3 border-orange-400 pb-2">รายงานประจำเดือน</h3>
                        </div>

                        <!-- Gallery Grid -->
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Gallery Item 1 -->
                            <div class="relative group cursor-pointer">
                                <img src="https://via.placeholder.com/200x150/3b82f6/ffffff?text=ขุดหน้าช่างประจำเดือนพฤษภาคม+2568" alt="รายงานประจำเดือน พฤษภาคม 2568" class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg flex items-end p-3">
                                    <div>
                                        <h4 class="text-white font-medium text-sm">ขุดหน้าช่างประจำเดือน</h4>
                                        <p class="text-orange-300 text-sm font-medium">พฤษภาคม 2568</p>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-300"></div>
                            </div>

                            <!-- Gallery Item 2 -->
                            <div class="relative group cursor-pointer">
                                <img src="https://via.placeholder.com/200x150/3b82f6/ffffff?text=ขุดหน้าช่างประจำเดือนเมษายน+2568" alt="รายงานประจำเดือน เมษายน 2568" class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg flex items-end p-3">
                                    <div>
                                        <h4 class="text-white font-medium text-sm">ขุดหน้าช่างประจำเดือน</h4>
                                        <p class="text-orange-300 text-sm font-medium">เมษายน 2568</p>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-300"></div>
                            </div>

                            <!-- Gallery Item 3 -->
                            <div class="relative group cursor-pointer">
                                <img src="https://via.placeholder.com/200x150/3b82f6/ffffff?text=ขุดหน้าช่างประจำเดือนกุมภาพันธ์+256.." alt="รายงานประจำเดือน กุมภาพันธ์ 2568" class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg flex items-end p-3">
                                    <div>
                                        <h4 class="text-white font-medium text-sm">ขุดหน้าช่างประจำเดือน</h4>
                                        <p class="text-orange-300 text-sm font-medium">กุมภาพันธ์ 256..</p>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-300"></div>
                            </div>

                            <!-- Gallery Item 4 -->
                            <div class="relative group cursor-pointer">
                                <img src="https://via.placeholder.com/200x150/3b82f6/ffffff?text=ขุดหน้าช่างประจำเดือนมกราคม+2568" alt="รายงานประจำเดือน มกราคม 2568" class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg flex items-end p-3">
                                    <div>
                                        <h4 class="text-white font-medium text-sm">ขุดหน้าช่างประจำเดือน</h4>
                                        <p class="text-orange-300 text-sm font-medium">มกราคม 2568</p>
                                    </div>
                                </div>
                                <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-300"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-blue-900 mb-4">บทความและบล็อก</h3>
                    <p class="text-gray-600 text-lg">อัปเดตข่าวสาร บทความวิชาการ และเรื่องราวน่าสนใจจากมหาวิทยาลัย</p>
                </div>

                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8">
                    <!-- Featured Blog Post -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                        <div class="relative">
                            <img src="https://via.placeholder.com/600x300/1e40af/ffffff?text=Featured+Blog" alt="Featured Blog" class="w-full h-64 object-cover">
                            <div class="absolute top-4 left-4">
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">Featured</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium mr-3">วิจัย</span>
                                <span class="text-gray-500 text-sm">21 มิถุนายน 2568</span>
                            </div>
                            <h4 class="font-bold text-xl mb-3 leading-tight">นวัตกรรมเทคโนโลยีเกษตรสมัยใหม่: แนวทางการพัฒนาที่ยั่งยืน</h4>
                            <p class="text-gray-600 mb-4 leading-relaxed">การวิจัยและพัฒนาเทคโนโลยีการเกษตรแบบสมัยใหม่ที่มุ่งเน้นความยั่งยืนและเป็นมิตรกับสิ่งแวดล้อม พร้อมทั้งเพิ่มผลผลิตและลดต้นทุนการผลิต...</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="https://via.placeholder.com/32x32/1e40af/ffffff?text=A" alt="Author" class="w-8 h-8 rounded-full mr-3">
                                    <span class="text-sm text-gray-700 font-medium">ดร.สมชาย วิทยาการ</span>
                                </div>
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                                    อ่านเพิ่มเติม <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Post 1 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                        <img src="https://via.placeholder.com/400x200/1e40af/ffffff?text=Blog+1" alt="Blog 1" class="w-full h-48 object-cover">
                        <div class="p-5">
                            <div class="flex items-center mb-2">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium mr-2">การศึกษา</span>
                                <span class="text-gray-500 text-xs">20 มิถุนายน 2568</span>
                            </div>
                            <h4 class="font-bold text-lg mb-2 leading-tight">การเตรียมความพร้อมสู่ยุค Digital Transformation</h4>
                            <p class="text-gray-600 text-sm mb-3 leading-relaxed">เทคโนโลยีดิจิทัลที่เปลี่ยนแปลงวิธีการเรียนรู้และการทำงานในศตวรรษที่ 21...</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="https://via.placeholder.com/24x24/1e40af/ffffff?text=B" alt="Author" class="w-6 h-6 rounded-full mr-2">
                                    <span class="text-xs text-gray-700">ผศ.ดร.วิทยา เทคโน</span>
                                </div>
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-xs font-medium">อ่านต่อ →</a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Post 2 -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                        <img src="https://via.placeholder.com/400x200/1e40af/ffffff?text=Blog+2" alt="Blog 2" class="w-full h-48 object-cover">
                        <div class="p-5">
                            <div class="flex items-center mb-2">
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium mr-2">นวัตกรรม</span>
                                <span class="text-gray-500 text-xs">19 มิถุนายน 2568</span>
                            </div>
                            <h4 class="font-bold text-lg mb-2 leading-tight">สตาร์ทอัปเทคโนโลยีจากนักศึกษา RMUTI</h4>
                            <p class="text-gray-600 text-sm mb-3 leading-relaxed">เรื่องราวความสำเร็จของนักศึกษาที่สร้างสรรค์นวัตกรรมเพื่อแก้ปัญหาสังคม...</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="https://via.placeholder.com/24x24/1e40af/ffffff?text=C" alt="Author" class="w-6 h-6 rounded-full mr-2">
                                    <span class="text-xs text-gray-700">อ.สมศรี นวัตกรรม</span>
                                </div>
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-xs font-medium">อ่านต่อ →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More Blog Posts -->
                <div class="grid md:grid-cols-3 gap-6 mt-8">
                    <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <img src="https://via.placeholder.com/400x160/1e40af/ffffff?text=Article+1" alt="Article 1" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <div class="flex items-center mb-2">
                                <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-medium mr-2">กิจกรรม</span>
                                <span class="text-gray-500 text-xs">18 มิถุนายน 2568</span>
                            </div>
                            <h5 class="font-semibold text-base mb-2">งานมหกรรมวิจัยนานาชาติ 2568</h5>
                            <p class="text-gray-600 text-sm leading-relaxed">การแสดงผลงานวิจัยและนวัตกรรมระดับนานาชาติ...</p>
                            <a href="#" class="inline-block mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">อ่านเพิ่มเติม →</a>
                        </div>
                    </article>

                    <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <img src="https://via.placeholder.com/400x160/1e40af/ffffff?text=Article+2" alt="Article 2" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <div class="flex items-center mb-2">
                                <span class="bg-teal-100 text-teal-800 px-2 py-1 rounded text-xs font-medium mr-2">เทคโนโลยี</span>
                                <span class="text-gray-500 text-xs">17 มิถุนายน 2568</span>
                            </div>
                            <h5 class="font-semibold text-base mb-2">AI และการประยุกต์ในอุตสาหกรรม</h5>
                            <p class="text-gray-600 text-sm leading-relaxed">การนำปัญญาประดิษฐ์มาใช้เพิ่มประสิทธิภาพ...</p>
                            <a href="#" class="inline-block mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">อ่านเพิ่มเติม →</a>
                        </div>
                    </article>

                    <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <img src="https://via.placeholder.com/400x160/1e40af/ffffff?text=Article+3" alt="Article 3" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <div class="flex items-center mb-2">
                                <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded text-xs font-medium mr-2">ชุมชน</span>
                                <span class="text-gray-500 text-xs">16 มิถุนายน 2568</span>
                            </div>
                            <h5 class="font-semibold text-base mb-2">โครงการพัฒนาชุมชนท้องถิ่น</h5>
                            <p class="text-gray-600 text-sm leading-relaxed">การมีส่วนร่วมในการพัฒนาเศรษฐกิจชุมชน...</p>
                            <a href="#" class="inline-block mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">อ่านเพิ่มเติม →</a>
                        </div>
                    </article>
                </div>

                <!-- Blog Navigation -->
                <div class="text-center mt-12">
                    <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300 inline-flex items-center">
                        ดูบทความทั้งหมด <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Quick Links -->
        <section class="py-16 bg-gray-100">
            <div class="container mx-auto px-4">
                <h3 class="text-3xl font-bold text-center mb-12 text-blue-900">บริการด่วน</h3>
                <div class="grid md:grid-cols-4 gap-6">
                    <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow text-center">
                        <i class="fas fa-graduation-cap text-4xl text-blue-600 mb-4"></i>
                        <h4 class="font-semibold">ระบบนักศึกษา</h4>
                    </a>
                    <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow text-center">
                        <i class="fas fa-book text-4xl text-blue-600 mb-4"></i>
                        <h4 class="font-semibold">ห้องสมุด</h4>
                    </a>
                    <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow text-center">
                        <i class="fas fa-calendar text-4xl text-blue-600 mb-4"></i>
                        <h4 class="font-semibold">ปฏิทินการศึกษา</h4>
                    </a>
                    <a href="#" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow text-center">
                        <i class="fas fa-envelope text-4xl text-blue-600 mb-4"></i>
                        <h4 class="font-semibold">อีเมล</h4>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h5 class="font-bold text-lg mb-4">ติดต่อเรา</h5>
                    <p class="text-sm mb-2">123 ถนนมหาวิทยาลัย</p>
                    <p class="text-sm mb-2">ตำบลธาตุเชิงชุม อำเภอเมือง</p>
                    <p class="text-sm mb-2">จังหวัดสกลนคร 47000</p>
                    <p class="text-sm">โทร: 042-761-000</p>
                </div>
                <div>
                    <h5 class="font-bold text-lg mb-4">ลิงก์ด่วน</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm hover:text-blue-200">รับเข้าศึกษา</a></li>
                        <li><a href="#" class="text-sm hover:text-blue-200">ระบบสารสนเทศ</a></li>
                        <li><a href="#" class="text-sm hover:text-blue-200">บุคลากร</a></li>
                        <li><a href="#" class="text-sm hover:text-blue-200">หลักสูตร</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-lg mb-4">บริการ</h5>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm hover:text-blue-200">ห้องสมุด</a></li>
                        <li><a href="#" class="text-sm hover:text-blue-200">ศูนย์คอมพิวเตอร์</a></li>
                        <li><a href="#" class="text-sm hover:text-blue-200">งานทะเบียน</a></li>
                        <li><a href="#" class="text-sm hover:text-blue-200">กิจการนักศึกษา</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-bold text-lg mb-4">ติดตามเรา</h5>
                    <div class="flex space-x-4">
                        <a href="#" class="text-2xl hover:text-blue-200"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl hover:text-blue-200"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-2xl hover:text-blue-200"><i class="fab fa-line"></i></a>
                        <a href="#" class="text-2xl hover:text-blue-200"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-blue-800 mt-8 pt-8 text-center">
                <p class="text-sm">&copy; 2025 มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน วิทยาเขตสกลนคร สงวนลิขสิทธิ์</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('active');
        });

        // Mobile submenu toggle
        function toggleMobileSubmenu(id) {
            const submenu = document.getElementById(id + '-submenu');
            submenu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');

            if (!mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                mobileMenu.classList.remove('active');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>
