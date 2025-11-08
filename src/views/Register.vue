<template>
  <div class="container mt-5" style="max-width: 500px">
    <h3 class="text-center mb-4">📝 สมัครสมาชิก (Register)</h3>

    <div class="card p-4 shadow">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">ชื่อจริง</label>
          <input v-model="firstname" type="text" class="form-control" />
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">นามสกุล</label>
          <input v-model="lastname" type="text" class="form-control" />
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">อีเมล</label>
        <input v-model="email" type="email" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label">เบอร์โทรศัพท์</label>
        <input v-model="tel" type="text" class="form-control" />
      </div>

      <hr />

      <div class="mb-3">
        <label class="form-label">ชื่อผู้ใช้ (Username)</label>
        <input v-model="username" type="text" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label">รหัสผ่าน</label>
        <input v-model="password" type="password" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label">ยืนยันรหัสผ่าน</label>
        <input
          v-model="confirm_password"
          type="password"
          class="form-control"
        />
      </div>

      <button @click="register" class="btn btn-success w-100">
        ยืนยันการสมัคร
      </button>

      <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
      <div v-if="success" class="alert alert-success mt-3">{{ success }}</div>

      <div class="text-center mt-3">
        มีบัญชีแล้ว? <router-link to="/login_customer">เข้าสู่ระบบ</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      firstname: "",
      lastname: "",
      email: "",
      tel: "",
      username: "",
      password: "",
      confirm_password: "",
      error: "",
      success: "",
    };
  },
  methods: {
    async register() {
      // 1. เคลียร์ข้อความเก่า
      this.error = "";
      this.success = "";

      // 2. ตรวจสอบรหัสผ่าน
      if (this.password !== this.confirm_password) {
        this.error = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
        return;
      }

      // 3. ตรวจสอบว่ากรอกข้อมูลครบ (แบบง่าย)
      if (
        !this.firstname ||
        !this.username ||
        !this.password ||
        !this.email
      ) {
        this.error = "กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน (ชื่อ, อีเมล, ชื่อผู้ใช้, รหัสผ่าน)";
        return;
      }

      // 4. ส่งข้อมูลไป API
      try {
        const res = await axios.post(
          "http://localhost/air-project/api_php/show_customer.php", // API สำหรับสมัครสมาชิก
          {
            firstname: this.firstname,
            lastname: this.lastname,
            email: this.email,
            tel: this.tel,
            username: this.username,
            password: this.password,
          }
        );

        if (res.data.success) {
          this.success =
            "สมัครสมาชิกสำเร็จ! กำลังจะพาคุณไปหน้าเข้าสู่ระบบ...";
          // รอ 2 วินาทีแล้วไปหน้า login
          setTimeout(() => {
            this.$router.push("/login_customer");
          }, 2000);
        } else {
          // แสดง error จาก backend (เช่น: "ชื่อผู้ใช้นี้ถูกใช้แล้ว")
          this.error = res.data.message;
        }
      } catch (err) {
        this.error = "เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์";
      }
    },
  },
};
</script>