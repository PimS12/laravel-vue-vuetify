<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card
          elevation="2"
          outlined
        >
          <v-card-title class="headline text-center">{{$t('login')}}</v-card-title>

          <v-card-text>
            <v-form @submit.prevent="login" @keydown="form.onKeydown($event)">

              <custom-input 
                v-model="form.email"
                :label="$t('email')" 
                type="email"
                :errors="inputErrors['email'] ? inputErrors['email'] : []"
              />

              <custom-input 
                v-model="form.password"
                :label="$t('password')" 
                type="password"
                :errors="inputErrors['password'] ? inputErrors['password'] : []"
              />

              <router-link :to="{ name: 'password.request' }" class="small ms-auto my-auto">
                {{ $t('forgot_password') }}
              </router-link>

              <v-checkbox
                v-model="remember"
                color="#FA845C"
                :label="$t('remember_me')"
              ></v-checkbox>

              <custom-button
                color="#FA845C"
                :loading="form.busy"
                type="submit"
                class="white--text"
              >
                {{$t('login')}}
              </custom-button>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
  
<script>
  import Cookies from 'js-cookie'
  import Form from 'vform'

  export default {
    middleware: 'guest',
    data: () => ({
      form: new Form({
        email: '',
        password: '',
      }),
      remember: false,
      mustVerifyEmail: false,
      inputErrors: [],
    }),

    methods: {
      async login () {
        try {
          // Submit the form.
          const { data } = await this.form.post('/api/login')

          // Save the token.
          this.$store.dispatch('auth/saveToken', {
            token: data.token,
            remember: this.remember
          })

          // Fetch the user.
          await this.$store.dispatch('auth/fetchUser')

          // Redirect home.
          this.redirect()          
        } catch(error) {
          this.inputErrors = error.response.data.errors
        }
        
      },

      redirect () {
        const intendedUrl = Cookies.get('intended_url')

        if (intendedUrl) {
          Cookies.remove('intended_url')
          this.$router.push({ path: intendedUrl })
        } else {
          this.$router.push({ name: 'home' })
        }
      }
    }
  };
</script>