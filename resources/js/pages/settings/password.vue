<template lang="">
  
    <v-row>
        <v-col cols="8"> 
          <v-card elevation="0">
            <v-card-title>{{$t('your_password')}}</v-card-title>

            <v-card-text>
              <v-form @submit.prevent="update" @keydown="form.onKeydown($event)" >
                  <!-- <v-text-field 
                      v-model="form.password"
                      :label="$t('password')" 

                      :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                      :type="show1 ? 'text' : 'password'"

                      :error-messages="form.errors.get('password')"

                      outlined dense
                      @click:append="show1 = !show1"
                      
                  ></v-text-field>

                  <v-text-field 
                      v-model="form.password_confirmation"
                      :label="$t('confirm_password')" 

                      :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                      :type="show2 ? 'text' : 'password'"

                      outlined dense
                      @click:append="show2 = !show2"
                  ></v-text-field> -->

                  <custom-input 
                    type="password" 
                    :label="$t('password')" 
                    v-model="form.password"  
                    :errors="inputErrors['password'] ? inputErrors['password'] : []"
                  />


                  <custom-input 
                    type="password" 
                    :label="$t('confirm_password')" 
                    v-model="form.password_confirmation"  
                    :errors="inputErrors['password_confirmation'] ? inputErrors['password_confirmation'] : []"
                  />
                  <div class="d-flex align-center">
                    <custom-button color="success" type="submit">{{ $t('update') }}</custom-button>
                    <transition name="fade" mode="out-in">
                        <span v-if="form.success" class="ml-2 text-success">{{$t('password_updated')}}</span> 
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

export default {
  scrollToTop: false,

  metaInfo () {
    return { title: this.$t('settings') }
  },

  data: () => ({
    form: new Form({
      password: '',
      password_confirmation: '',
      success: false
    }),
    inputErrors: [],
  }),

  metaInfo () {
    return { title: this.$t('settings') }
  },

  methods: {
    async update () {
        try {
            await this.form.patch('/api/settings/password')

            this.form.reset()     
            this.success()       
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
  },
}
</script>
<style>
    .text-success {
        color: #4caf50;
    }
</style>