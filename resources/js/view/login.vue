<template>
    <v-container fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Sistema Comedor</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    label="Usuario"
                    name="usuario"
                    prepend-icon="mdi-account"
                    type="text"
                    v-model="cuenta.usuario"
                  ></v-text-field>

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    v-model="cuenta.password"
                  ></v-text-field>
                  <div class="text-right">
                        <v-btn color="primary" @click="ingresar">Ingresar</v-btn>
                  </div>
                </v-form>
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
            cuenta: {
                usuario: '',
                password: ''
            }
        }
    },
    methods: {
        ingresar(){
            axios.post(url_base+'/login',this.cuenta)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "ERROR":
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'warn'
                        })
                        break;

                    case "OK":
                        this.$notify({
                            group: 'foo',
                            title: respuesta.data,
                            type: 'success'
                        })
                        this.$store.commit('auth_success', respuesta.data);
                        this.$router.push({path: "/"} );
                        break;

                    default:
                        break;
                }
            });
        }
    },
}
</script>