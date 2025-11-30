<template>
  <div class="container my-5">
    <h2 class="text-center mb-4">🔍 ตรวจสอบสถานะการจอง</h2>

    <div class="card mx-auto shadow-sm" style="max-width: 500px;">
      <div class="card-body p-4">
        
        <!-- ช่องค้นหา -->
        <label class="form-label fw-bold">กรอกรหัสการจอง (Booking ID)</label>
        <div class="input-group mb-3">
          <input 
            type="number" 
            class="form-control" 
            v-model="searchId" 
            placeholder="เช่น 15"
            @keyup.enter="checkStatus"
          >
          <button class="btn btn-primary" @click="checkStatus" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
            {{ loading ? 'กำลังค้นหา...' : 'ตรวจสอบ' }}
          </button>
        </div>

        <!-- แจ้งเตือนเมื่อไม่เจอ -->
        <div v-if="error" class="alert alert-danger mt-3 text-center">
          <i class="fas fa-exclamation-circle me-1"></i> {{ error }}
        </div>

        <!-- แสดงผลลัพธ์ -->
        <div v-if="bookingData" class="mt-4 pt-3 border-top">
            <h5 class="fw-bold text-primary text-center mb-3">ผลการค้นหา</h5>
            
            <div class="row mb-2">
                <div class="col-5 text-muted">รหัสการจอง:</div>
                <div class="col-7 fw-bold">{{ bookingData.id }}</div>
            </div>
            
            <div class="row mb-2">
                <div class="col-5 text-muted">สายการบิน:</div>
                <div class="col-7">{{ bookingData.airline }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-5 text-muted">เส้นทาง:</div>
                <div class="col-7">{{ bookingData.origin }} ➝ {{ bookingData.destination }}</div>
            </div>
            
            <div class="row mb-2">
                <div class="col-5 text-muted">เวลาเดินทาง:</div>
                <div class="col-7">{{ bookingData.depart_time }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-5 text-muted">ยอดชำระ:</div>
                <div class="col-7 fw-bold">{{ Number(bookingData.total_price).toLocaleString() }} บาท</div>
            </div>

            <div class="text-center my-4">
                <div class="badge rounded-pill p-3 w-100" 
                      :class="bookingData.status === 'PAID' ? 'bg-success' : 'bg-warning text-dark'"
                      style="font-size: 1.1rem;">
                    {{ bookingData.status === 'PAID' ? '✅ ชำระเงินเรียบร้อยแล้ว' : '⏳ รอการชำระเงิน' }}
                </div>
            </div>

            <!-- ปุ่ม Action -->
            <div v-if="bookingData.status !== 'PAID'">
                <button class="btn btn-outline-primary w-100" @click="goToPayment">
                    ไปหน้าชำระเงิน ➝
                </button>
            </div>
            <div v-else>
                <button class="btn btn-outline-secondary w-100" disabled>
                   รายการนี้เสร็จสมบูรณ์
                </button>
            </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

export default {
  name: "CheckStatus",
  setup() {
    const router = useRouter();
    const searchId = ref("");
    const bookingData = ref(null);
    const loading = ref(false);
    const error = ref("");

    const checkStatus = async () => {
        if (!searchId.value) {
            error.value = "กรุณากรอกรหัสการจอง";
            return;
        }

        loading.value = true;
        error.value = "";
        bookingData.value = null;

        try {
            // ใช้ API ตัวเดิมที่มีอยู่แล้วได้เลย
            const res = await axios.get(`http://localhost/air_project/api_php/api_get_booking_detail.php?id=${searchId.value}`);
            
            if (res.data && res.data.status !== 'error' && res.data.id) {
                bookingData.value = res.data;
            } else {
                error.value = "ไม่พบข้อมูลการจองรหัสนี้";
            }
        } catch (err) {
            console.error(err);
            error.value = "เกิดข้อผิดพลาดในการเชื่อมต่อ Server";
        } finally {
            loading.value = false;
        }
    };

    const goToPayment = () => {
        router.push({ path: '/payment', query: { id: bookingData.value.id } });
    };

    return { searchId, bookingData, loading, error, checkStatus, goToPayment };
  }
};
</script>

<style scoped>
/* Bootstrap handles styling */
</style>