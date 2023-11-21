<template lang="">
<v-row justify="center">
    <v-col cols="12" md="4">
        <v-card 
            class="elevation-2"
            outlined 
        >
            <v-card-title
                class="headline"
                primary-title
            >
                {{ $t('reset_password') }}
            </v-card-title>

            <v-divider></v-divider>

            <v-card-text>
                <v-form @submit.prevent="send" @keydown="form.onKeydown($event)" >
                    <v-text-field 
                        v-model="form.email"
                        :label="$t('email')" 
                        :value="form.email"
                        type="text" 
                        outlined dense
                        :error-messages="form.errors.get('email')"
                        @input="form.errors.clear('email')"
                    ></v-text-field>
  
                    <div class="d-flex align-center justify-center">
                      <v-btn color="primary" type="submit">{{ $t('send_password_reset_link') }}</v-btn>               
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
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('reset_password') }
  },

  data: () => ({
    status: '',
    form: new Form({
      email: '',
    })
  }),

  methods: {
    async send () {
      const { data } = await this.form.post('/api/password/email')

      this.status = data.status

      this.form.reset()
    }
  }
}
</script>
<style lang="">
    
</style>