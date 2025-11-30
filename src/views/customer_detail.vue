<template>
  <div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">👮‍♂️ แผงควบคุมพนักงาน</h2>
            <p class="text-muted small">รายการจองทั้งหมด (เรียงจากล่าสุด)</p>
        </div>
        <button class="btn btn-primary shadow-sm" @click="fetchBookings">
            🔄 รีเฟรชข้อมูล
        </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2 text-muted">กำลังดึงข้อมูล...</p>
    </div>

    <!-- Table -->
    <div v-else class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="ps-4 py-3">Book ID</th>
                            <th class="py-3 text-center">Cus ID</th> <!-- ✅ เปลี่ยนเป็น Cus ID -->
                            <th class="py-3">เที่ยวบิน</th>
                            <th class="py-3">เวลาเดินทาง</th>
                            <th class="text-center py-3">ยอดรวม</th>
                            <th class="text-center py-3">สถานะ</th>
                            <th class="text-end pe-4 py-3">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="booking in bookings" :key="booking.id">
                            <!-- Booking ID -->
                            <td class="ps-4 fw-bold text-primary">#{{ booking.id }}</td>
                            
                            <!-- ✅ Customer ID (แสดงแค่นี้ตามต้องการ) -->
                            <td class="text-center">
                                <span v-if="booking.customer_id" class="badge bg-light text-dark border">
                                    {{ booking.customer_id }}
                                </span>
                                <span v-else class="text-muted small">-</span>
                            </td>
                            
                            <!-- เที่ยวบิน -->
                            <td>
                                <div class="fw-bold">{{ booking.airline }}</div>
                                <div class="small text-muted">
                                    {{ booking.origin }} ➝ {{ booking.destination }}
                                </div>
                            </td>

                            <!-- เวลา -->
                            <td>{{ booking.depart_time }}</td>

                            <!-- ยอดเงิน -->
                            <td class="text-center fw-bold">
                                {{ Number(booking.total_price).toLocaleString() }}
                            </td>

                            <!-- สถานะ -->
                            <td class="text-center">
                                <span 
                                    class="badge rounded-pill"
                                    :class="booking.status === 'PAID' ? 'bg-success' : 'bg-warning text-dark'"
                                >
                                    {{ booking.status === 'PAID' ? 'จ่ายแล้ว' : 'รอจ่าย' }}
                                </span>
                            </td>

                            <!-- ปุ่มจัดการ -->
                            <td class="text-end pe-4">
                                <button 
                                    v-if="booking.status !== 'PAID'"
                                    class="btn btn-sm btn-outline-success"
                                    @click="manualApprove(booking.id)"
                                    title="ยืนยันยอดเงิน"
                                >
                                    ✅ อนุมัติ
                                </button>
                                <span v-else class="text-success small fw-bold">
                                    <i class="fas fa-check-double"></i> เสร็จสิ้น
                                </span>
                            </td>
                        </tr>

                        <!-- ไม่มีข้อมูล -->
                        <tr v-if="bookings.length === 0">
                            <td colspan="7" class="text-center py-5 text-muted">
                                ยังไม่มีรายการจองเข้ามาในระบบ
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
  name: "Employees",
  setup() {
    const bookings = ref([]);
    const loading = ref(true);

    const fetchBookings = async () => {
        loading.value = true;
        try {
            const res = await axios.get("http://localhost/air_project/api_php/api_get_all_bookings.php");
            bookings.value = res.data;
        } catch (error) {
            console.error(error);
            alert("ไม่สามารถดึงข้อมูลได้");
        } finally {
            loading.value = false;
        }
    };

    const manualApprove = async (id) => {
        if(!confirm(`ยืนยันการชำระเงิน Booking #${id}?`)) return;

        try {
            const res = await axios.post("http://localhost/air_project/api_php/api_pay.php", {
                booking_id: id
            });
            
            if (res.data.status === 'success') {
                alert("✅ อัปเดตสถานะเรียบร้อย!");
                fetchBookings();
            } else {
                alert("เกิดข้อผิดพลาด: " + res.data.message);
            }
        } catch (error) {
            alert("เชื่อมต่อ Server ไม่ได้");
        }
    };

    onMounted(fetchBookings);

    return { bookings, loading, fetchBookings, manualApprove };
  }
};
</script>

<style scoped>
.table th {
    font-size: 0.9rem;
    font-weight: 600;
}
</style>