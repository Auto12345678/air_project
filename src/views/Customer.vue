<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">👥 จัดการข้อมูลลูกค้า</h2>
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
                <th>Username</th>
                <th class="text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="cus in customers" :key="cus.customer_id">
                <td class="ps-4 fw-bold">#{{ cus.customer_id }}</td>
                <td>{{ cus.firstname }}</td>
                <td>{{ cus.lastname }}</td>
                <td>{{ cus.email }}</td>
                <td>{{ cus.tel }}</td>
                <td>{{ cus.username }}</td>
                <td class="text-center">
                  <button class="btn btn-warning btn-sm me-2" @click="openEditModal(cus)">
                    <i class="fas fa-edit"></i> แก้ไข
                  </button>
                  <button class="btn btn-danger btn-sm" @click="deleteCustomer(cus.customer_id)">
                    <i class="fas fa-trash"></i> ลบ
                  </button>
                </td>
              </tr>
              <tr v-if="customers.length === 0">
                <td colspan="7" class="text-center py-4 text-muted">ไม่พบข้อมูลลูกค้า</td>
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

    <!-- Modal สำหรับแก้ไขข้อมูล -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title">✏️ แก้ไขข้อมูลลูกค้า</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

              <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label">อีเมล</label>
                    <input v-model="editForm.email" type="email" class="form-control" required>
                </div>
                <div class="col-6">
                    <label class="form-label">เบอร์โทร</label>
                    <input v-model="editForm.tel" type="text" class="form-control" required>
                </div>
              </div>

              <hr>
              
              <div class="mb-3">
                <label class="form-label fw-bold">ชื่อผู้ใช้ (Username)</label>
                <input v-model="editForm.username" type="text" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold text-danger">รหัสผ่านใหม่</label>
                <input v-model="editForm.password" type="password" class="form-control" placeholder="(เว้นว่างไว้ถ้าไม่เปลี่ยน)">
              </div>
              
              <div class="d-flex justify-content-end mt-4">
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
import { Modal } from "bootstrap"; 

export default {
  name: "Customer",
  setup() {
    const customers = ref([]);
    const loading = ref(true);
    const editForm = ref({});
    let editModalInstance = null;

    const fetchCustomers = async () => {
      loading.value = true;
      try {
        const res = await axios.get("http://localhost/air_project/api_php/api_customer.php");
        customers.value = res.data;
      } catch (err) {
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    const openEditModal = (customer) => {
      // Copy ข้อมูลมาใส่ Form และเคลียร์ Password
      editForm.value = { 
          ...customer, 
          password: '' 
      };
      editModalInstance.show();
    };

    const updateCustomer = async () => {
      try {
        // ✅ สร้าง Payload ใหม่ เพื่อให้แน่ใจว่าส่ง customer_id ไปแน่นอน
        const payload = {
            customer_id: editForm.value.customer_id, // บังคับส่ง ID
            firstname: editForm.value.firstname,
            lastname: editForm.value.lastname,
            email: editForm.value.email,
            tel: editForm.value.tel,
            username: editForm.value.username,
            password: editForm.value.password // ส่งไป (ถ้าว่าง PHP จะข้ามเอง)
        };

        const res = await axios.put("http://localhost/air_project/api_php/api_customer.php", payload);
        
        if (res.data && res.data.status === 'success') {
          alert("แก้ไขข้อมูลสำเร็จ!");
          editModalInstance.hide();
          fetchCustomers();
        } else {
          // ถ้ามี Error จะแสดงข้อความจาก PHP (หรือ Unknown ถ้าไม่มี)
          const msg = res.data && res.data.message ? res.data.message : 'Unknown Error';
          alert("เกิดข้อผิดพลาด: " + msg);
        }
      } catch (err) {
        console.error(err);
        alert("เชื่อมต่อ Server ไม่ได้");
      }
    };

    const deleteCustomer = async (id) => {
      if (!confirm("ยืนยันการลบลูกค้า ID: " + id + " ?")) return;

      try {
        const res = await axios.delete("http://localhost/air_project/api_php/api_customer.php", {
            data: { customer_id: id }
        });
        
        if (res.data.status === 'success') {
          alert("ลบข้อมูลสำเร็จ!");
          customers.value = customers.value.filter(c => c.customer_id !== id);
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
</style>