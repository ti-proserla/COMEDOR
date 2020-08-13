<template>
    <v-app>
        <v-navigation-drawer
            :expand-on-hover="expandOnHover"
            :permanent="permanent"
            :src="bg"
            absolute
            dark
            app
        >
            <v-list
            dense
            nav
            class="py-0"
            >
            <v-list-item two-line :class="miniVariant && 'px-0'">
                <v-list-item-avatar>
                <img src="https://randomuser.me/api/portraits/men/81.jpg">
                </v-list-item-avatar>

                <v-list-item-content>
                <v-list-item-title>Application</v-list-item-title>
                <v-list-item-subtitle>Subtext</v-list-item-subtitle>
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
                <v-list-item link to="/empresa">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Empresas
                    </v-list-item-content>
                </v-list-item>

                <v-list-item link to="/planilla">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        planilla
                    </v-list-item-content>
                </v-list-item>
                
                <v-list-item link to="/personal">
                    <v-list-item-icon>
                        <i class="far fa-building"></i>
                    </v-list-item-icon>
                    <v-list-item-content>
                        Personal
                    </v-list-item-content>
                </v-list-item>

            </v-list>
        </v-navigation-drawer>
        <v-main>
            <notifications group="foo" position="top center"/>
            <router-view></router-view>
        </v-main>
    </v-app>
</template>
<style>
    .v-navigation-drawer{
        position: fixed;
    }
</style>