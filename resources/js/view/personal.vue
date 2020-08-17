<template>
    <v-container fluid>
        <v-row justify="center">
            <v-col cols="12">
                <v-card>
                    <v-card-title>Personal</v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols=12 md=6>
                                <v-btn @click="open_nuevo=true" outlined color="info">Nuevo personal</v-btn>
                                <masivo></masivo>
                            </v-col>
                            <v-col cols=10 md=6>
                                <v-text-field 
                                        label="Buscar:" 
                                        v-model="search"
                                        outlined
                                        dense
                                        clearable
                                        @keyup.native="listar()"
                                    ></v-text-field>
                            </v-col>
                        </v-row>
                        <!-- Nuevo -->
                        <v-dialog v-model="open_nuevo" persistent max-width="350">
                            <v-card>
                                <v-card-title class="headline">Nueva personal</v-card-title>
                                <v-card-text>
                                    <v-text-field 
                                        label="Código" 
                                        v-model="personal.codigo"
                                        :error-messages="error.codigo"
                                    ></v-text-field>
                                    <v-text-field 
                                        required 
                                        label="Nombres" 
                                        v-model="personal.nombres"
                                        :error-messages="error.nombres"
                                    ></v-text-field>
                                    <v-text-field 
                                        required 
                                        label="Apellidos" 
                                        v-model="personal.apellidos"
                                        :error-messages="error.apellidos"
                                    ></v-text-field>
                                    <v-select
                                        v-model="personal.empresa_id"
                                        label="Empresa"
                                        :items="empresas"
                                        item-text="nombre_empresa"
                                        item-value="id"
                                        :error-messages="error.empresa_id">
                                        </v-select>
                                    <v-select
                                        v-model="personal.planilla_id"
                                        label="Planilla"
                                        :items="planillas"
                                        item-text="nombre_planilla"
                                        item-value="id"
                                        :error-messages="error.planilla_id">
                                        </v-select>
                                    <div class="text-right">
                                        <v-btn outlined color="secondary" @click="open_nuevo=false">Cancelar</v-btn>
                                        <v-btn outlined color="primary" @click="guardar()">Guardar</v-btn>
                                    </div>
                                </v-card-text>
                            </v-card>               
                        </v-dialog>
                        <!-- editar -->
                        <v-dialog v-model="open_editar" persistent max-width="350">
                            <v-card>
                                <v-card-title class="headline">Modificar personal</v-card-title>
                                <v-card-text>
                                    {{personal_editar.codigo}}
                                    <v-text-field 
                                        required 
                                        label="Nombres" 
                                        v-model="personal_editar.nombres"
                                        :error-messages="error_editar.nombres"
                                    ></v-text-field>
                                    <v-text-field 
                                        required 
                                        label="Apellidos" 
                                        v-model="personal_editar.apellidos"
                                        :error-messages="error_editar.apellidos"
                                    ></v-text-field>
                                    <v-select
                                        v-model="personal_editar.empresa_id"
                                        label="Empresa"
                                        :items="empresas"
                                        item-text="nombre_empresa"
                                        item-value="id"
                                        :error-messages="error_editar.empresa_id"
                                        ></v-select>
                                    <v-select
                                        v-model="personal_editar.planilla_id"
                                        label="Planilla"
                                        :items="planillas"
                                        item-text="nombre_planilla"
                                        :error-messages="error_editar.planilla_id"
                                        item-value="id">
                                        </v-select>
                                    <div class="text-right">
                                        <v-btn outlined color="secondary" @click="open_editar=false">Cancelar</v-btn>
                                        <v-btn outlined color="primary" @click="actualizar()">Guardar</v-btn>
                                    </div>
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
                { text: 'Nombres', value: 'nombres' },
                { text: 'Apellidos', value: 'apellidos' },
                { text: 'Editar', value: 'editar' },
            ],
            table: {
                current_page: 1,
                last_page: 1,
                data: []
            },
            search: '',
            empresas: [],
            planillas: [],
            personal:  this.initPersonal(),
            personal_editar:  this.initPersonal(),
            error: {

            },
            error_editar: {

            }
        }
    },
    mounted() {
        this.listar(1);
        axios.get(url_base+'/empresa?all')
        .then(response => {
            this.empresas = response.data;
        })
        axios.get(url_base+'/planilla?all')
        .then(response => {
            this.planillas = response.data;
        })
    },
    methods: {
        initPersonal(){
            return {
                codigo:  '',
                nombres:  '',
                apellidos:  '',
                planilla_id:  '',
                empresa_id:  ''
            }
        },
        guardar(){
            axios.post(url_base+'/personal',this.personal)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case 'OK':
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'success'
                        })
                        this.personal=this.initPersonal();
                        this.open_nuevo=false;
                        this.listar();
                        break;
                    case 'VALIDATION':
                        this.error=respuesta.data;
                        break;
                    case 'ERROR':
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'warn'
                        })
                        break;
                    default:

                        break;
                }
            });
        },
        listar(n=this.table.current_page){
            axios.get(url_base+'/personal?page='+n+'&search='+this.search)
            .then(response => {
                this.table = response.data;
            })
        },
        buscar(id){
            axios.get(url_base+'/personal/'+id)
            .then(response => {
                this.open_editar=true;
                this.personal_editar = response.data;
            })
        },
        actualizar(){
            axios.post(url_base+'/personal/'+this.personal_editar.id+'?_method=PUT',this.personal_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case 'OK':
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'success'
                        })
                        this.personal_editar=this.initPersonal();
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