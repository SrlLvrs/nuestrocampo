import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", component: () => import("../views/Inicio.vue")},
        { path: "/clientes", component: () => import("../views/Clientes.vue")},
        { path: "/pedidos", component: () => import("../views/Pedidos.vue")},
        { path: "/produccion", component: () => import("../views/Produccion.vue")},
        { path: "/admin", component: () => import("../views/Admin.vue")},
        { path: "/sectores", component: () => import("../views/Sectores.vue")}
    ],
});

export default router;