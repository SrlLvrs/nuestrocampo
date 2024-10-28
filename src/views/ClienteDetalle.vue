<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->

    <!-- Detalle Cliente -->
    <div class="prose max-w-none">
        <h1 class="text-center p-4 m-0">Información del cliente</h1>
    </div>
    <div class="overflow-x-auto">
        <table class="table">
            <!-- Head -->
            <thead>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Frecuencia</th>
                    <th>Teléfono</th>
                    <th>Teléfono 2</th>
                    <th>Día de reparto</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <tr v-for="item in items" :key="item.IDCliente">
                    <th>
                        <div v-if="item.Deuda" class="text-red-500 tooltip tooltip-right"
                            data-tip="Cliente con deuda pendiente">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                    </th>
                    <th> {{ item.Nombre }}</th>
                    <td> {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</td>
                    <td> {{ item.Frecuencia }} </td>
                    <td> {{ item.Telefono }}</td>
                    <td> {{ item.Telefono2 }}</td>
                    <td> {{ item.Dia_de_Reparto }}</td>
                    <td> {{ item.Observacion }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pedidos pendientes de pago -->
    <div v-if="pendientes && pendientes.length > 0">
        <div class="prose max-w-none mt-8">
            <h1 class="text-center p-4 m-0">Pedidos pendientes de pago</h1>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <!-- Head -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estado</th>
                        <th>Pagado</th>
                        <th>Medio de Pago</th>
                        <th>Hora y Fecha de Entrega</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- Body -->
                <tbody>
                    <tr v-for="item in pendientes" :key="item.ID">
                        <th> {{ item.ID }}</th>
                        <td> {{ item.Estado }}</td>
                        <td> {{ item.Pagado }}</td>
                        <td> {{ item.MedioPago }}</td>
                        <td> {{ item.FechaEntrega }}</td>
                        <td>
                            <!-- Detalle Pedido -->
                            <DetallePedidoMinimal :label="item.ID + 'detail'" :id="item.ID" />

                            <!-- Pagar Pedido -->
                            <PagarPedidoEfectivo :id="item.ID" :label="item.ID + 'efectivo'" />
                            <PagarPedidoTransferencia :id="item.ID" :label="item.ID + 'transferencia'" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-else-if="pendientes">
        <div class="prose max-w-none mt-8">
            <h1 class="text-center p-4 m-0">Pedidos pendientes de pago</h1>
            <p class="text-center">No se encontraron pedidos pendientes de pago para este cliente.</p>
        </div>
    </div>

    <!-- Próximos pedidos -->
    <div v-if="futuros && futuros.length > 0">
        <div class="prose max-w-none mt-8">
            <h1 class="text-center p-4 m-0">Próximos pedidos</h1>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <!-- Head -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estado</th>
                        <th>Pagado</th>
                        <th>Medio de Pago</th>
                        <th>Fecha de Entrega</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- Body -->
                <tbody>
                    <tr v-for="item in futuros" :key="item.ID">
                        <th> {{ item.ID }}</th>
                        <td> {{ item.Estado }}</td>
                        <td> {{ item.Pagado }}</td>
                        <td> {{ item.MedioPago }}</td>
                        <td> {{ item.FechaEntrega }}</td>
                        <td>
                            <!-- Detalle Pedido -->
                            <DetallePedidoMinimal :label="item.ID + 'next'" :id="item.ID" />

                            <!-- Editar Pedido -->
                            <EditarPedido :label="item.ID + 'edit'" :id="item.ID" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-else-if="futuros">
        <div class="prose max-w-none mt-8">
            <h1 class="text-center p-4 m-0">Próximos pedidos</h1>
            <p class="text-center">No hay próximos pedidos programados para este cliente.</p>
        </div>
    </div>

    <!-- Pedidos anteriores -->
    <div v-if="pasados && pasados.length > 0">
        <div class="prose max-w-none mt-8">
            <h1 class="text-center p-4 m-0">Pedidos pasados</h1>
        </div>
        <div class="overflow-x-auto">
            <table class="table">
                <!-- Head -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estado</th>
                        <th>Pagado</th>
                        <th>Medio de Pago</th>
                        <th>Fecha de Entrega</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- Body -->
                <tbody>
                    <tr v-for="item in pasados" :key="item.ID">
                        <th> {{ item.ID }}</th>
                        <td> {{ item.Estado }}</td>
                        <td> {{ item.Pagado }}</td>
                        <td> {{ item.MedioPago }}</td>
                        <td> {{ item.FechaEntrega }}</td>
                        <td>
                            <!-- Detalle Pedido -->
                            <DetallePedidoMinimal :label="item.ID + 'past'" :id="item.ID" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-else-if="pasados">
        <div class="prose max-w-none mt-8">
            <h1 class="text-center p-4 m-0">Pedidos pasados</h1>
            <p class="text-center mb-8">Este cliente no tiene pedidos anteriores registrados.</p>
        </div>
    </div>

</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import DetallePedidoMinimal from "../components/DetallesPedidoMinimal.vue";
import PagarPedidoEfectivo from "../components/PagarPedidoEfectivo.vue";
import PagarPedidoTransferencia from "../components/PagarPedidoTransferencia.vue";
import EditarPedido from "../components/EditarPedido.vue";

export default {
    //Nombre del componente
    name: "DetalleCliente",

    props: {
        id: Number,
    },

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
            pendientes: [],
            futuros: [],
            pasados: [],
        };
    },

    methods: {
        async getInfoCliente() {
            //REVISAR ESTOS 4 ENDPOINTS
            const urls = [
                `https://nuestrocampo.cl/api/clientes/read-detail.php?id=${this.id}`,
                `https://nuestrocampo.cl/api/pedidos/read-debt.php?id=${this.id}`,
                `https://nuestrocampo.cl/api/pedidos/read-next.php?id=${this.id}`,
                `https://nuestrocampo.cl/api/pedidos/read-past.php?id=${this.id}`,
            ];

            const promises = urls.map(url => axios.get(url));

            try {
                const resultados = await Promise.allSettled(promises);
                resultados.forEach((resultado, indice) => {
                    if (resultado.status === 'fulfilled') {
                        // Si la promesa se resolvió correctamente
                        if (indice === 0) {
                            this.items = resultado.value.data;
                        } else if (indice === 1) {
                            this.pendientes = resultado.value.data;
                        } else if (indice === 2) {
                            this.futuros = resultado.value.data;
                        } else if (indice === 3) {
                            this.pasados = resultado.value.data;
                        }
                    } else {
                        // Si la promesa fue rechazada
                        console.error(`Error en el endpoint ${urls[indice]}`, resultado.reason);
                    }
                });
            } catch (error) {
                console.error(error);
            }
        },
    },

    computed: {
        //Nada por aqui
    },

    components: { DetallePedidoMinimal, PagarPedidoEfectivo, PagarPedidoTransferencia, EditarPedido },

    mounted() {
        this.getInfoCliente()
    },
}
</script>
