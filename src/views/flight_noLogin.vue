<template>
  <div class="page-container">
    <!-- Background (Same as HomeView) -->
    <div class="sky-gradient-bg"></div>
    
    <!-- Clouds Animation -->
    <i class="fas fa-cloud cloud-icon cloud-1"></i>
    <i class="fas fa-cloud cloud-icon cloud-2"></i>

    <div class="container content-wrapper">
      
      <!-- Main Glass Panel -->
      <div class="glass-panel">
        <h2 class="text-center mb-4 page-title">
          <i class="fas fa-plane-departure me-2"></i> จองเที่ยวบินออนไลน์
        </h2>

        <!-- Passenger Selector -->
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

        <!-- Flights Grid -->
        <div class="row g-4">
          <div class="col-md-4" v-for="flight in flights" :key="flight.id">
            <div class="flight-card h-100">
              <!-- Card Header -->
              <div class="card-header-custom">
                <span class="airline-badge">{{ flight.airline }}</span>
                <i class="fas fa-plane plane-icon"></i>
              </div>
              
              <div class="card-body-custom">
                <!-- Route Info -->
                <div class="route-info">
                  <!-- Origin -->
                  <div class="point">
                    <!-- ดึงรหัสสนามบิน (ถ้ามีในวงเล็บ) -->
                    <div class="city-code">{{ flight.origin.includes('(') ? flight.origin.match(/\(([^)]+)\)/)[1] : 'ORG' }}</div>
                    <div class="city-name text-truncate" :title="flight.origin">{{ flight.origin.split('(')[0] }}</div>
                    <div class="time">{{ flight.depart }}</div>
                  </div>
                  
                  <!-- Arrow Connector -->
                  <div class="connector">
                    <i class="fas fa-long-arrow-alt-right"></i>
                    <div class="duration-line"></div>
                  </div>

                  <!-- Destination -->
                  <div class="point">
                    <div class="city-code">{{ flight.destination.includes('(') ? flight.destination.match(/\(([^)]+)\)/)[1] : 'DST' }}</div>
                    <div class="city-name text-truncate" :title="flight.destination">{{ flight.destination.split('(')[0] }}</div>
                    <div class="time">{{ flight.arrive }}</div>
                  </div>
                </div>

                <hr class="dashed-line">

                <!-- Price Section -->
                <div class="price-section">
                  <div class="total-price">
                    <span class="label">ราคา/ท่าน</span>
                    <span class="amount">{{ Number(flight.price).toLocaleString() }} ฿</span>
                  </div>
                </div>

                <!-- Total Price Summary (ถ้าเลือก > 1 คน) -->
                <div class="total-summary mt-2 text-center" v-if="passengers > 1">
                  <small class="text-primary fw-bold">
                    รวม {{ passengers }} ท่าน: {{ (flight.price * passengers).toLocaleString() }} ฿
                  </small>
                </div>

                <!-- Action Button -->
                <button class="btn-book mt-3 w-100" @click="goToLogin(flight.id)">
                  จองเลย <i class="fas fa-ticket-alt ms-1"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { useRouter } from "vue-router";

export default {
  name: "FlightBooking",
  setup() {
    const router = useRouter();
    const passengers = ref(1);
    
    // ข้อมูลเที่ยวบิน
    const flights = ref([
      { id: 1, airline: "Thai Airways", origin: "กรุงเทพฯ (BKK)", destination: "เชียงใหม่ (CNX)", depart: "08:00", arrive: "09:30", price: 2500 },
      { id: 2, airline: "AirAsia", origin: "ดอนเมือง (DMK)", destination: "ภูเก็ต (HKT)", depart: "10:00", arrive: "11:30", price: 1200 },
      { id: 3, airline: "Nok Air", origin: "ดอนเมือง (DMK)", destination: "เชียงราย (CEI)", depart: "14:00", arrive: "15:15", price: 1400 },
      { id: 4, airline: "Thai Lion Air", origin: "ดอนเมือง (DMK)", destination: "หาดใหญ่ (HDY)", depart: "15:30", arrive: "16:45", price: 1350 },
      { id: 5, airline: "Thai Vietjet", origin: "สุวรรณภูมิ (BKK)", destination: "กระบี่ (KBV)", depart: "18:00", arrive: "19:15", price: 1200 },
      { id: 6, airline: "Bangkok Airways", origin: "สุวรรณภูมิ (BKK)", destination: "สมุย (USM)", depart: "20:15", arrive: "21:30", price: 2800 },
    ]);

    const goToLogin = (flightId) => {
      // Logic เหมือนเดิม
      router.push({ 
        path: '/login_customer', 
        query: { flight_id: flightId, pax: passengers.value } 
      });
    };

    return {
      passengers,
      flights,
      goToLogin,
    };
  },
};
</script>

<style scoped>
/* Reuse fonts */
@import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600;800&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.page-container {
  font-family: 'Kanit', sans-serif;
  min-height: 100vh;
  position: relative;
  padding-top: 100px; /* Space for Navbar */
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
}
.cloud-1 { top: 100px; left: 5%; font-size: 8rem; animation: float 10s ease-in-out infinite; }
.cloud-2 { bottom: 100px; right: 10%; font-size: 6rem; animation: float 8s ease-in-out infinite reverse; }

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

/* Glass Panel */
.glass-panel {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
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

/* Inputs */
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
  position: relative;
}

.flight-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.card-header-custom {
  background: linear-gradient(45deg, #0ea5e9, #2563eb);
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
}

.airline-badge {
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 0.95rem;
}

.card-body-custom {
  padding: 1.5rem;
}

.route-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.point {
  text-align: center;
  flex: 1;
}

.city-code {
  font-size: 1.8rem;
  font-weight: 800;
  color: #333;
  line-height: 1;
}

.city-name {
  font-size: 0.8rem;
  color: #666;
}

.time {
  font-weight: bold;
  color: #0ea5e9;
  margin-top: 5px;
  font-size: 1.1rem;
}

.connector {
  flex: 0 0 40px;
  text-align: center;
  color: #ccc;
  position: relative;
  top: -15px;
}

.dashed-line {
  border-top: 2px dashed #ddd;
  margin: 1rem 0;
}

.price-section {
  text-align: center;
  background: #f8f9fa;
  padding: 0.5rem;
  border-radius: 10px;
}

.total-price {
  display: flex;
  justify-content: center;
  align-items: baseline;
  gap: 5px;
}

.total-price .label {
  font-size: 0.8rem;
  color: #666;
}

.total-price .amount {
  font-size: 1.5rem;
  font-weight: 800;
  color: #dc2626;
}

.btn-book {
  background: #facc15;
  color: #b91c1c;
  border: none;
  font-weight: 800;
  padding: 0.75rem;
  border-radius: 50px;
  transition: all 0.2s;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-book:hover {
  background: #fbbf24;
  transform: scale(1.02);
  box-shadow: 0 5px 15px rgba(250, 204, 21, 0.3);
}
</style>