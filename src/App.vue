<template>
  <nav class="navbar navbar-expand-lg" style="background-color: #39BF4A;">
    <div class="container">
      <a class="navbar-brand" href="/">SkyDrop</a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <template v-if="userRole === 'employee'">
            <li class="nav-item">
              <a class="nav-link" href="/customer">ข้อมูลลูกค้า</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="/employees">รายการจอง</a>
            </li>

             <li class="nav-item ms-lg-3">
              <button class="btn btn-danger btn-sm nav-link text-white px-3" @click="logout">
                Logout (Admin)
              </button>
            </li>
          </template>

          <template v-else-if="userRole === 'customer'">
            <li class="nav-item">
              <a class="nav-link active" href="/">หน้าหลัก</a>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-airplane-fill me-2"></i>จองตั๋วเครื่องบิน
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/log_air"><i class="bi bi-plus-circle me-2"></i>จองตั๋วเครื่องบิน</a></li>
                <li><a class="dropdown-item" href="/payment"><i class="bi bi-credit-card me-2"></i>ชำระเงิน</a></li>
                <li><a class="dropdown-item" href="/check"><i class="bi bi-search me-2"></i>ตรวจสอบสถานะ</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-x-circle me-2"></i>ยกเลิก/เปลี่ยนเที่ยวบิน</a></li>
              </ul>
            </li>
            

            <li class="nav-item ms-lg-3">
              <a class="nav-link" href="#" @click="logout" style="color: #fff;">
                <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
              </a>
            </li>
          </template>

          <template v-else>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/">หน้าหลัก</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="/no_air">ค้นหาเที่ยวบิน</a>
            </li>


            <li class="nav-item dropdown ms-lg-3">
              <a class="nav-link dropdown-toggle btn btn-outline-light text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid white; border-radius: 5px; padding: 8px 15px;">
                เข้าสู่ระบบ
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/login_customer">เข้าสู่ระบบ (ลูกค้า)</a></li>
                <li><a class="dropdown-item" href="/login_employee">เข้าสู่ระบบ (ผู้ดูแล)</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/register">สมัครสมาชิก</a></li>
              </ul>
            </li>
          </template>

        </ul>
      </div>
    </div>
  </nav>
  <router-view/>
</template>

<script>
export default {
  name: "Navbar",
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
      // 1. เช็คว่าเป็นพนักงานหรือไม่
      if (localStorage.getItem("employeeLogin") === "true") {
        this.userRole = 'employee';
      } 
      // 2. เช็คว่าเป็นลูกค้าหรือไม่
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
        // ล้างข้อมูลทั้งหมดใน LocalStorage
        localStorage.removeItem("customerLogin");
        localStorage.removeItem("employeeLogin");
        localStorage.removeItem("username");
        localStorage.removeItem("token");
        
        // รีเซ็ตสถานะเป็น Guest
        this.userRole = 'guest';

        // เด้งกลับไปหน้าแรก
        this.$router.push("/");
        
        // รีเฟรชหน้าจอเพื่อให้ Navbar อัปเดตทันที (Optional)
        // window.location.reload(); 
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

<style scoped>
.navbar {
  /* ใช้สีตามที่คุณกำหนดไว้ใน style หรือ class */
  background-color: #86bfe7ff !important; 
}
.nav-link {
  color: rgb(0, 0, 0) !important;
  font-weight: 500;
}
.nav-link:hover {
  text-decoration: underline;
}
/* ปรับแต่ง Dropdown ให้สวยงามขึ้น */
.dropdown-menu {
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
</style>