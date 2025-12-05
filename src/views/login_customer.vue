<template>
  <div class="page-container">
    <!-- Background & Animation -->
    <div class="sky-gradient-bg"></div>
    <i class="fas fa-cloud cloud-icon cloud-1"></i>
    <i class="fas fa-cloud cloud-icon cloud-2"></i>

    <div class="container content-wrapper d-flex justify-content-center align-items-center">
      
      <!-- Glass Panel Card -->
      <div class="glass-panel shadow-lg p-0" style="max-width: 450px; width: 100%;">
        <div class="card-header-custom text-center py-4">
          <div class="mb-2 icon-bounce">
            <i class="fas fa-user-circle text-white" style="font-size: 4rem;"></i>
          </div>
          <h3 class="fw-bold text-white mb-0 text-shadow">🔐 เข้าสู่ระบบลูกค้า</h3>
          <p class="text-white-50 small mb-0">ยินดีต้อนรับสู่ SkyDrop</p>
        </div>

        <div class="card-body p-4">
          <form @submit.prevent="login"> <!-- เพิ่ม prevent เพื่อไม่ให้ refresh หน้า -->
            <div class="mb-4">
              <label class="form-label fw-bold text-secondary">ชื่อผู้ใช้</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                  <i class="fas fa-user text-primary"></i>
                </span>
                <input 
                  v-model="username" 
                  type="text" 
                  class="form-control border-start-0 rounded-end-pill ps-2" 
                  placeholder="กรอกชื่อผู้ใช้ของคุณ"
                  required
                />
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-bold text-secondary">รหัสผ่าน</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                  <i class="fas fa-lock text-primary"></i>
                </span>
                <input 
                  v-model="password" 
                  type="password" 
                  class="form-control border-start-0 rounded-end-pill ps-2" 
                  placeholder="กรอกรหัสผ่าน"
                  required
                />
              </div>
            </div>

            <button type="submit" class="btn btn-login w-100 rounded-pill fw-bold shadow-sm mb-3">
              เข้าสู่ระบบ <i class="fas fa-sign-in-alt ms-1"></i>
            </button>
          </form>

          <!-- Error Message -->
          <div v-if="error" class="alert alert-danger d-flex align-items-center rounded-3 p-2 small mb-3">
            <i class="fas fa-exclamation-circle me-2 fs-5"></i>
            <div>{{ error }}</div>
          </div>

          <hr class="text-muted opacity-25 my-4">

          <div class="text-center">
            <span class="text-muted small">ยังไม่มีบัญชีใช่ไหม?</span>
            <router-link to="/register" class="text-decoration-none fw-bold ms-1 text-primary link-hover">
              สมัครสมาชิกที่นี่ <i class="fas fa-arrow-right small"></i>
            </router-link>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
// ---------- Logic เดิมของคุณ 100% ----------
import axios from "axios";
export default {
  data() {
    return {
      username: "",
      password: "",
      error: "",
    };
  },
  methods: {
    async login() {
      try {
        this.error = ""; // เคลียร์ error เก่า
        const res = await axios.post(
          
          "http://localhost/air_project/api_php/login_customer.php", 
          {
            username: this.username,
            password: this.password,
          }
        );

        if (res.data.success) {
          localStorage.setItem("customerLogin", "true");
          // เก็บข้อมูลเพิ่มเติมตาม API ที่ตอบกลับมา
          if(res.data.user) {
             localStorage.setItem("username", res.data.user.username);
             localStorage.setItem("userId", res.data.user.id);
             localStorage.setItem("role", "customer"); // เพิ่ม role เพื่อใช้กับ Navbar ใหม่
          }

          this.$router.push("/"); // ไปหน้าแรก หรือหน้า profile
        } else {
          this.error = res.data.message;
        }
      } catch (err) {
        this.error = "เกิดข้อผิดพลาดในการเชื่อมต่อ";
      }
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.page-container {
  font-family: 'Kanit', sans-serif;
  min-height: 100vh;
  position: relative;
  /* จัดให้อยู่กึ่งกลางหน้าจอเสมอ */
  display: flex;
  align-items: center; 
  justify-content: center;
  padding-top: 60px; /* เผื่อที่ให้ Navbar */
}

/* Background Animation */
.sky-gradient-bg {
  position: fixed;
  inset: 0;
  z-index: -1;
  background: radial-gradient(circle at 50% 120%, #ffcd00 0%, #ff8a65 20%, #4fc3f7 60%, #0288d1 100%);
  background-size: 200% 200%;
  animation: sky-shift 20s infinite alternate;
}

@keyframes sky-shift {
  0% { background-position: 50% 0%; }
  100% { background-position: 50% 100%; }
}

.cloud-icon {
  position: fixed;
  color: white;
  opacity: 0.15;
  z-index: 0;
  pointer-events: none;
}
.cloud-1 { top: 15%; left: 10%; font-size: 8rem; animation: float 10s ease-in-out infinite; }
.cloud-2 { bottom: 15%; right: 10%; font-size: 6rem; animation: float 8s ease-in-out infinite reverse; }

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

/* Glass Panel */
.glass-panel {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(15px);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.6);
  overflow: hidden;
  position: relative;
  z-index: 10;
  margin-top: -250px; /* ขยับขึ้นตามที่ต้องการ */
}

.card-header-custom {
  background: linear-gradient(135deg, #0ea5e9, #2563eb);
}

.text-shadow {
  text-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

/* Form Styles */
.input-group-text {
  background-color: #f8f9fa;
  border-color: #e9ecef;
  color: #6c757d;
}

.form-control {
  background-color: #f8f9fa;
  border-color: #e9ecef;
}

.form-control:focus {
  background-color: #fff;
  border-color: #0ea5e9;
  box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.15);
}

.btn-login {
  background: linear-gradient(to right, #0ea5e9, #2563eb);
  color: white;
  border: none;
  padding: 12px;
  font-size: 1.1rem;
  transition: all 0.3s;
}

.btn-login:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
  background: linear-gradient(to right, #0284c7, #1d4ed8);
}

.link-hover:hover {
  text-decoration: underline !important;
  color: #0284c7 !important;
}

.icon-bounce {
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}
</style>