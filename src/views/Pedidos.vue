<template>
    <!-- Usar la clase prose para estilos, y max-w-none para usar el ancho completo -->
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Todos los pedidos</h1>
            <div class="flex justify-center mb-4 items-center gap-2">
                <!-- Selector de fechas -->
                <VCalendar v-model="fechaSeleccionada" />
                
                <!-- Botón Buscar -->
                <button @click="getPedidos" class="btn btn-outline btn-primary" :disabled="isLoading">
                    {{ isLoading ? 'Buscando...' : 'Buscar' }}
                    <span v-if="isLoading" class="loading loading-spinner loading-sm"></span>
                </button>
                
                <!-- Botón Excel -->
                <Excel :items="items" />
            </div>
        </div>
        <div class="flex justify-center">
        <!-- INPUT FILTRAR -->
        <label class="input input-bordered flex items-center gap-2">
            <input v-model="filterText" type="text" class="grow" placeholder="Nombre, dirección, sector o comuna..." />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                class="h-4 w-4 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>
    </div>
    </div>
    <!-- RESULTADOS -->
    <div class="overflow-x-auto">
        <table v-if="items.length > 0" class="table table-zebra">
            <!-- Encabezado -->
            <thead>
                <tr>
                    <th></th>
                    <th>ID de pedido</th>
                    <th>Cliente</th>
                    <th>Direccion</th>
                    <th>Estado</th>
                    <th>Repartidor</th>
                    <th>Pagado</th>
                    <th>Medio de Pago</th>
                    <th>Observacion</th>
                    <th>Fecha de Entrega</th>
                    <th>Hora de Creación</th>
                    <th>Hora de Cierre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>
                <tr v-for="item in filteredItems" :key="item.ID">
                    <th>
                        <div v-if="item.IDPA" class="tooltip tooltip-right"
                            data-tip="Pedido creado automáticamente">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0V7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </div>
                    </th>
                    <th> {{ item.ID }}</th>
                    <th> {{ item.Nombre }}</th>
                    <th> {{ item.Direccion }}, {{ item.NombreSector }}, {{ item.Comuna }}</th>
                    <td> {{ item.Estado }}</td>
                    <td> {{ item.NombreRepartidor }}</td>
                    <td> {{ item.Pagado }}</td>
                    <td> {{ item.MedioPago }}</td>
                    <td> {{ item.Observacion }}</td>
                    <td> {{ item.FechaEntrega }}</td>
                    <td> {{ item.HoraCreacion }}</td>
                    <td> {{ item.HoraCierre }}</td>
                    <td>
                        <div class="flex gap-2">
                            <!-- Detalle Pedido -->
                            <DetallePedido :label="item.ID + 'detail'" :id="item.ID" />

                            <!-- Editar Pedido (No se muestra si el rol es repartidor) -->
                            <EditarPedido v-if="this.rol != 'Repartidor'" :label="item.ID + 'edit'" :id="item.ID" />

                            <!-- Eliminar Pedido (No se muestra si el rol es repartidor) -->
                            <EliminarPedido v-if="this.rol != 'Repartidor'" :id="item.ID" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-else class="flex justify-center items-center h-screen">
            <p class="text-center">No se encontraron pedidos con el filtro de búsqueda actual</p>
        </div>
        
        <!-- Nuevo calendario y botón para cambiar fecha de reparto -->
        <div class="flex justify-center items-center mt-8 gap-2">
            <VCalendar v-model="nuevaFechaReparto" />
            
            <button class="btn btn-outline btn-primary" @click="cambiarFechaReparto" :disabled="isLoading">
                {{ isLoading ? 'Cambiando...' : 'Cambiar fecha de reparto' }}
                <span v-if="isLoading" class="loading loading-spinner loading-sm"></span>
            </button>
        </div>
    </div>
</template>

<script>
//Para usar axios, primero hay que instalarlo usando: 'npm install axios'
import axios from "axios";
import EliminarPedido from "../components/EliminarPedido.vue";
import DetallePedido from "../components/DetallesPedido.vue";
import EditarPedido from "../components/EditarPedido.vue";
import Excel from "../components/Excel.vue";
import VCalendar from "../components/VCalendar.vue";

export default {
    //Nombre del componente
    name: "Pedidos",

    data() {
        return {
            //Array para guardar datos de la API
            items: [],
            rol: '',
            fechaSeleccionada: new Date(),
            nuevaFechaReparto: new Date(),
            isLoading: false,
            filterText: '', // Añadido para el filtro
        };
    },

    computed: {
        filteredItems() {
            return this.items.filter(item => {
                const normalizedFilterText = this.filterText.toLowerCase();
                return (
                    item.Nombre.toLowerCase().includes(normalizedFilterText) ||
                    item.Direccion.toLowerCase().includes(normalizedFilterText) ||
                    item.NombreSector.toLowerCase().includes(normalizedFilterText) ||
                    item.Comuna.toLowerCase().includes(normalizedFilterText)
                );
            });
        },
    },

    methods: {
        formatearFecha(fecha) {
            let d = new Date(fecha);
            let year = d.getFullYear();
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return `${year}-${month}-${day}`;
        },

        formatearFechaHora(fecha) {
            let d = new Date(fecha);
            let year = d.getFullYear();
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();
            let hours = '' + d.getHours();
            let minutes = '' + d.getMinutes();
            let seconds = '' + d.getSeconds();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;
            if (hours.length < 2)
                hours = '0' + hours;
            if (minutes.length < 2)
                minutes = '0' + minutes;
            if (seconds.length < 2)
                seconds = '0' + seconds;

            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        },

        async getPedidos() {
            this.isLoading = true;
            let fechaFormateada = this.formatearFecha(this.fechaSeleccionada);
            let url = `https://nuestrocampo.cl/api/pedidos/read.php?fecha=${fechaFormateada}`;
            try {
                const response = await axios.get(url);
                this.items = response.data;
            } catch (error) {
                console.error("Error al obtener pedidos:", error);
                alert('Hubo un error al buscar los pedidos');
            } finally {
                this.isLoading = false;
            }
        },

        async cambiarFechaReparto() {
            this.isLoading = true;
            const fechaFormateada = this.formatearFechaHora(this.nuevaFechaReparto);
            const errores = [];
            
            for (const item of this.filteredItems) {
                try {
                    const url = `https://nuestrocampo.cl/api/pedidos/update_fecha_reparto.php?id=${item.ID}&nuevaFecha=${encodeURIComponent(fechaFormateada)}`;
                    const response = await axios.get(url);
                    
                    if (response.data.success) {
                        item.FechaEntrega = fechaFormateada;
                    } else {
                        throw new Error(`No se pudo actualizar la fecha para el pedido ${item.ID}`);
                    }
                } catch (error) {
                    console.error(`Error al cambiar la fecha del pedido ${item.ID}:`, error);
                    errores.push(item.ID);
                }
            }
            
            this.isLoading = false;
            
            if (errores.length === 0) {
                alert('Todas las fechas de reparto se actualizaron con éxito');
            } else {
                alert(`Se actualizaron algunas fechas, pero hubo errores con los pedidos: ${errores.join(', ')}`);
            }
        }
    },

    mounted() {
        const sessionData = JSON.parse(localStorage.getItem('authUser'));
        if (sessionData) {
            console.log('Sesión iniciada. Montando...');
            this.rol = sessionData.rol;
            this.getPedidos();
        } else {
            console.log('No hay sesión iniciada. Redireccionando a login');
            this.$router.push({ name: 'LogIn' });
        }
    },

    components: { EliminarPedido, DetallePedido, EditarPedido, Excel, VCalendar },
}
</script>

