<template lang="">
    <v-card class="outlinded elevation-2">
        <v-card-title>
            {{ $t('updating') }}

            <v-spacer></v-spacer>

            <custom-button @click="$router.push({ name: 'users.index' })" color="#890E4F" class="white--text">
                <v-icon>mdi-menu-left</v-icon>
                {{ $t('cancel') }}
            </custom-button>
        </v-card-title>

        <v-divider></v-divider>

        <v-card-text>
            <v-form>
                <custom-input 
                    v-model="form.name"
                    :label="$t('name')"
                    :errors="inputErrors['name'] ? inputErrors['name'] : []"
                />
                <custom-input
                    v-model="form.email"
                    :label="$t('email')"
                    type="email"
                    :errors="inputErrors['email'] ? inputErrors['email'] : []"
                />
            </v-form>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions class="d-flex justify-end pr-4">
            <transition name="fade" mode="out-in">
                <span v-if="form.success" class="mr-2 text-success">{{ form.successMessage }}</span> 
            </transition>  

            <!-- <custom-button color="error" @click="$router.go(-1)">
                {{ $t('cancel') }}
            </custom-button> -->

            <custom-button color="success" @click="updateUser">
                {{ $t('update') }}
            </custom-button>
        </v-card-actions>

    </v-card>  
</template>
<script>
  import Form from 'vform'

  export default {
    middleware: 'auth',
    metaInfo () {
        return { title: this.$t('updating') }
    },

    data: () => ({
        form: new Form({
            id: 0,
            name: '',
            email: '',
            success: false,
            successMessage: '',
        }),
        inputErrors: [],
    }),

    async created () {
        console.log(this.$route.params.userId)
        const { data } = await this.form.get(`/api/users/${this.$route.params.userId}`)
        console.log(data);
        this.form.id = data.data.id
        this.form.name = data.data.name
        this.form.email = data.data.email
    },

    methods: {
        async updateUser() {
            try {
                const { data } = await this.form.patch('/api/users/' + this.$route.params.userId + '/update')   
                console.log(data.success);
                this.form.successMessage = this.$t(data.success);
                this.success();
                
            } catch(error) {
                console.log(error.response.data.errors);
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