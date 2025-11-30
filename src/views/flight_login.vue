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
              <span class="fw-bold small">{{ flight.origin }}</span>
              <span class="mx-2 text-muted">➝</span>
              <span class="fw-bold small">{{ flight.destination }}</span>
            </div>

            <p class="text-muted mb-2">เวลา: {{ flight.depart }} - {{ flight.arrive }}</p>
            <h4 class="fw-bold text-dark">{{ flight.price }} บาท</h4>
            
            <button class="btn btn-success w-100 mt-2" @click="bookFlight(flight)">
              จองเลย ({{ (flight.price * passengers).toLocaleString() }} บาท)
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal แจ้งเตือนสำเร็จ (ไม่มี QR) -->
    <div v-if="showModal" class="modal d-block" style="background: rgba(0,0,0,0.5);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">✅ ทำรายการจองสำเร็จ!</h5>
            <button type="button" class="btn-close btn-close-white" @click="showModal = false"></button>
          </div>
          <div class="modal-body text-center pt-4">
            <h1 class="mb-3">🎫</h1>
            <h4 class="fw-bold">Booking ID: <span class="text-primary">{{ currentBookingInfo.id }}</span></h4>
            
            <div class="alert alert-light border mt-3 text-start d-inline-block" style="min-width: 80%;">
                <p class="mb-1"><strong>สายการบิน:</strong> {{ currentBookingInfo.airline }}</p>
                <p class="mb-0"><strong>ยอดชำระ:</strong> <span class="text-danger fw-bold">{{ Number(currentBookingInfo.price).toLocaleString() }} บาท</span></p>
            </div>
            <p class="text-muted mt-3">กรุณากดปุ่มด้านล่างเพื่อชำระเงิน</p>
          </div>
          <div class="modal-footer flex-nowrap">
            <button type="button" class="btn btn-secondary w-50" @click="showModal = false">ปิด</button>
            <button type="button" class="btn btn-primary w-50 fw-bold" @click="goToPaymentPage">
              ไปหน้าชำระเงิน ➝
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