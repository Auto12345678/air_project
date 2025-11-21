<template>
  <div class="container my-5">
    <h2 class="text-center mb-4">จองเที่ยวบิน</h2>

    <div class="mb-4 text-center">
      <label class="me-2 fw-bold">จำนวนผู้โดยสาร:</label>
      <select v-model="passengers" class="form-select d-inline-block w-auto">
        <option disabled value="">-- เลือกจำนวน --</option>
        <option v-for="n in 10" :key="n" :value="n">{{ n }} คน</option>
      </select>
    </div>

    <div class="row">
      <div class="col-md-4" v-for="flight in flights" :key="flight.id">
        <div class="card shadow-sm mb-4">
          <div class="card-body text-center">
            <h5 class="card-title">{{ flight.airline }}</h5>
            <p>ออกเดินทาง: {{ flight.depart }}</p>
            <p>ถึงที่หมาย: {{ flight.arrive }}</p>
            <p class="fw-bold text-primary">ราคา: {{ flight.price }} บาท</p>
            
            <button class="btn btn-success" @click="goToLogin(flight.id)">
              จองตั๋วเที่ยวบิน
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
    
    // เพิ่มข้อมูลเที่ยวบินตรงนี้
    const flights = ref([
      // แถวที่ 1
      { id: 1, airline: "Thai Airways", depart: "08:30", arrive: "10:00", price: 2200 },
      { id: 2, airline: "AirAsia", depart: "09:45", arrive: "11:15", price: 1500 },
      { id: 3, airline: "Bangkok Airways", depart: "13:20", arrive: "14:50", price: 2800 },
      
      // แถวที่ 2 (เพิ่มใหม่)
      { id: 4, airline: "Nok Air", depart: "15:30", arrive: "16:45", price: 1450 },
      { id: 5, airline: "Thai Lion Air", depart: "18:00", arrive: "19:15", price: 1350 },
      { id: 6, airline: "Thai Vietjet", depart: "20:15", arrive: "21:30", price: 1200 },
    ]);

    const goToLogin = (flightId) => {
      // ส่งค่า flight_id และ pax ไปด้วย เพื่อให้หน้าถัดไปรู้ว่าเลือกอะไร
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
</style>