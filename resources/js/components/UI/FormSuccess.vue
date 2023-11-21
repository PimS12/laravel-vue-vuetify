<template lang="">
    <transition name="fade" mode="out-in">
        <span v-if="success" :key="success" class="ml-2 text-success">
            <slot></slot>
        </span> 
    </transition>  
</template>
<script>
export default {
    name: 'form-success',

    props: {
        success: {
            type: Boolean,
            default: false
        }
    },

    success () {
        setTimeout(() => {
            this.success = false
        }, 3000);
    }
}
</script>
<style lang="">
    
</style>