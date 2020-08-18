<template>
    <div class="masivo">
        <v-btn @click="open=true" outlined color="info">Migración Masiva</v-btn>
        <v-dialog v-model="open" persistent max-width="750">
            <v-card>
                <v-card-title class="headline">Migración Masiva</v-card-title>
                <v-card-text>
                    <v-alert dismissible dense v-model="alert.visible" :color="alert.status" dark transition="scale-transition">{{ alert.message }}</v-alert>
                    
                    <v-row>
                        <v-col md=7>
                            <h5>Migrar A:</h5>
                            <v-row>
                                <v-col md=6>
                                    <v-select
                                        v-model="empresa_id"
                                        label="Empresa"
                                        :items="empresas"
                                        item-text="nombre_empresa"
                                        item-value="id"
                                        outlined
                                        dense
                                        hide-details
                                        ></v-select>
                                </v-col>
                                <v-col md=6>
                                    <v-select
                                        v-model="planilla_id"
                                        label="Planilla"
                                        :items="planillas"
                                        item-text="nombre_planilla"
                                        item-value="id"
                                        outlined
                                        dense
                                        hide-details
                                        ></v-select>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col md=5>
                            <h5>Desde:</h5>
                            <v-row>
                                <v-col col=12>
                                    <v-file-input 
                                        label="Excel:" 
                                        outlined
                                        clearable
                                        dense
                                        accept=".xlsx"
                                        hide-details
                                        @change.native="subirMasivo($event.target.files)"
                                    ></v-file-input>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                    <v-row v-if="datos.length>0&&empresa_id!=null&&planilla_id!=null">
                        <v-col col=12>
                            <h5>Previsualizar:</h5>
                            <v-data-table :headers="header" :items="datos"></v-data-table>
                            <div class="text-right">
                                <v-btn outlined color="primary" @click="guardar()">Guardar</v-btn>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row v-if="datos_repetidos.length>0">
                        <v-col col=12>
                            <h5>Repetidos:</h5>
                            <v-data-table :headers="header" :items="datos_repetidos"></v-data-table>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col col=12 class="text-right">
                            <v-btn outlined color="secondary" @click="resetear()">Cancelar</v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>               
        </v-dialog>
    </div>
</template>
<style>
    .masivo{
        display: inline-block;
    }
</style>
<script>
export default {
    name: 'masivo',
    data() {
        return {
            open: false,
            alert: this.initAlert(),
            datos: [],
            datos_repetidos: [],
            header:[
                { text: 'Codigo', value: 'codigo' },
                { text: 'Nombres', value: 'nombres' },
                { text: 'Apellidos', value: 'apellidos' },
            ],
            empresa_id: null,
            planilla_id: null,
            empresas: [],
            planillas: []
        }
    },
    mounted() {
        axios.get(url_base+'/empresa?all')
        .then(response => {
            this.empresas = response.data;
        });
        axios.get(url_base+'/planilla?all')
        .then(response => {
            this.planillas = response.data;
        });
    },
    methods: {
        initAlert(){
            return {
                status: '',
                visible: false,
                message: ''
            }
        },
        subirMasivo(event){
            this.datos=[];
            this.datos_repetidos=[];
            const XLSX = window.XLSX;
            let file = this.getFile(event);
            let workBook = null;
            let jsonData = null;
            if(file !== null) {
                const reader = new FileReader();
                const rABS = true;
                reader.onload = (event) => {
                    const data = event.target.result; 
                    if(rABS) {
                        workBook = XLSX.read(data, {type: 'binary'});
                        jsonData = workBook.SheetNames.reduce((initial, name) => {
                            const sheet = workBook.Sheets[name];
                            initial[name] = XLSX.utils.sheet_to_json(sheet);
                            return initial;
                        }, {});
                        const dataString = JSON.stringify(jsonData, 2, 2);
                        
                        for (var key1 in jsonData) {
                            console.log(key1);
                            this.datos=jsonData[key1];
                            break;
                        }
                    }
                }
                if(rABS) reader.readAsBinaryString(file);
                else reader.readAsArrayBuffer(file);
            }
        },
        getFile(item) {
            if(item.dataTransfer !== undefined) {
                const dt = item.dataTransfer;
                if(dt.items) {
                if(dt.items[0].kind == 'file') {
                return dt.items[0].getAsFile();
                }
                }
            }
            else {
                return item[0];
            }
        },
        guardar(){
            axios.post(url_base+'/personal/masivo',{
                personales: this.datos,
                empresa_id: this.empresa_id,
                planilla_id:this.planilla_id
            })
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case 'OK':
                        this.alert.status="success";
                        this.alert.visible=true;
                        this.alert.message=respuesta.data;
                        this.datos=[];
                        this.datos_repetidos=respuesta.repetidos;
                        this.$emit('change',1);
                        break;
                    case 'ERROR':
                        this.alert.status="error";
                        this.alert.visible=true;
                        this.alert.message=respuesta.data;
                        break;
                    default:

                        break;
                }
            });
        },
        resetear(){
            this.datos=[];
            this.datos_repetidos=[];
            this.alert=this.initAlert();
            this.open=false;
        }
    },
}
</script>