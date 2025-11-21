<template>
  <div class="container my-5 text-center">
    <div class="card mx-auto shadow" style="max-width: 450px;">
      <div class="card-header bg-primary text-white">
        <h4>📲 สแกนจ่ายเงิน</h4>
        <small>Booking ID: {{ bookingId }}</small>
      </div>
      
      <div class="card-body p-4">
        <div v-if="loading" class="py-5">
            <div class="spinner-border text-primary"></div>
            <p>กำลังดึงข้อมูลลูกค้า...</p>
        </div>

        <div v-else>
            <h5 class="text-muted">{{ bookingData.airline }}</h5>
            <p class="mb-2">เดินทาง: {{ bookingData.depart_time }} ({{ bookingData.passengers }} ท่าน)</p>
            
            <h2 class="text-primary fw-bold mb-4">
                {{ Number(bookingData.total_price).toLocaleString() }} บาท
            </h2>
            
            <div class="my-4 position-relative d-inline-block border p-2 bg-light">
                <img src="/upload.png" width="200" alt="QR Code">
                
                <div v-if="isScanning" class="scan-line"></div>
            </div>
            
            <p class="text-muted small">กรุณาสแกน QR Code ด้านบนเพื่อชำระเงิน</p>

            <div v-if="bookingData.payment_status === 'paid'" class="alert alert-success">
                ✅ รายการนี้ชำระเงินแล้ว
            </div>
            
            <button v-else class="btn btn-outline-success w-100" @click="simulateScan" :disabled="isScanning">
                {{ isScanning ? 'กำลังตรวจสอบ...' : '📱 จำลองการสแกน (Demo)' }}
            </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

export default {
  setup() {
    const route = useRoute();
    const router = useRouter();
    const bookingId = route.query.id;
    
    const bookingData = ref({});
    const loading = ref(true);
    const isScanning = ref(false);

    // 1. ฟังก์ชันดึงข้อมูลจาก Database เมื่อเปิดหน้า
    const fetchBookingDetail = async () => {
        try {
            const res = await axios.get(`http://localhost/flight_api/api_get_booking_detail.php?id=${bookingId}`);
            if (res.data && !res.data.error) {
                bookingData.value = res.data;
            }
        } catch (error) {
            console.error("Connection Error", error);
            alert("ไม่สามารถดึงข้อมูลการจองได้");
        } finally {
            loading.value = false;
        }
    };

    // 2. ฟังก์ชันจำลองการจ่ายเงิน (อัปเดต DB)
    const simulateScan = () => {
      isScanning.value = true;
      
      setTimeout(async () => {
        try {
            const res = await axios.post("http://localhost/air_project/api_php/api_pay.php", { booking_id: bookingId });
            if (res.data.status === 'success') {
                alert("✅ ชำระเงินสำเร็จ!");
                router.push('/'); 
            }
        } catch (error) {
            alert("เกิดข้อผิดพลาดในการชำระเงิน");
        } finally {
            isScanning.value = false;
        }
      }, 2000);
    };

    // เรียกใช้ทันทีที่เปิดหน้า
    onMounted(fetchBookingDetail);

    return { bookingId, bookingData, loading, isScanning, simulateScan };
  }
};
</script>

<style scoped>
.scan-line {
  position: absolute; width: 100%; height: 2px; background: red;
  top: 0; left: 0; animation: scan 1.5s infinite;
}
@keyframes scan { 0% {top:0} 100% {top:100%} }
</style>