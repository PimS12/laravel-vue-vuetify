<template>
    <v-btn
        :fab="fab"
        :small="fab"
        :color="color"
        :loading="loading"
        :disabled="loading"
        :type="type"
        :to="to"

        @click="$emit('click')"
    >
        <slot></slot>
    </v-btn>
</template>
<script>
export default {
    name: 'custom-button',
    props: {
        color: {
            type: String,
            default: 'primary'
        },
        loading: {
            type: Boolean,
            default: false
        },
        type: {
            type: String
        },
        fab: {
            type: Boolean,
            default: false
        },
        to: {
            type: Object,
            default: null
        }
    }, 
    // methods: {
    //     onClick() {
    //         this.$emit('click')
    //     }
    // }
}
</script>
<style lang="">
    
</style>