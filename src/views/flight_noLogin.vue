<template>
  <div class="container my-5">
    <h2 class="text-center mb-4">✈️ จองเที่ยวบินออนไลน์</h2>

    <div class="mb-4 text-center">
      <label class="fw-bold">ผู้โดยสาร:</label>
      <select v-model="passengers" class="form-select d-inline-block w-auto ms-2">
        <option disabled value="">-- เลือกจำนวน --</option>
        <option v-for="n in 5" :key="n" :value="n">{{ n }} คน</option>
      </select>
    </div>

    <div class="row">
      <div class="col-md-4" v-for="flight in flights" :key="flight.id">
        <div class="card shadow-sm mb-4">
          <div class="card-body text-center">
            <h5 class="card-title text-primary mb-3">{{ flight.airline }}</h5>
            
            <div class="d-flex justify-content-center align-items-center mb-2">
              <span class="fw-bold">{{ flight.origin }}</span>
              <span class="mx-2 text-muted">➝</span> <span class="fw-bold">{{ flight.destination }}</span>
            </div>

            <p class="text-muted mb-2">เวลา: {{ flight.depart }} - {{ flight.arrive }}</p>
            
            <h4 class="fw-bold text-dark">{{ flight.price }} บาท</h4>
            
            <button class="btn btn-success w-100 mt-2" @click="goToLogin(flight.id)">
              จองเลย ({{ flight.price * passengers }} บาท)
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

export default {
  name: "FlightBooking",
  setup() {
    const router = useRouter();
    const passengers = ref(1);
    
    // เพิ่มข้อมูล origin (ต้นทาง) และ destination (ปลายทาง) ใน database จำลอง
    const flights = ref([
      // แถวที่ 1
      { id: 1, airline: "Thai Airways", origin: "กรุงเทพฯ (BKK)", destination: "เชียงใหม่ (CNX)", depart: "08:00", arrive: "09:30", price: 2500 },
      { id: 2, airline: "AirAsia", origin: "ดอนเมือง (DMK)", destination: "ภูเก็ต (HKT)", depart: "10:00", arrive: "11:30", price: 1200 },
      { id: 3, airline: "Nok Air", origin: "ดอนเมือง (DMK)", destination: "เชียงราย (CEI)", depart: "14:00", arrive: "15:15", price: 1400 },
      
      // แถวที่ 2
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
/* ใช้ Bootstrap classes ทั้งหมด */
</style>