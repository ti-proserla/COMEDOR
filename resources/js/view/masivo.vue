<template>
    <div class="masivo">
        <v-btn @click="open=true" outlined color="info">Migración Masiva</v-btn>
        <v-dialog v-model="open" persistent max-width="450">
            <v-card>
                <v-card-title class="headline">Migración Masiva</v-card-title>
                <v-card-text>
                    <v-file-input 
                        label="Excel:" 
                        outlined
                        dense
                        clearable
                        accept=".xlsx"
                        @change.native="subirMasivo($event.target.files)"
                    ></v-file-input>
                    <v-data-table :headers="header" :items="datos"></v-data-table>
                    <div class="text-right">
                        <v-btn outlined color="secondary" @click="open=false">Cancelar</v-btn>
                        <!-- <v-btn outlined color="primary" @click="guardar()">Guardar</v-btn> -->
                    </div>
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
            datos: [],
            header:[
                { text: 'Codigo', value: 'codigo' },
                { text: 'Nombres', value: 'nombres' },
                { text: 'Apellidos', value: 'apellidos' },
            ],
        }
    },
    methods: {
        subirMasivo(event){
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
    },
}
</script>