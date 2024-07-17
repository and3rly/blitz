import { createRouter, createWebHistory } from "vue-router";
import { useLoginStore } from "@/stores/app-login";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { 
      path: "/login",
      name: "Login",
      component: () => import("@/views/sesion/Login.vue")
    },
    {
      path: "/",
      component: () => import("@/views/Principal.vue"), 
      children: [
        { 
          path: '/inicio',
          name: 'Inicio',
          component: () => import('@/views/Inicio.vue'),
          meta: {titulo: 'Inicio'}
        }
      ]
    },
    { 
      path: '/:pathMatch(.*)*', 
      component: () => import('../views/PageError.vue') 
    }
  ]
});

router.beforeEach((to, from, next) => {

  const store = useLoginStore();

  if (to.meta.titulo) {
    document.title = "Blitz  - " + to.meta.titulo;
  } else {
    document.title = "Blitz";
  }

  if (to.name !== 'Login' && !store.isLoggedIn) {
    next({name:'Login'});
  } else if (to.name == 'Login' && store.isLoggedIn) {
      next({name:'Inicio'});
  } else {
    next();
  }

});

export default router;
