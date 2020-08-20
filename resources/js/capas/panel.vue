<template>
    <v-app>
        <v-app-bar app
         absolute
         >
            <v-app-bar-nav-icon @click="open=true"></v-app-bar-nav-icon>
            <v-toolbar-title>SISTEMA COMEDOR</v-toolbar-title>
        </v-app-bar>
        <v-navigation-drawer
            absolute
            dark
            app
            v-model="open"
        >
            <v-list
            dense
            nav
            class="py-0"
            >
            <v-list-item two-line>
                <v-list-item-avatar>
                    <img src="http://172.16.1.112:80/storage/logotipo.png">
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>{{ cuenta.nombre.toUpperCase()+' '+cuenta.apellido.toUpperCase() }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
                <v-list-item link to="/">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Inicio
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="existe('/atencion')" link to="/atencion">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Atención
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="existe('/empresa')" link to="/empresa">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Empresas
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="existe('/planilla')" link to="/planilla">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Planilla
                    </v-list-item-content>
                </v-list-item>
                
                <v-list-item v-if="existe('/personal')" link to="/personal">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Personal
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="existe('/servicio')" link to="/servicio">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Servicio
                    </v-list-item-content>
                </v-list-item>

                <v-list-item v-if="existe('/reporte-fecha')" link to="/reporte-fecha">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Reporte por Fecha
                    </v-list-item-content>
                </v-list-item>
                <v-list-item v-if="existe('/reporte-personal')" link to="/reporte-personal">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Reporte por Personal
                    </v-list-item-content>
                </v-list-item>
                <v-list-item-content>
                    <v-btn color="error" text small @click="cerrar">Cerrar Sistema</v-btn>
                </v-list-item-content>
            </v-list>
        </v-navigation-drawer>
        
        <v-main>
            <notifications group="foo" position="top center"/>
            <slot/>
        </v-main>
    </v-app>
</template>
<style>
    .v-navigation-drawer{
        position: fixed;
        z-index: 10;
    }
</style>
<script>
import { mapState,mapMutations } from 'vuex'

export default {
    data() {
        return {
            open: true
        }
    },
    computed: {
    ...mapState(['cuenta','rutas']),
    },
    methods: {
        cerrar(){
            this.$store.commit('auth_close');
            this.$router.push({path: "/login"} );
        },
        existe(rol){
            return this.rutas.indexOf(rol)>-1;
        }
    },
}
</script>