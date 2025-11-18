import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },

 {
    path: '/show',
    name: 'show',
    component: () => import('../views/ShowProduct.vue')
  },

  {
    path: '/custom',
    name: 'custom',
    component: () => import('../views/Customer.vue')
  },

  {
    path: '/add_custom',
    name: 'add_custom',
    component: () => import('../views/Add_customer.vue')
  },
  
  {
    path: '/product',
    name: 'product',
    component: () => import('../views/Product.vue')
  },

  {
    path: '/add_product',
    name: 'add_product',
    component: () => import('../views/Add_product.vue')
  },

  {
    path: '/edit',
    name: 'edit',
    component: () => import('../views/Edit_customer.vue')
  },

  {
    path: '/edit_product',
    name: 'edit_product',
    component: () => import('../views/product_edit.vue')
  },

  {
    path: '/employees',
    name: 'employees',
    component: () => import('../views/Employees.vue')
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

]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

// ✅ Navigation Guard
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
