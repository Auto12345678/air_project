import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/customer',
    name: 'customer',
    component: () => import('../views/Customer.vue')
  },
  {
    path: '/add_custom',
    name: 'add_custom',
    component: () => import('../views/Add_customer.vue')
  },
  {
    path: '/edit',
    name: 'edit',
    component: () => import('../views/Edit_customer.vue')
  },
  {
    path: '/employees',
    name: 'employees',
    component: () => import('../views/customer_detail.vue')
  },
  {
    path: '/login_employee',
    name: 'login_employee',
    component: () => import('../views/login_employee.vue')
  },
  {
    path: '/login_customer',
    name: 'login_customer',
    component: () => import('../views/login_customer.vue')
  },
  {
    path: '/Register',
    name: 'Register',
    component: () => import('../views/Register.vue')
  },
  {
    path: '/no_air',
    name: 'no_air',
    component: () => import('../views/flight_noLogin.vue')
  },
  {
    path: '/log_air',
    name: 'log_air',
    component: () => import('../views/flight_login.vue')
  },
  
  // ✅ แก้ไขตรงนี้ครับ เปลี่ยนจาก '/pay' เป็น '/payment'
  {
    path: '/payment', 
    name: 'payment',
    component: () => import('../views/PaymentPage.vue')
  },

  {
    path: '/check', 
    name: 'check',
    component: () => import('../views/check_status.vue')
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

// ✅ Navigation Guard (เหมือนเดิม)
router.beforeEach((to, from, next) => {
  const role = localStorage.getItem("role"); // 'admin' | 'customer' | null
  const isLoggedIn = !!role; // true ถ้ามีการล็อกอิน

  // ✅ ถ้า route ต้องล็อกอิน
  if (to.meta.requiresAuth) {
    if (!isLoggedIn) {
      alert("⚠ กรุณาเข้าสู่ระบบก่อนใช้งานหน้านี้");
      if (to.meta.role === "admin") next("/login_admin");
      else next("/login");
      return;
    }

    // ✅ ถ้ามีการล็อกอินแต่ role ไม่ตรง (ป้องกันเข้า page ผิด)
    if (to.meta.role && to.meta.role !== role) {
      alert("⛔ คุณไม่มีสิทธิ์เข้าหน้านี้");
      next("/");
      return;
    }
  }

  // ✅ ป้องกันไม่ให้กลับไปหน้า login ถ้าเข้าสู่ระบบแล้ว
  if ((to.path === "/login" || to.path === "/login_admin") && isLoggedIn) {
    next("/");
    return;
  }

  next();
});

export default router;