<template>
  <div class="app-wrapper">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar">
      <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center" href="/">
          <div class="brand-icon me-2">
            <i class="bi bi-clouds-fill"></i>
          </div>
          <span class="brand-text">SkyDrop</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">

            <!-- 🟢 กรณีพนักงาน (Employee) -->
            <template v-if="userRole === 'employee'">
              <li class="nav-item">
                <a class="nav-link" href="/customer">
                  <i class="bi bi-people-fill me-1"></i> ข้อมูลลูกค้า
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/employee">
                  <i class="bi bi-person-badge-fill me-1"></i> ข้อมูลพนักงาน
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/detail">
                  <i class="bi bi-journal-text me-1"></i> รายการจอง
                </a>
              </li>
              <li class="nav-item ms-lg-3">
                <button class="btn btn-logout btn-sm px-4 rounded-pill" @click="logout">
                  <i class="bi bi-power me-1"></i> Logout (Admin)
                </button>
              </li>
            </template>

            <!-- 🟠 กรณีลูกค้า (Customer) -->
            <template v-else-if="userRole === 'customer'">
              <li class="nav-item">
                <a class="nav-link active" href="/">หน้าหลัก</a>
              </li>

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-airplane-fill me-1"></i> จองตั๋วเครื่องบิน
                </a>
                <ul class="dropdown-menu dropdown-menu-end animate slideIn">
                  <li><a class="dropdown-item" href="/log_air"><i class="bi bi-plus-circle me-2 text-primary"></i>จองตั๋วเครื่องบิน</a></li>
                  <li><a class="dropdown-item" href="/check"><i class="bi bi-search me-2 text-info"></i>ตรวจสอบสถานะ</a></li>
                </ul>
              </li>

              <li class="nav-item ms-lg-3">
                <a class="nav-link btn btn-outline-light border-0 px-3 rounded-pill" href="#" @click="logout">
                  <i class="bi bi-box-arrow-right me-1"></i> ออกจากระบบ
                </a>
              </li>
            </template>

            <!-- ⚪ กรณีทั่วไป (Guest) -->
            <template v-else>
              <li class="nav-item">
                <a class="nav-link active" href="/">หน้าหลัก</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/no_air">
                  <i class="bi bi-search me-1"></i> ค้นหาเที่ยวบิน
                </a>
              </li>

              <li class="nav-item dropdown ms-lg-3">
                <a class="btn btn-login dropdown-toggle text-white px-4 rounded-pill shadow-sm" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-box-arrow-in-right me-1"></i> เข้าสู่ระบบ
                </a>
                <ul class="dropdown-menu dropdown-menu-end animate slideIn">
                  <li><a class="dropdown-item" href="/login_customer">เข้าสู่ระบบ (ลูกค้า)</a></li>
                  <li><a class="dropdown-item" href="/login_employee">เข้าสู่ระบบ (ผู้ดูแล)</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item fw-bold text-primary" href="/register">สมัครสมาชิก</a></li>
                </ul>
              </li>
            </template>

          </ul>
        </div>
      </div>
    </nav>

    <!-- Content Area -->
    <div class="content-wrapper">
      <router-view/>
    </div>
  </div>
</template>

<script>
export default {
  name: "App",
  data() {
    return {
      // เก็บสถานะ: 'guest' (ทั่วไป), 'customer' (ลูกค้า), 'employee' (พนักงาน)
      userRole: 'guest', 
    };
  },
  mounted() {
    // ตรวจสอบสถานะทันทีที่โหลดหน้าเว็บ
    this.checkLogin();
  },
  methods: {
    checkLogin() {
      // 1. เช็คว่าเป็นพนักงานหรือไม่ (ใช้ Logic เดิมของคุณ)
      if (localStorage.getItem("employeeLogin") === "true") {
        this.userRole = 'employee';
      } 
      // 2. เช็คว่าเป็นลูกค้าหรือไม่ (ใช้ Logic เดิมของคุณ)
      else if (localStorage.getItem("customerLogin") === "true") {
        this.userRole = 'customer';
      } 
      // 3. ถ้าไม่มีใคร Login เลย
      else {
        this.userRole = 'guest';
      }
    },
    logout() {
      if (confirm("ต้องการออกจากระบบหรือไม่?")) {
        // ล้างข้อมูลทั้งหมดใน LocalStorage (ตาม Code เดิมของคุณ)
        localStorage.removeItem("customerLogin");
        localStorage.removeItem("employeeLogin");
        localStorage.removeItem("username");
        localStorage.removeItem("token");
        
        // รีเซ็ตสถานะเป็น Guest
        this.userRole = 'guest';

        // เด้งกลับไปหน้าแรก
        this.$router.push("/");
      }
    },
  },
  watch: {
    // เมื่อเปลี่ยนหน้า (Route) ให้เช็ค Login ใหม่ทุกครั้ง
    $route() {
      this.checkLogin();
    },
  },
};
</script>

<style>
/* Import Google Fonts */
@import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css");

/* Global Styles */
body {
  font-family: 'Kanit', sans-serif;
  background-color: #f8f9fa; /* สีพื้นหลังเว็บ */
}

/* Navbar Styling */
.custom-navbar {
  /* Gradient สีฟ้าท้องฟ้า */
  background: linear-gradient(90deg, #0ea5e9 0%, #2563eb 100%);
  box-shadow: 0 4px 20px rgba(37, 99, 235, 0.2);
  padding: 0.8rem 0;
  transition: all 0.3s ease;
}

.brand-icon {
  font-size: 1.5rem;
  color: #fff;
  animation: float 3s ease-in-out infinite;
}

.brand-text {
  font-size: 1.5rem;
  font-weight: 700;
  letter-spacing: 1px;
  color: #fff;
  text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Menu Links */
.navbar-nav .nav-link {
  color: rgba(255, 255, 255, 0.9) !important;
  font-weight: 400;
  margin: 0 5px;
  transition: color 0.3s, transform 0.2s;
  position: relative;
}

.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
  color: #fff !important;
  transform: translateY(-1px);
}

/* Underline effect on hover */
.navbar-nav .nav-link::after {
  content: '';
  position: absolute;
  width: 0;
  height: 2px;
  bottom: 5px;
  left: 50%;
  background-color: #facc15; /* สีเหลืองทอง */
  transition: all 0.3s ease;
  transform: translateX(-50%);
}

.navbar-nav .nav-link:hover::after {
  width: 80%;
}

/* Buttons */
.btn-login {
  background-color: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.4);
  backdrop-filter: blur(5px);
  transition: all 0.3s;
}

.btn-login:hover, .btn-login:focus {
  background-color: #fff;
  color: #2563eb !important;
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
}

.btn-logout {
  background-color: #ef4444;
  border: none;
  color: white;
  transition: background-color 0.3s;
}
.btn-logout:hover {
  background-color: #dc2626;
}

/* Dropdown Menu */
.dropdown-menu {
  border: none;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  overflow: hidden;
  margin-top: 10px;
}

.dropdown-item {
  padding: 10px 20px;
  transition: background-color 0.2s;
  font-size: 0.95rem;
}

.dropdown-item:hover {
  background-color: #f0f9ff;
  color: #0284c7;
}

/* Animations */
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-3px); }
}

@keyframes slideIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate {
  animation-duration: 0.3s;
  animation-fill-mode: both;
}

.slideIn {
  animation-name: slideIn;
}

/* Content Wrapper to prevent overlap with fixed navbar */
.content-wrapper {
  padding-top: 80px; /* ปรับตามความสูงของ Navbar */
  min-height: 100vh;
}
</style>