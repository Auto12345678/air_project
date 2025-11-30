<template>
  <div class="container my-5 text-center">
    <div class="card mx-auto shadow" style="max-width: 450px;">
      <div class="card-header bg-primary text-white">
        <h4>📲 ชำระเงิน</h4>
        <small>Booking ID: {{ bookingId }}</small>
      </div>
      
      <div class="card-body p-4">
        <div v-if="loading" class="py-5">
            <div class="spinner-border text-primary"></div>
            <p>กำลังดึงข้อมูล...</p>
        </div>

        <div v-else>
            <h5 class="text-muted">{{ bookingData.airline }}</h5>
            <p v-if="bookingData.origin" class="small text-muted">
                {{ bookingData.origin }} ➝ {{ bookingData.destination }}
            </p>
            <p class="mb-2">เดินทาง: {{ bookingData.depart_time }} ({{ bookingData.passengers }} ท่าน)</p>
            
            <h2 class="text-primary fw-bold mb-4">
                {{ Number(bookingData.total_price).toLocaleString() }} บาท
            </h2>
            
            <div v-if="bookingData.status === 'PAID'" class="alert alert-success mt-4">
                ✅ รายการนี้ชำระเงินเรียบร้อยแล้ว
            </div>
            
            <div v-else>
                <div class="alert alert-warning">สถานะ: รอการชำระเงิน</div>
                <button class="btn btn-success w-100 py-2 mt-2" @click="confirmPayment" :disabled="isProcessing">
                    {{ isProcessing ? 'กำลังตรวจสอบ...' : '💳 ยืนยันการชำระเงิน (จำลอง)' }}
                </button>
            </div>
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
  name: "PaymentPage",
  setup() {
    const route = useRoute();
    const router = useRouter();
    const bookingId = route.query.id;
    
    const bookingData = ref({});
    const loading = ref(true);
    const isProcessing = ref(false);

    const fetchBookingDetail = async () => {
        if (!bookingId) {
            alert("ไม่พบรหัสการจอง");
            router.push('/');
            return;
        }
        try {
            const res = await axios.get(`http://localhost/air_project/api_php/api_get_booking_detail.php?id=${bookingId}`);
            if (res.data && res.data.status !== 'error') {
                bookingData.value = res.data;
            } else {
                alert("ไม่พบข้อมูลการจอง");
            }
        } catch (error) {
            console.error(error);
            alert("เชื่อมต่อ API ไม่ได้");
        } finally {
            loading.value = false;
        }
    };

    const confirmPayment = async () => {
        isProcessing.value = true;
        setTimeout(async () => {
            try {
                const res = await axios.post("http://localhost/air_project/api_php/api_pay.php", { 
                    booking_id: bookingId 
                });
                if (res.data.status === 'success') {
                    alert("✅ ชำระเงินสำเร็จ!");
                    // รีโหลดข้อมูลเพื่ออัปเดตสถานะเป็น PAID
                    fetchBookingDetail();
                } else {
                    alert("เกิดข้อผิดพลาด: " + res.data.message);
                }
            } catch (error) {
                alert("เกิดข้อผิดพลาดในการเชื่อมต่อ");
            } finally {
                isProcessing.value = false;
            }
        }, 1500);
    };

    onMounted(fetchBookingDetail);

    return { bookingId, bookingData, loading, isProcessing, confirmPayment };
  }
};
</script>