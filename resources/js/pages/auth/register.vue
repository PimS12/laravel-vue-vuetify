<template>
    <v-container>
      <v-row justify="center">
        <v-col cols="12" sm="8" md="4">
          <v-card
            elevation="2"
            outlined
          >
            <v-card-title class="headline text-center">{{$t('register')}}</v-card-title>
  
            <v-card-text>
              <v-form @submit.prevent="register" @keydown="form.onKeydown($event)">

                <custom-input 
                    v-model="form.name" 
                    :label="$t('name')" 
                    :errors="inputErrors['name'] ? inputErrors['name'] : []"
                    type="text" 
                />
                <custom-input 
                    v-model="form.email" 
                    :label="$t('email')" 
                    :errors="inputErrors['email'] ? inputErrors['email'] : []"
                    type="email" 
                />

                <custom-input
                    v-model="form.password" 
                    :label="$t('password')"
                    :errors="inputErrors['password'] ? inputErrors['password'] : []"
                    type="password" 
                />

                <custom-input
                    v-model="form.password_confirmation" 
                    :label="$t('confirm_password')"
                    :errors="inputErrors['password_confirmation'] ? inputErrors['password_confirmation'] : []"
                    type="password" 
                />
                
                <custom-button
                  color="#FA845C"
                  :loading="form.busy"
                  type="submit"
                  class="white--text"
                >
                  {{$t('register')}}
                </custom-button>
                <!-- <v-btn color="primary" type="submit">{{$t('register')}}</v-btn> -->
              </v-form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </template>
    
  <script>
    import Form from 'vform'
    import LoginWithGithub from '~/components/LoginWithGithub'

    export default {
    components: {
        LoginWithGithub
    },

    middleware: 'guest',

    metaInfo () {
        return { title: this.$t('register') }
    },

    data: () => ({
        form: new Form({
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
        }),
        mustVerifyEmail: false,
        inputErrors: [],
    }),

    methods: {
      async register() {
        try {
          // Register the user.
          const { data } = await this.form.post('/api/register')

          // Must verify email fist.
          if (data.status) {
            this.mustVerifyEmail = true
          } else {
            // Log in the user.
            const { data: { token } } = await this.form.post('/api/login')

            // Save the token.
            this.$store.dispatch('auth/saveToken', { token })

            // Update the user.
            await this.$store.dispatch('auth/updateUser', { user: data })

            // Redirect home.
            this.$router.push({ name: 'home' })
          }
        } catch (error) {
          this.inputErrors = error.response.data.errors
        }

      }
    }
    }
  </script>