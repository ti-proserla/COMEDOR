<template>
    <v-container fluid>
        <v-row justify="center">
            <v-col cols="12">
                <v-card>
                    <v-card-title>Servicios</v-card-title>
                    <v-card-text>
                        <v-btn @click="open_nuevo=true" outlined color="info">Nuevo Servicio</v-btn>
                        <!-- Nuevo -->
                        <v-dialog v-model="open_nuevo" persistent max-width="350">
                            <v-card>
                                <v-card-title class="headline">Nuevo Servicio</v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field 
                                                label="Nombre del servicio" 
                                                v-model="servicio.nombre_servicio"
                                            ></v-text-field>
                                            <v-text-field 
                                                label="Hora inicio" 
                                                v-model="servicio.inicio"
                                                type="time"
                                            ></v-text-field>
                                            <v-text-field 
                                                label="Hora Final" 
                                                v-model="servicio.fin"
                                                type="time"
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
                                <v-card-title class="headline">Modificar servicio</v-card-title>
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field 
                                                required 
                                                label="Nombre de la servicio" 
                                                v-model="servicio_editar.nombre_servicio"
                                            ></v-text-field>
                                            <v-text-field 
                                                label="Hora inicio" 
                                                v-model="servicio_editar.inicio"
                                                type="time"
                                            ></v-text-field>
                                            <v-text-field 
                                                label="Hora Final" 
                                                v-model="servicio_editar.fin"
                                                type="time"
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
                { text: 'servicio', value: 'nombre_servicio' },
                { text: 'Hora Inicio', value: 'inicio' },
                { text: 'Hora Final', value: 'fin' },
                { text: 'Editar', value: 'editar' },
            ],
            table: {
                current_page: 1,
                last_page: 1,
                data: []
            },
            servicio:  this.initServicio(),
            servicio_editar:  this.initServicio()
        }
    },
    mounted() {
        this.listar(1);
    },
    methods: {
        initServicio(){
            return {
                nombre_servicio:  ''
            }
        },
        guardar(){
            axios.post(url_base+'/servicio',this.servicio)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case 'OK':
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'success'
                        })
                        this.servicio=this.initServicio();
                        this.open_nuevo=false;
                        this.listar();
                        break;
                
                    default:

                        break;
                }
            });
        },
        listar(n=this.table.current_page){
            axios.get(url_base+'/servicio?page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        buscar(id){
            axios.get(url_base+'/servicio/'+id)
            .then(response => {
                this.open_editar=true;
                this.servicio_editar = response.data;
            })
        },
        actualizar(){
            axios.post(url_base+'/servicio/'+this.servicio_editar.id+'?_method=PUT',this.servicio_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case 'OK':
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'success'
                        })
                        this.servicio_editar=this.initServicio();
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