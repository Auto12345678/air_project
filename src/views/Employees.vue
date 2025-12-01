<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">👔 จัดการข้อมูลพนักงาน</h2>
      <button class="btn btn-primary shadow-sm" @click="openModal('add')">
        <i class="fas fa-user-plus"></i> เพิ่มพนักงาน
      </button>
    </div>

    <!-- ตารางแสดงข้อมูล -->
    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-dark text-white">
              <tr>
                <th class="ps-4">ID</th>
                <th>รูปโปรไฟล์</th>
                <th>ชื่อ-นามสกุล</th>
                <th>ชื่อผู้ใช้ (Username)</th>
                <th class="text-center">จัดการ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="emp in employees" :key="emp.employee_id">
                <td class="ps-4 fw-bold">#{{ emp.employee_id }}</td>
                
                <!-- แสดงรูปภาพ (ใช้ชื่อ field profile_picture) -->
                <td>
                    <div v-if="emp.profile_picture">
                        <img :src="`http://localhost/air_project/uploads/${emp.profile_picture}`" 
                             class="rounded-circle border" 
                             width="100" height="100" 
                             style="object-fit: cover;"
                             @error="$event.target.src='https://via.placeholder.com/50'"
                        >
                    </div>
                    <div v-else class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 50px; font-size: 1.2rem;">
                        <!-- ดึงตัวอักษรแรกของ first_name -->
                        <span>{{ (emp.first_name && emp.first_name.length > 0) ? emp.first_name.charAt(0) : '?' }}</span>
                    </div>
                </td>

                <td>{{ emp.first_name }} {{ emp.last_name }}</td>
                <td><span class="badge bg-light text-dark border">{{ emp.username }}</span></td>
                
                <td class="text-center">
                  <button class="btn btn-warning btn-sm me-2" @click="openModal('edit', emp)">
                    <i class="fas fa-edit"></i> แก้ไข
                  </button>
                  <button class="btn btn-danger btn-sm" @click="deleteEmployee(emp.employee_id)">
                    <i class="fas fa-trash"></i> ลบ
                  </button>
                </td>
              </tr>
              <tr v-if="employees.length === 0">
                <td colspan="5" class="text-center py-4 text-muted">ไม่พบข้อมูลพนักงาน</td>
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

    <!-- Modal เพิ่ม/แก้ไข พนักงาน -->
    <div class="modal fade" id="empModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" :class="isEditMode ? 'bg-warning' : 'bg-primary text-white'">
            <h5 class="modal-title">
                {{ isEditMode ? '✏️ แก้ไขข้อมูลพนักงาน' : '➕ เพิ่มพนักงานใหม่' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveEmployee">
              
              <!-- ส่วนอัปโหลดรูป -->
              <div class="text-center mb-3">
                  <div class="mb-2">
                      <img v-if="previewImage" :src="previewImage" class="rounded-circle border" width="100" height="100" style="object-fit: cover;">
                      <div v-else class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                          <i class="fas fa-camera fa-2x text-muted"></i>
                      </div>
                  </div>
                  <input type="file" class="form-control form-control-sm w-75 mx-auto" @change="handleFileUpload" accept="image/*">
              </div>

              <div class="row mb-3">
                <div class="col-6">
                  <label class="form-label">ชื่อจริง</label>
                  <input v-model="form.first_name" type="text" class="form-control" required>
                </div>
                <div class="col-6">
                  <label class="form-label">นามสกุล</label>
                  <input v-model="form.last_name" type="text" class="form-control" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">ชื่อผู้ใช้ (Username)</label>
                <input v-model="form.username" type="text" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">รหัสผ่าน</label>
                <input v-model="form.password" type="password" class="form-control" 
                       :placeholder="isEditMode ? '(เว้นว่างไว้ถ้าไม่เปลี่ยน)' : 'กำหนดรหัสผ่าน'" 
                       :required="!isEditMode">
              </div>

              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn" :class="isEditMode ? 'btn-warning' : 'btn-primary'">
                    {{ isEditMode ? 'บันทึกการแก้ไข' : 'บันทึกข้อมูล' }}
                </button>
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
  name: "EmployeeList",
  setup() {
    const employees = ref([]);
    const loading = ref(true);
    const form = ref({});
    const isEditMode = ref(false);
    const selectedFile = ref(null);
    const previewImage = ref(null);
    let modalInstance = null;

    // 1. ดึงข้อมูล
    const fetchEmployees = async () => {
      loading.value = true;
      try {
        const res = await axios.get("http://localhost/air_project/api_php/api_employees.php");
        employees.value = res.data;
      } catch (err) {
        console.error(err);
      } finally {
        loading.value = false;
      }
    };

    // 2. เปิด Modal (Add/Edit)
    const openModal = (mode, emp = null) => {
      isEditMode.value = mode === 'edit';
      selectedFile.value = null; 
      
      if (mode === 'edit' && emp) {
        // Copy ข้อมูลมาใส่ Form (ใช้ชื่อ key ตาม DB)
        form.value = { 
            employee_id: emp.employee_id, 
            first_name: emp.first_name,
            last_name: emp.last_name,
            username: emp.username,
            password: '' // เคลียร์รหัสผ่าน
        }; 
        
        // แสดงรูปเดิม
        if (emp.profile_picture) {
            previewImage.value = `http://localhost/air_project/uploads/${emp.profile_picture}`;
        } else {
            previewImage.value = null;
        }
      } else {
        // Reset Form สำหรับเพิ่มใหม่
        form.value = { first_name: '', last_name: '', username: '', password: '' };
        previewImage.value = null;
      }
      
      modalInstance.show();
    };

    // จัดการการเลือกรูป
    const handleFileUpload = (event) => {
        const file = event.target.files[0];
        if (file) {
            selectedFile.value = file;
            previewImage.value = URL.createObjectURL(file);
        }
    };

    // 3. บันทึกข้อมูล
    const saveEmployee = async () => {
        try {
            const formData = new FormData();
            
            // ส่ง employee_id ถ้าเป็นการแก้ไข
            if (isEditMode.value) {
                formData.append('employee_id', form.value.employee_id);
            }

            // ส่งข้อมูลตามชื่อคอลัมน์ของคุณ
            formData.append('first_name', form.value.first_name || '');
            formData.append('last_name', form.value.last_name || '');
            formData.append('username', form.value.username || '');
            formData.append('password', form.value.password || '');

            if (selectedFile.value) {
                formData.append('image', selectedFile.value);
            }

            const res = await axios.post("http://localhost/air_project/api_php/api_employees.php", formData, {
                headers: { "Content-Type": "multipart/form-data" }
            });

            if (res.data.status === 'success') {
                alert(isEditMode.value ? "แก้ไขสำเร็จ!" : "เพิ่มสำเร็จ!");
                modalInstance.hide();
                fetchEmployees();
            } else {
                alert("เกิดข้อผิดพลาด: " + res.data.message);
            }
        } catch (err) {
            alert("เชื่อมต่อ Server ไม่ได้");
        }
    };

    // 4. ลบข้อมูล
    const deleteEmployee = async (id) => {
        if (!confirm("ต้องการลบพนักงานคนนี้?")) return;
        try {
            const res = await axios.delete("http://localhost/air_project/api_php/api_employees.php", {
                data: { employee_id: id } // ส่งเป็น employee_id
            });
            if (res.data.status === 'success') {
                alert("ลบสำเร็จ!");
                // ลบจาก Array โดยเช็ค employee_id
                employees.value = employees.value.filter(e => e.employee_id !== id);
            } else {
                alert("ลบไม่สำเร็จ: " + res.data.message);
            }
        } catch (err) {
            alert("เชื่อมต่อ Server ไม่ได้");
        }
    };

    onMounted(() => {
        fetchEmployees();
        const modalEl = document.getElementById("empModal");
        if (modalEl) modalInstance = new Modal(modalEl);
    });

    return { 
        employees, loading, form, isEditMode, previewImage,
        openModal, handleFileUpload, saveEmployee, deleteEmployee 
    };
  }
};
</script>