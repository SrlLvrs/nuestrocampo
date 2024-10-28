import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "Inicio",
            component: () => import("../views/Inicio.vue"),
        },
        {
            path: "/iniciorepartidor",
            name: "InicioRepartidor",
            component: () => import("../views/Inicio-Repartidor.vue"),
        },
        {
            path: "/despacharcamion",
            name: "DespacharCamion",
            component: () => import("../views/Despachar-Camion.vue"),
        },
        { 
            path: "/clientes",
            name: "Clientes", 
            component: () => import("../views/Clientes.vue")
        },
        { 
            path: "/clientes/detalle/:id", 
            name: "ClienteDetalle",
            component: () => import("../views/ClienteDetalle.vue"),
            props: true
        },
        { 
            path: "/clientes/deudores", 
            name: "ClientesDeudores",
            component: () => import("../views/Clientes-Deudores.vue") 
        },
        {
            path: "/resumen",
            name: "Resumen",
            component: () => import("../views/Resumen.vue"),
        },
        { 
            path: "/productos", 
            name: "Productos",
            component: () => import("../views/Productos.vue") 
        },
        { 
            path: "/pedidos",
            name: "Pedidos", 
            component: () => import("../views/Pedidos.vue") 
        },
        { 
            path: "/pedidos/automaticos",
            name: "PedidosAutomáticos", 
            component: () => import("../views/Pedidos-auto.vue") 
        },
        { 
            path: "/pedidos/hoy", 
            name: "PedidosHoy",
            component: () => import("../views/Pedidos-hoy.vue") 
        },
        {
            path: "/pedidos/ordenar",
            name: "PedidosOrdenar",
            component: () => import("../views/OrdenarPedidos.vue"),
        },
        {
            path: "/pedidos/aprobar",
            name: "PedidosAprobar",
            component: () => import("../views/Pedidos-Aprobar.vue"),
        },
        { 
            path: "/produccion", 
            name: "Produccion",
            component: () => import("../views/Produccion.vue") 
        },
        { 
            path: "/admin",
            name: "AdministrarUsuarios", 
            component: () => import("../views/Admin.vue") 
        },
        { 
            path: "/sectores",
            name: "Sectores", 
            component: () => import("../views/Sectores.vue") 
        },
        {
            path: "/repartidor",
            name: "Repartidor",
            component: () => import("../views/Repartidor.vue"),
        },
        {
            path: "/login",
            name: "LogIn",
            component: () => import("../views/LogIn.vue"),
        },
    ],
});

export default router;