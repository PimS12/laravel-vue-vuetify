<template lang="">
  
    <v-row>
        <v-col cols="8"> 
          <v-card elevation="0">
            <v-card-title>{{$t('your_info')}}</v-card-title>

            <v-card-text>
              <v-form @submit.prevent="update" @keydown="form.onKeydown($event)" >

                  <custom-input 
                    v-model="form.name"
                    :label="$t('name')" 
                    type="text" 
                    :errors="inputErrors['name'] ? inputErrors['name'] : []"
                  />

                  <custom-input 
                    v-model="form.email"
                    :label="$t('email')" 
                    type="email" 
                    :errors="inputErrors['email'] ? inputErrors['email'] : []"
                  />

                  <div class="d-flex align-center">
                    <custom-button color="success" type="submit">{{ $t('update') }}</custom-button>
                    <transition name="fade" mode="out-in">
                        <span v-if="form.success" class="ml-2 text-success">{{$t('info_updated')}}</span> 
                    </transition>                   
                  </div>
              </v-form>                   
            </v-card-text>
       
          </v-card>

        </v-col>
    </v-row>
</template>
<script>
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      name: '',
      email: '',
      success: false
    }), 

    inputErrors: [],
  }),

  computed: {
         ...mapGetters({
            user: 'auth/user'
        }), 
    },

  created () {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key]
    })
  },

  methods: {
    async update () {
      try {
        const { data } = await this.form.patch('/api/settings/profile')
        
        this.success()
        this.$store.dispatch('auth/updateUser', { user: data }) 

      } catch (error) {

        this.inputErrors = error.response.data.errors

      }

    },
    success () {
        this.form.success = true;
        
        this.inputErrors = [];

        setTimeout(() => {
            this.form.success = false
        }, 3000);
    }
  }

}
</script>
<style lang="">
    
</style>