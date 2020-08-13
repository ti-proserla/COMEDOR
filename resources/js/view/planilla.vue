<template>
    <v-container fluid>
        <v-row justify="center">
            <v-col cols="12">
                <v-card>
                    <v-card-title>Planillas</v-card-title>
                    <v-card-text>
                        <v-btn @click="open_nuevo=true" outlined color="info">Nueva Planilla</v-btn>
                        <!-- Nuevo -->
                        <v-dialog v-model="open_nuevo" persistent max-width="350">
                            <v-card>
                                <v-card-title class="headline">Nueva Planilla</v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field 
                                                required 
                                                label="Nombre de la planilla" 
                                                v-model="planilla.nombre_planilla"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols=12 class="text-right">
                                            <v-btn outlined color="secondary" @click="open_nuevo=false">Cancelar</v-btn>
                                            <v-btn outlined color="primary" @click="guardar()">Guardar</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>               
                        </v-dialog>
                        <!-- editar -->
                        <v-dialog v-model="open_editar" persistent max-width="350">
                            <v-card>
                                <v-card-title class="headline">Modificar Planilla</v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field 
                                                required 
                                                label="Nombre de la planilla" 
                                                v-model="planilla_editar.nombre_planilla"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols=12 class="text-right">
                                            <v-btn outlined color="secondary" @click="open_editar=false">Cancelar</v-btn>
                                            <v-btn outlined color="primary" @click="actualizar()">Guardar</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>               
                        </v-dialog>
                        <v-data-table
                            :headers="header"
                            :items="table.data"
                            :page.sync="table.current_page"
                            hide-default-footer
                            >
                            <template v-slot:item.editar="{ item }">
                                <v-btn text color="warning" @click="buscar(item.id)"><i class="far fa-edit"></i></v-btn>
                            </template>
                        </v-data-table>
                        <v-pagination v-model="table.current_page" :length="table.last_page" circle @input="listar"></v-pagination>    
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
            open_nuevo: false,
            open_editar: false,
            header:[
                { text: 'planilla', value: 'nombre_planilla' },
                { text: 'Editar', value: 'editar' },
            ],
            table: {
                current_page: 1,
                last_page: 1,
                data: []
            },
            planilla:  this.initPlanilla(),
            planilla_editar:  this.initPlanilla()
        }
    },
    mounted() {
        this.listar(1);
    },
    methods: {
        initPlanilla(){
            return {
                nombre_planilla:  ''
            }
        },
        guardar(){
            axios.post(url_base+'/planilla',this.planilla)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case 'OK':
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'success'
                        })
                        this.planilla=this.initPlanilla();
                        this.open_nuevo=false;
                        this.listar();
                        break;
                
                    default:

                        break;
                }
            });
        },
        listar(n=this.table.current_page){
            axios.get(url_base+'/planilla?page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        buscar(id){
            axios.get(url_base+'/planilla/'+id)
            .then(response => {
                this.open_editar=true;
                this.planilla_editar = response.data;
            })
        },
        actualizar(){
            axios.post(url_base+'/planilla/'+this.planilla_editar.id+'?_method=PUT',this.planilla_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case 'OK':
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'success'
                        })
                        this.planilla_editar=this.initPlanilla();
                        this.open_editar=false;
                        this.listar();
                        break;
                
                    default:

                        break;
                }
            });
        },
    },
}
</script>