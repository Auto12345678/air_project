<template>
  <div class="container my-5">
    <h2 class="text-center mb-4">✈️ จองเที่ยวบินออนไลน์</h2>
    
    <div class="mb-4 text-center">
       <label class="fw-bold">ผู้โดยสาร:</label>
       <select v-model="passengers" class="form-select d-inline-block w-auto ms-2">
          <option v-for="n in 5" :key="n" :value="n">{{ n }} คน</option>
       </select>
    </div>

    <div class="row">
      <div class="col-md-4" v-for="flight in flights" :key="flight.id">
        <div class="card shadow-sm mb-4">
          <div class="card-body text-center">
            <h5 class="card-title text-primary">{{ flight.airline }}</h5>
            <p>เวลา: {{ flight.depart }} - {{ flight.arrive }}</p>
            <h4 class="fw-bold">{{ flight.price }} บาท</h4>
            <button class="btn btn-success w-100 mt-2" @click="bookFlight(flight)">
              จองเลย ({{ flight.price * passengers }} บาท)
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="showModal" class="modal d-block" style="background: rgba(0,0,0,0.5);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">💰 ชำระเงิน (PromptPay)</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body text-center">
            <p>รหัสการจอง: <strong>{{ currentBookingInfo.id }}</strong></p>
            <p>สายการบิน: {{ currentBookingInfo.airline }}</p>
            <h3 class="text-danger fw-bold">ยอดรวม: {{ currentBookingInfo.price }} บาท</h3>
            
            <div class="my-3">
                <img :src="qrCodeUrl" alt="QR Code Payment" class="img-thumbnail" width="200">
            </div>
            <p class="text-muted small">กรุณาสแกนเพื่อชำระเงิน</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="showModal = false">ยกเลิก</button>
            <button type="button" class="btn btn-primary" @click="confirmPayment">แจ้งชำระเงินเรียบร้อย</button>
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
  setup() {
    const router = useRouter();
    const passengers = ref(1);
    
    // ตัวแปรสำหรับจัดการ Modal และ QR Code
    const showModal = ref(false);
    const qrCodeUrl = ref("");
    const currentBookingInfo = ref({});

    const flights = ref([
      { id: 1, airline: "Thai Airways", depart: "08:00", arrive: "09:30", price: 2500 },
      { id: 2, airline: "AirAsia", depart: "10:00", arrive: "11:30", price: 1200 },
      { id: 3, airline: "Nok Air", depart: "14:00", arrive: "15:15", price: 1400 },
      { id: 4, airline: "Thai Lion Air", depart: "15:30", arrive: "16:45", price: 1350 },
      { id: 5, airline: "Thai Vietjet", depart: "18:00", arrive: "19:15", price: 1200 },
      { id: 6, airline: "Bangkok Airways", depart: "20:15", arrive: "21:30", price: 2800 },
    ]);

    const bookFlight = async (flight) => {
      const totalPrice = flight.price * passengers.value;
      
      try {
        // 1. ยิง API บันทึกการจองลง Database ก่อน
        const res = await axios.post("http://localhost/air_project/api_php/api_booking.php", {
           flight_id: flight.id,
           airline: flight.airline,
           depart: flight.depart,
           passengers: passengers.value,
           total_price: totalPrice
        });

        if (res.data.status === "success") {
           // 2. เตรียมข้อมูลสำหรับแสดงใน Modal
           currentBookingInfo.value = {
             id: res.data.booking_id,
             airline: flight.airline,
             price: totalPrice
           };

           // 3. สร้าง URL สำหรับ QR Code (ในที่นี้จำลองสร้าง QR Text ธรรมดา)
           // หากต้องการ PromptPay จริง ต้องใช้ Library 'promptpay-qr' สร้าง payload แทน text นี้
           const qrData = `BOOKING-ID:${res.data.booking_id}-PRICE:${totalPrice}`;
           qrCodeUrl.value = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrData}`;

           // 4. เปิด Modal
           showModal.value = true;
        }
      } catch (error) {
        console.error(error);
        alert("Error: เชื่อมต่อ Server ไม่ได้");
      }
    };

    // ฟังก์ชันเมื่อกดปุ่ม "แจ้งชำระเงินเรียบร้อย"
    const confirmPayment = () => {
       // ปิด Modal
       showModal.value = false;
       
       // ไปหน้า Payment หรือหน้า Success โดยส่ง ID ไป
       router.push({ 
          path: '/payment', 
          query: { 
            id: currentBookingInfo.value.id, 
            price: currentBookingInfo.value.price 
          } 
       });
    };

    return { 
      passengers, 
      flights, 
      bookFlight, 
      showModal, 
      qrCodeUrl, 
      currentBookingInfo, 
      confirmPayment 
    };
  }
};
</script>