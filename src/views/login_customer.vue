<template>
  <div class="container mt-5" style="max-width: 400px">
    <h3 class="text-center mb-4">🔐 เข้าสู่ระบบลูกค้า</h3>

    <div class="card p-4 shadow">
      <div class="mb-3">
        <label class="form-label">ชื่อผู้ใช้</label>
        <input v-model="username" type="text" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label">รหัสผ่าน</label>
        <input v-model="password" type="password" class="form-control" />
      </div>

      <button @click="login" class="btn btn-primary w-100">เข้าสู่ระบบ</button>

      <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>

      <div class="text-center mt-3">
        ยังไม่มีบัญชี? <router-link to="/register">สมัครสมาชิกที่นี่</router-link>
      </div>
    </div>
  </div>
</template>

<script>
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
          "http://localhost/air_project/api_php/login_customer.php", // เปลี่ยนเป็น API สำหรับลูกค้า
          {
            username: this.username,
            password: this.password,
          }
        );

        if (res.data.success) {
          localStorage.setItem("customerLogin", "true");
          // อาจจะเก็บข้อมูลลูกค้าหรือ Token แทน "true"
          // localStorage.setItem("customerToken", res.data.token);
          // localStorage.setItem("customerInfo", JSON.stringify(res.data.customer));

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