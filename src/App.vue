<template>
  <nav class="navbar navbar-expand-lg " style="background-color: #39BF4A;">
  <div class="container">
    <!-- Login admin ใน Navbar -->
    <a class="navbar-brand" href="*">SkyDrop</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- แสดงเฉพาะเมื่อเข้าสู่ระบบแล้ว -->
          <template v-if="isLoggedIn">
        
        
        <li class="nav-item">
          <a class="nav-link" href="/show">Show Product</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/employees">Employees</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Product
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/product">Product</a></li>
            <li><a class="dropdown-item" href="/edit_product">ProductEdit</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="*"@click="logout">Logout</a>
        </li>
        

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Customer
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/custom">Customer</a></li>
            <li><a class="dropdown-item" href="edit">CustomerEdit</a></li>
            
          </ul>
        </li>
          </template>
           <!--ยังไม่ได้ล็อคอิน-->
           
           <!-- แสดงเฉพาะเมื่อยังไม่ได้เข้าสู่ระบบ -->
          <template v-else>
            <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">หน้าหลัก</a>
        </li>
        

         <li class="nav-item">
          <a class="nav-link" href="*">ค้นหาเที่ยวบิน</a>
        </li>

        <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-airplane-fill me-2"></i>จองตั๋วเครื่องบิน
  </a>
  <ul class="dropdown-menu">
    <li>
      <a class="dropdown-item" href="*">
        <i class="bi bi-plus-circle me-2"></i>จองตั๋วเครื่องบิน
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="*">
        <i class="bi bi-credit-card me-2"></i>ชำระเงิน
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="*">
        <i class="bi bi-search me-2"></i>ตรวจสอบสถานะการจอง
      </a>
    </li>
    <li><hr class="dropdown-divider"></li> <li>
      <a class="dropdown-item" href="*">
        <i class="bi bi-x-circle me-2"></i>ยกเลิกหรือเปลี่ยนเที่ยวบิน
      </a>
    </li>
  </ul>
</li>

        <li class="nav- dropdown">

          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
      isLoggedIn: false,
    };
  },
  mounted() {
    // ตรวจสอบสถานะเมื่อโหลดหน้า
    this.checkLogin();
  },
  methods: {
    checkLogin() {
      this.isLoggedIn = localStorage.getItem("customerLogin") === "true";
    },
    logout() {
      if (confirm("ต้องการออกจากระบบหรือไม่?")) {
        // เคลียร์ข้อมูลทั้งหมดที่เกี่ยวข้องกับการล็อกอิน
        localStorage.removeItem("customerLogin");
        localStorage.removeItem("username");
        localStorage.removeItem("token");
        this.isLoggedIn = false;

        // กลับไปหน้าเมนูหลัก
        this.$router.push("/");
      }
    },
  },
  watch: {
    // เมื่อเปลี่ยนเส้นทาง ให้ตรวจสอบสถานะการล็อกอินใหม่
    $route() {
      this.checkLogin();
    },
  },
};
</script>


<style scoped>
.navbar {
  background-color: #86bfe7ff !important;
}
.nav-link {
  color: white !important;
  font-weight: 500;
}
.nav-link:hover {
  text-decoration: underline;
}
</style>