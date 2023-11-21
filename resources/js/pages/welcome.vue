<template>

</template>

<script>
import { mapGetters } from 'vuex'

export default {
  layout: 'basic',

  metaInfo () {
    return { title: this.$t('home') }
  },

  data: () => ({
    title: window.config.appName
  }),

  computed: mapGetters({
    authenticated: 'auth/check'
  }),

  methods: {
    redirect() {
        if (this.authenticated) {
          this.$router.push({ name: 'home' })
        } else {
          this.$router.push({ name: 'login' })
        }      
    }
  },

  mounted () {
    this.redirect()
  }
}
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}

.title {
  font-size: 85px;
}
</style>
