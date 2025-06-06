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
        },
        {
          path: '/sucursal',
          name: 'Sucursal',
          component: () => import('@/views/mnt/sucursal/Cuerpo.vue'),
          meta: {titulo: 'Sucursal'}
        },
        {
          path: '/producto',
          name: 'Producto',
          component: () => import('@/views/producto/Principal.vue'),
          meta: {titulo: 'Producto'}
        },
        {
          path: '/um',
          name: 'UnidadMedida',
          component: () => import('@/views/producto/um/Principal.vue'),
          meta: {titulo: 'Unidad de medida'}
        },
        {
          path: '/marca',
          name: 'Marca',
          component: () => import('@/views/producto/marca/Principal.vue'),
          meta: {titulo: 'Marca'}
        },
        {
          path: '/categoria',
          name: 'Categoria',
          component: () => import('@/views/producto/categoria/Principal.vue'),
          meta: {titulo: 'Categoría'}
        },
        {
          path: '/cliente',
          name: 'Cliente',
          component: () => import('@/views/mnt/cliente/Principal.vue'),
          meta: {titulo: 'Cliente'}
        },
        {
          path: '/proveedor',
          name: 'Proveedor',
          component: () => import('@/views/mnt/proveedor/Principal.vue'),
          meta: {titulo: 'Proveedor'}
        },
        {
          path: '/usuario',
          name: 'Usuario',
          component: () => import('@/views/mnt/usuario/Principal.vue'),
          meta: {titulo: 'Usuario'}
        },
        {
          path: '/moneda',
          name: 'Moneda',
          component: () => import('@/views/mnt/moneda/Principal.vue'),
          meta: {titulo: 'Moneda'}
        },
        {
          path: '/producto_sucursal',
          name: 'ProductoSucursal',
          component: () => import('@/views/producto/producto_sucursal/Principal.vue'),
          meta: {titulo: 'Producto Sucursal'}
        },
        {
          path: '/compra',
          name: 'OrdenCompra',
          component: () => import('@/views/compra/Principal.vue'),
          meta: {titulo: 'Orden de Compra'}
        }
      ]
    },
    { 
      path: '/:pathMatch(.*)*', 
      component: () => import('../views/PageError.vue') 
    }
  ],
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
