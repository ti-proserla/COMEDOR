<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="6">
                <v-card>
                    <v-card-title>Registro de Atención</v-card-title>
                    <v-card-text>
                        <form v-on:submit.prevent="guardar()">
                            <v-text-field label="Código de Barras" autofocus v-model="codigo_barras"></v-text-field>
                            <button type="submit" hidden>Submin</button>
                        </form>
                        <v-alert v-model="alert.visible" :color="alert.status" dark transition="scale-transition">{{ alert.message }}</v-alert>
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
            alert: this.initAlert(),
            codigo_barras: '',
            timer: null
        }
    },
    methods: {
        initAlert(){
            return {
                status: '',
                visible: false,
                message: ''
            }
        },
        guardar(){
            if (this.timer) {
                clearTimeout(this.timer);
            }
            if (this.codigo_barras.length==8) {
                var envio={
                    codigo_personal: this.codigo_barras
                };
                this.codigo_barras='';
                axios.post(url_base+'/pedido',envio)
                .then(response => {
                    var respuesta=response.data;
                    switch (respuesta.status) {
                        case 'OK':
                            this.alert.status= 'primary';
                            this.alert.visible= true;
                            this.alert.message= respuesta.data;
                            break;
                        case 'ERROR':
                            this.alert.status= 'danger';
                            this.alert.visible= true;
                            this.alert.message= respuesta.data;
                            break;
                        default:
                            this.alert.status= 'warning',
                            this.alert.visible= true;
                            this.alert.message=respuesta.data;
                            break;
                    }
                    this.timer=setTimeout(() => {
                        this.alert=this.initAlert();
                    }, 10000);
                });
            }else{
                this.alert.status= 'danger';
                this.alert.visible= true;
                this.alert.message= 'Código Incorrecto';
                this.timer=setTimeout(() => {
                    this.alert=this.initAlert();
                }, 20000);
            }
        }
    },
}
</script>