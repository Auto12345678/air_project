<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">👥 จัดการข้อมูลลูกค้า</h2>
      <!-- ปุ่ม Add ลิงก์ไปหน้าเพิ่มลูกค้า (เหมือนเดิม) -->
      <router-link to="/add_custom" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> เพิ่มลูกค้าใหม่
      </router-link>
    </div>

    <!-- ตารางแสดงข้อมูล -->
    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-primary text-white">
              <tr>
                <th class="ps-4">ID</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>อีเมล</th>
                <th>เบอร์โทร</th>
                <th>ชื่อผู้ใช้</th>
                <th>รหัส</th>
                <th class="text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="cus in customers" :key="cus.customer_id">
                <td class="ps-4 fw-bold">#{{ cus.customer_id }}</td>
                <td>{{ cus.firstname }}</td>
                <td>{{ cus.lastname }}</td>
                <td>{{ cus.email }}</td>
                <td>{{ cus.phone }}</td>
                <td>{{ cus.username }}</td>
                <td>{{ cus.password }}</td>
                <td class="text-center">
                  <!-- ปุ่มแก้ไข (เปิด Modal) -->
                  <button class="btn btn-warning btn-sm me-2" @click="openEditModal(cus)">
                    <i class="fas fa-edit"></i> แก้ไข
                  </button>
                  <!-- ปุ่มลบ -->
                  <button class="btn btn-danger btn-sm" @click="deleteCustomer(cus.customer_id)">
                    <i class="fas fa-trash"></i> ลบ
                  </button>
                </td>
              </tr>
              <tr v-if="customers.length === 0">
                <td colspan="6" class="text-center py-4 text-muted">ไม่พบข้อมูลลูกค้า</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-center mt-4">
      <div class="spinner-border text-primary" role="status"></div>
      <p>กำลังโหลดข้อมูล...</p>
    </div>

    <!-- Modal สำหรับแก้ไขข้อมูล (Bootstrap 5) -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title">✏️ แก้ไขข้อมูลลูกค้า</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateCustomer">
              <div class="row mb-3">
                <div class="col-6">
                  <label class="form-label">ชื่อ</label>
                  <input v-model="editForm.firstname" type="text" class="form-control" required>
                </div>
                <div class="col-6">
                  <label class="form-label">นามสกุล</label>
                  <input v-model="editForm.lastname" type="text" class="form-control" required>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input v-model="editForm.email" type="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">เบอร์โทร</label>
                <input v-model="editForm.phone" type="text" class="form-control" required>
              </div>
              
              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
// import Modal จาก bootstrap เพื่อใช้ควบคุมการเปิด/ปิด
import { Modal } from "bootstrap"; 

export default {
  name: "Customer",
  setup() {
    const customers = ref([]);
    const loading = ref(true);
    const editForm = ref({}); // ตัวแปรเก็บข้อมูลที่จะแก้ไข
    let editModalInstance = null; // ตัวแปรเก็บ object ของ Modal

    // 1. ดึงข้อมูลลูกค้า
    const fetchCustomers = async () => {
      loading.value = true;
      try {
        const res = await axios.get("http://localhost/air_project/api_php/api_customer.php");
        customers.value = res.data;
      } catch (err) {
        console.error(err);
        alert("ไม่สามารถดึงข้อมูลได้");
      } finally {
        loading.value = false;
      }
    };

    // 2. เปิด Modal แก้ไข
    const openEditModal = (customer) => {
      // Copy ข้อมูลจากแถวที่เลือกมาใส่ฟอร์ม
      editForm.value = { ...customer };
      // สั่งเปิด Modal
      editModalInstance.show();
    };

    // 3. บันทึกการแก้ไข (Update)
    const updateCustomer = async () => {
      try {
        const res = await axios.post("http://localhost/air_project/api_php/api_edit_customer.php", editForm.value);
        
        if (res.data.status === 'success') {
          alert("แก้ไขข้อมูลสำเร็จ!");
          editModalInstance.hide(); // ปิด Modal
          fetchCustomers(); // โหลดข้อมูลใหม่
        } else {
          alert("เกิดข้อผิดพลาด: " + res.data.message);
        }
      } catch (err) {
        console.error(err);
        alert("เชื่อมต่อ Server ไม่ได้");
      }
    };

    // 4. ลบข้อมูล (Delete)
    const deleteCustomer = async (id) => {
      if (!confirm("คุณต้องการลบข้อมูลลูกค้านี้ใช่หรือไม่?")) return;

      try {
        const res = await axios.post("http://localhost/air_project/api_php/api_delete_customer.php", { id: id });
        
        if (res.data.status === 'success') {
          alert("ลบข้อมูลสำเร็จ!");
          // ลบออกจากหน้าเว็บทันที ไม่ต้องรอโหลดใหม่
          customers.value = customers.value.filter(c => c.id !== id);
        } else {
          alert("ลบไม่สำเร็จ: " + res.data.message);
        }
      } catch (err) {
        console.error(err);
        alert("เชื่อมต่อ Server ไม่ได้");
      }
    };

    onMounted(() => {
      fetchCustomers();
      
      // เริ่มต้น Modal เมื่อหน้าเว็บโหลดเสร็จ
      const modalEl = document.getElementById("editModal");
      if (modalEl) {
        editModalInstance = new Modal(modalEl);
      }
    });

    return {
      customers,
      loading,
      editForm,
      openEditModal,
      updateCustomer,
      deleteCustomer
    };
  }
};
</script>

<style scoped>
/* เพิ่ม CSS เพิ่มเติมได้ที่นี่ */
</style>