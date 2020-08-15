<template>
    <component :is="layout">
        <router-view></router-view>
    </component>
</template>
<script>
var default_layouts="panel";

export default {
    computed:{
        layout(){
            console.log(this.$route.meta.layout);
            return (this.$route.meta.layout || default_layouts);
        }
    },
}
</script>