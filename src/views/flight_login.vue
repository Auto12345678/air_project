<template>
  <div class="page-container">
    <!-- Background & Animation -->
    <div class="sky-gradient-bg"></div>
    <i class="fas fa-cloud cloud-icon cloud-1"></i>
    <i class="fas fa-cloud cloud-icon cloud-2"></i>

    <div class="container content-wrapper">
      
      <!-- Glass Panel (กรอบแก้ว) -->
      <div class="glass-panel">
        <h2 class="text-center mb-4 page-title">
          <i class="fas fa-plane-departure me-2"></i> จองเที่ยวบินออนไลน์
        </h2>

        <!-- ส่วนเลือกผู้โดยสาร -->
        <div class="d-flex justify-content-center mb-5">
          <div class="glass-input-group">
            <label class="fw-bold me-3 text-white">
              <i class="fas fa-user-friends me-1"></i> ผู้โดยสาร:
            </label>
            <select v-model="passengers" class="form-select custom-select">
              <option disabled value="">-- เลือกจำนวน --</option>
              <option v-for="n in 5" :key="n" :value="n">{{ n }} คน</option>
            </select>
          </div>
        </div>

        <!-- รายการเที่ยวบิน (Grid Layout) -->
        <div class="row g-4">
          <div class="col-md-4" v-for="flight in flights" :key="flight.id">
            <div class="flight-card h-100">
              
              <!-- Header การ์ด (ชื่อสายการบิน) -->
              <div class="card-header-custom">
                <span class="airline-badge">{{ flight.airline }}</span>
                <i class="fas fa-plane plane-icon"></i>
              </div>
              
              <div class="card-body-custom">
                <!-- เส้นทางบิน -->
                <div class="route-info">
                  <div class="point text-start">
                    <div class="city-name">{{ flight.origin }}</div>
                    <div class="time text-primary">{{ flight.depart }}</div>
                  </div>
                  
                  <div class="connector px-2">
                    <i class="fas fa-long-arrow-alt-right text-muted"></i>
                  </div>

                  <div class="point text-end">
                    <div class="city-name">{{ flight.destination }}</div>
                    <div class="time text-primary">{{ flight.arrive }}</div>
                  </div>
                </div>

                <hr class="dashed-line">

                <!-- ราคา -->
                <div class="price-section">
                  <div class="total-price">
                    <span class="label">ราคา/ท่าน</span>
                    <span class="amount">{{ Number(flight.price).toLocaleString() }} ฿</span>
                  </div>
                </div>

                <!-- ปุ่มจอง -->
                <button class="btn-book mt-3 w-100" @click="bookFlight(flight)">
                  จองเลย ({{ (flight.price * passengers).toLocaleString() }} บาท)
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal แจ้งเตือนสำเร็จ (ดีไซน์ใหม่) -->
    <div v-if="showModal" class="modal d-block" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(5px);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
          <div class="modal-header bg-success text-white border-0 justify-content-center py-3">
            <h5 class="modal-title fw-bold"><i class="fas fa-check-circle me-2"></i>ทำรายการจองสำเร็จ!</h5>
          </div>
          <div class="modal-body text-center pt-4 pb-4">
            
            <div class="mb-3 animate-bounce">
                 <i class="fas fa-ticket-alt text-warning" style="font-size: 4rem;"></i>
            </div>

            <h5 class="text-muted mb-1 small text-uppercase">Booking ID</h5>
            <h2 class="fw-bold text-primary mb-4">#{{ currentBookingInfo.id }}</h2>
            
            <div class="card bg-light border-0 p-3 mb-3 text-start mx-auto" style="max-width: 90%;">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted"><i class="fas fa-plane me-1"></i> สายการบิน:</span>
                    <span class="fw-bold text-dark">{{ currentBookingInfo.airline }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted"><i class="fas fa-coins me-1"></i> ยอดชำระ:</span>
                    <span class="text-danger fw-bold fs-5">{{ Number(currentBookingInfo.price).toLocaleString() }} บาท</span>
                </div>
            </div>

            <p class="text-muted small mt-3">
              กรุณากดปุ่มด้านล่างเพื่อดำเนินการชำระเงิน
            </p>
          </div>
          <div class="modal-footer border-0 pb-4 justify-content-center gap-2">
            <button type="button" class="btn btn-light border px-4 rounded-pill" @click="showModal = false">
              ปิดหน้าต่าง
            </button>
            <button type="button" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" @click="goToPaymentPage">
              ไปหน้าชำระเงิน <i class="fas fa-arrow-right ms-1"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
// ---------- ส่วนนี้คือ Logic เดิมของคุณ 100% ----------
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

export default {
  name: "FlightBooking",
  setup() {
    const router = useRouter();
    const passengers = ref(1);
    const showModal = ref(false);
    const currentBookingInfo = ref({});

    const flights = ref([
      { id: 1, airline: "Thai Airways", origin: "กรุงเทพฯ (BKK)", destination: "เชียงใหม่ (CNX)", depart: "08:00", arrive: "09:30", price: 2500 },
      { id: 2, airline: "AirAsia", origin: "ดอนเมือง (DMK)", destination: "ภูเก็ต (HKT)", depart: "10:00", arrive: "11:30", price: 1200 },
      { id: 3, airline: "Nok Air", origin: "ดอนเมือง (DMK)", destination: "เชียงราย (CEI)", depart: "14:00", arrive: "15:15", price: 1400 },
      { id: 4, airline: "Thai Lion Air", origin: "ดอนเมือง (DMK)", destination: "หาดใหญ่ (HDY)", depart: "15:30", arrive: "16:45", price: 1350 },
      { id: 5, airline: "Thai Vietjet", origin: "สุวรรณภูมิ (BKK)", destination: "กระบี่ (KBV)", depart: "18:00", arrive: "19:15", price: 1200 },
      { id: 6, airline: "Bangkok Airways", origin: "สุวรรณภูมิ (BKK)", destination: "สมุย (USM)", depart: "20:15", arrive: "21:30", price: 2800 },
    ]);

    const bookFlight = async (flight) => {
      const totalPrice = flight.price * passengers.value;
      
      try {
        const res = await axios.post("http://localhost/air_project/api_php/api_booking.php", {
           flight_id: flight.id, // ส่ง ID เที่ยวบิน
           airline: flight.airline,
           origin: flight.origin,
           destination: flight.destination,
           depart: flight.depart,
           passengers: passengers.value,
           total_price: totalPrice
        });

        if (res.data.status === "success") {
           currentBookingInfo.value = {
             id: res.data.booking_id,
             airline: flight.airline,
             price: totalPrice
           };
           showModal.value = true;
        } else {
            alert("จองไม่สำเร็จ: " + res.data.message);
        }
      } catch (error) {
        console.error(error);
        alert("Error: เชื่อมต่อ Server ไม่ได้");
      }
    };

    const goToPaymentPage = () => {
       showModal.value = false;
       router.push({ 
         path: '/payment', 
         query: { id: currentBookingInfo.value.id } 
       });
    };

    return { passengers, flights, bookFlight, showModal, currentBookingInfo, goToPaymentPage };
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600;800&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.page-container {
  font-family: 'Kanit', sans-serif;
  min-height: 100vh;
  position: relative;
  padding-top: 100px;
  padding-bottom: 50px;
  color: white;
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
.cloud-1 { top: 120px; left: 5%; font-size: 8rem; animation: float 10s ease-in-out infinite; }
.cloud-2 { bottom: 80px; right: 10%; font-size: 6rem; animation: float 8s ease-in-out infinite reverse; }

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

/* Glass Panel */
.glass-panel {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(12px);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  padding: 2rem;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  position: relative;
  z-index: 10;
}

.page-title {
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Input Group */
.glass-input-group {
  background: rgba(255, 255, 255, 0.2);
  padding: 0.5rem 1.5rem;
  border-radius: 50px;
  display: flex;
  align-items: center;
  border: 1px solid rgba(255, 255, 255, 0.4);
}

.custom-select {
  background: transparent;
  border: none;
  color: white;
  font-weight: bold;
  cursor: pointer;
  outline: none;
}
.custom-select option {
  color: #333;
}

/* Flight Cards */
.flight-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 15px;
  overflow: hidden;
  color: #333;
  transition: transform 0.3s, box-shadow 0.3s;
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  border: none;
  display: flex;
  flex-direction: column;
}

.flight-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.card-header-custom {
  background: linear-gradient(135deg, #0ea5e9, #2563eb);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
}

.airline-badge {
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 1rem;
}

.card-body-custom {
  padding: 1.5rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.route-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.city-name {
  font-size: 0.95rem;
  font-weight: 600;
  color: #333;
  line-height: 1.2;
}

.time {
  font-weight: bold;
  color: #0ea5e9;
  font-size: 1.1rem;
  margin-top: 4px;
}

.dashed-line {
  border-top: 2px dashed #e0e0e0;
  margin: 1rem 0;
  opacity: 0.5;
}

.price-section {
  text-align: center;
  background: #f8f9fa;
  padding: 0.8rem;
  border-radius: 10px;
  border: 1px solid #eee;
  margin-bottom: auto; /* Push content down */
}

.total-price .label {
  font-size: 0.85rem;
  color: #666;
  margin-right: 5px;
}

.total-price .amount {
  font-size: 1.5rem;
  font-weight: 800;
  color: #dc2626;
}

.btn-book {
  background: linear-gradient(to right, #facc15, #eab308);
  color: #92400e;
  border: none;
  font-weight: 800;
  padding: 0.8rem;
  border-radius: 50px;
  transition: all 0.3s;
  box-shadow: 0 4px 6px rgba(234, 179, 8, 0.3);
}

.btn-book:hover {
  transform: scale(1.02);
  box-shadow: 0 8px 15px rgba(234, 179, 8, 0.4);
}

/* Modal Animation */
.animate-bounce {
    animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
</style>