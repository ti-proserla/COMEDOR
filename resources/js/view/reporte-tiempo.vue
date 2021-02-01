<template>
    <v-container fluid>
        <v-row justify="center">
            <v-col cols="12">
                <v-card>
                    <v-card-title>Reporte por Tiempo Servicio</v-card-title>
                    <v-card-text>
                        <v-row>
        
                            <v-col cols=12 md=4>
                                <v-btn outlined color="primary" @click="buscar()"><i class="fas fa-search"></i> Buscar</v-btn>
                                <v-btn outlined color="success" :href="excel()" ><i class="far fa-file-excel"></i> Descargar</v-btn>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
export default {
    data() {
        return {
            header:[
                { text: 'Código', value: 'codigo' },
                { text: 'Nombres', value: 'nombres' },
                { text: 'Apellidos', value: 'apellidos' },
                { text: 'Fecha', value: 'fecha' },
                { text: 'Servicio', value: 'nombre_servicio' }
            ],
            table: [],
            empresas: [],
            busqueda: {
                inicio: moment().format('YYYY-MM-DD'),
                fin: moment().format('YYYY-MM-DD'),
                codigo: ''
            }
        }
    },
    mounted() {
        // axios.get(url_base+'/empresa?all')
        // .then(response => {
        //     this.empresas = response.data;
        //     this.busqueda.empresa_id=this.empresas[0].id;
        // })
        // this.listarServicios();
    },
    methods: {
        // listarServicios(){
        //     axios.get(url_base+'/servicio?all')
        //     .then(response => {
        //         var servicios = response.data;
        //         for (let i = 0; i < servicios.length; i++) {
        //             const servicio = servicios[i];
        //             this.header.push({ text: servicio.nombre_servicio, value: servicio.nombre_servicio.replace(" ", "_") },);
        //         }
        //     })
        // },
        // buscar(){
        //     axios.get(url_base+'/reporte/personal?codigo='+this.busqueda.codigo+'&inicio='+this.busqueda.inicio+'&fin='+this.busqueda.fin)
        //     .then(response => {
        //         this.table = response.data;
        //     })
        // },
        excel(){
            return url_base+'/reporte/tiempo?excel'
        }
    },
}
</script>