<template>
    <v-container>
        <v-data-table
            :headers="header"
            :items="table.data"
            :page.sync="table.current_page"
            hide-default-footer
            ></v-data-table>
        <v-pagination v-model="table.current_page" :length="table.last_page" circle @input="buscar"></v-pagination>
    </v-container>
</template>
<script>
export default {
    data() {
        return {
            header:[
                { text: 'Código', value: 'dni' },
                { text: 'Apellidos', value: 'ape_operador' },
            ],
            table: {
                current_page: 1,
                last_page: 5,
                data: [
                    {
                        dni: '77382978',
                        nombres: 'Diego Francisco',
                        apellidos: 'Mendoza Frias'
                    },
                    {
                        dni: '77382978',
                        nombres: 'Diego Francisco',
                        apellidos: 'Mendoza Frias'
                    },
                    {
                        dni: '77382978',
                        nombres: 'Diego Francisco',
                        apellidos: 'Mendoza Frias'
                    },
                    {
                        dni: '77382978',
                        nombres: 'Diego Francisco',
                        apellidos: 'Mendoza Frias'
                    },
                    {
                        dni: '77382978',
                        nombres: 'Diego Francisco',
                        apellidos: 'Mendoza Frias'
                    },
                ]
            }
        }
    },
    mounted() {
        this.buscar(1);
    },
    methods: {
        buscar(n){
            // axios.get(url_base+'/operador?page='+n+'&search='+this.search)
            axios.get('http://172.16.1.112:8081/api/operador?page='+n)
            .then(response => {
                this.table = response.data;
            })
        }
    },
}
</script>