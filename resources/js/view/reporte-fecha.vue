<template>
    <v-container fluid>
        <v-row justify="center">
            <v-col cols="12">
                <v-card>
                    <v-card-title>Reporte por Fecha</v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols=12 md=3>
                                <v-text-field 
                                    type="date"
                                    label="Fecha Inicio" 
                                    v-model="busqueda.inicio"
                                    outlined
                                    dense
                                    ></v-text-field>
                            </v-col>
                            <v-col cols=12 md=3>
                                <v-text-field 
                                    type="date"
                                    label="Fecha Fin" 
                                    v-model="busqueda.fin"
                                    outlined
                                    dense
                                    ></v-text-field>
                            </v-col>
                            <v-col cols=12 md=2>
                                <v-select
                                    v-model="busqueda.empresa_id"
                                    label="Empresa"
                                    :items="empresas"
                                    outlined
                                    dense
                                    item-text="nombre_empresa"
                                    item-value="id">
                                    </v-select>
                            </v-col>
                            <v-col cols=12 md=4>
                                <v-btn outlined color="primary" @click="buscar()"><i class="fas fa-search"></i> Buscar</v-btn>
                                <v-btn outlined color="success" :href="excel()" ><i class="far fa-file-excel"></i> Descargar</v-btn>
                            </v-col>
                        </v-row>
                        <v-data-table
                            :headers="header"
                            :items="table"
                            ></v-data-table>
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
            ],
            table: [],
            empresas: [],
            busqueda: {
                inicio: moment().format('YYYY-MM-DD'),
                fin: moment().format('YYYY-MM-DD')
            }
        }
    },
    mounted() {
        axios.get(url_base+'/empresa?all')
        .then(response => {
            this.empresas = response.data;
            this.busqueda.empresa_id=this.empresas[0].id;
        })
        this.listarServicios();
    },
    methods: {
        listarServicios(){
            axios.get(url_base+'/servicio?all')
            .then(response => {
                var servicios = response.data;
                for (let i = 0; i < servicios.length; i++) {
                    const servicio = servicios[i];
                    this.header.push({ text: servicio.nombre_servicio, value: servicio.nombre_servicio.replace(" ", "_") },);
                }
            })
        },
        buscar(){
            axios.get(url_base+'/reporte/fecha?empresa_id='+this.busqueda.empresa_id+'&inicio='+this.busqueda.inicio+'&fin='+this.busqueda.fin)
            .then(response => {
                this.table = response.data;
            })
        },
        excel(){
            return url_base+'/reporte/fecha?excel&empresa_id='+this.busqueda.empresa_id+'&inicio='+this.busqueda.inicio+'&fin='+this.busqueda.fin
        }
    },
}
</script>