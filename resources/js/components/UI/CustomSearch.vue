<template>
    <v-text-field
        :color="color"
        v-model="searchQuery"
        prepend-inner-icon="mdi-magnify"
        :label="label"
        :placeholder="label"
        single-line
        hide-details
        clearable
        outlined
        dense
    />
</template>
<script>
export default {
    name: 'custom-search',

    props: {
        color: {
            type: String,
            default: '#FA845C'
        },
        label: {
            type: String,
            default: 'Search'
        },
        query : {
            type: String,
            default: ''
        }
    },
    computed: {
        searchQuery: {
            get() {
                return this.query;
            },
            set(newQuery) {
                // Вы можете добавить дополнительную логику здесь, если необходимо
                this.$emit('input', newQuery);
            }
        }
    }


}
</script>
<style>
    
</style>