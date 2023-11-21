<template>
  <div data-app>
      <v-data-table 
          :search="search" 
          :headers="localeHeaders" 
          :items="users" 
          sort-by="names" 
          :loading="loading"
          :loading-text="$t('loading_please_wait')"
          class="elevation-2 outlined rounded"

          :footer-props="{
            'items-per-page-text': $t('dataTable.ariaLabel.rowsPerPageText'),
            'items-per-page-all-text': $t('dataTable.ariaLabel.allRows'),
          }"
          outlined
      >

        <template v-slot:progress>
          <v-progress-linear
            indeterminate
            color="#FA845C"
          ></v-progress-linear>
        </template>

        <template v-slot:footer.page-text="{pageStart, pageStop, itemsLength}">
           {{ pageStart }} - {{ pageStop }} {{ $t('dataTable.ariaLabel.of') }} {{ itemsLength }}
        </template>

        <template v-slot:top>
            <v-toolbar flat>
                <div class="d-flex align-center justify-space-between">
                    <v-toolbar-title><strong>{{ $t('users') }}</strong></v-toolbar-title>

                    <custom-button
                      fab
                      :to="{ name: 'users.create' }"
                      class="ml-2 white--text"
                      color="primary"
                      dark
                    >
                      <v-icon>mdi-plus</v-icon>
                    </custom-button>              
                </div>
                
                <v-spacer></v-spacer>

                <custom-search
                  v-model="search"
                  :label="$t('search')"
                />

                <v-dialog v-model="dialogDelete" max-width="550px" class="mx-auto">
                    <v-card>
                        <v-card-title>{{ $t('are_you_sure_you_want_to_delete_this_item') }}</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="#D32F2F" text @click="closeDelete">Cancel</v-btn>
                            <v-btn color="#1976D2" text @click="deleteItemConfirm">OK</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
            <v-divider />
        </template>

          <template v-slot:item.actions="{ item }">
              <v-icon color="primary" class="mr-2" @click="editItem(item)">
                  mdi-pencil
              </v-icon>
              <v-icon color="#890E4F" @click="deleteItem(item)">
                  mdi-delete
              </v-icon>
          </template>
          <template v-slot:no-data>
              <v-btn color="#1976D2" @click="initialize">
                  Reset
              </v-btn>
          </template>
      </v-data-table>
  </div>
  
</template>

<script>

import axios from 'axios'

export default {
  middleware: 'auth',

  data: () => ({
    loading: true,
    dialog: false,
    search:'',
    dialogDelete: false,
    headers: [],
    users: [],
    defaultItem: {
      id: 0,
      name: '',
      email: '',
    },

    nameErrors: [],
    emailErrors: [],
    passwordErrors: [],
  }),

  mounted () {
    this.setHeaders()
  },

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'New User' : 'Edit User'
    },

    localeHeaders () {
      return [
        {
          text: this.$t('dataTable.headers.id'),
          align: 'end',
          sortable: true,
          value: 'id',
        },
        {
          text: this.$t('dataTable.headers.name'),
          align: 'center',
          sortable: true,
          value: 'name',
        },
        {
          text: this.$t('dataTable.headers.email'),
          align: 'center',
          sortable: true,
          value: 'email',
        },
        {
          text: this.$t('dataTable.headers.actions'),
          value: 'actions',
          sortable: false,
        },
      ]
    }

  },

  watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    },
  },

  created () {
    this.initialize()
    // this.setHeaders()
  },

  methods: {
    setHeaders() {
      this.headers = [
        {
          text: this.$t('dataTable.headers.id'),
          align: 'end',
          sortable: true,
          value: 'id',
        },
        {
          text: this.$t('dataTable.headers.name'),
          align: 'center',
          sortable: true,
          value: 'name',
        },
        {
          text: this.$t('dataTable.headers.email'),
          align: 'center',
          sortable: true,
          value: 'email',
        },
        {
          text: this.$t('dataTable.headers.actions'),
          value: 'actions',
          sortable: false,
        },

      ]
    },


    async initialize () {


      const { data } = await axios.get('/api/users')

      this.users = data.data             

      this.loading = false       
      // console.log(data.data)
    },

    editItem (item) {
      this.$router.push({ name: 'users.edit', params: { userId: item.id } })
    },

    deleteItem (item) {
      this.editedIndex = this.users.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm () {
      console.log(this.editedItem)
      this.destroyUser(this.editedItem)
      // this.users.splice(this.editedIndex, 1)
      this.closeDelete()
    },

    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    closeDelete () {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    save () {
      if (this.editedIndex > -1) {
        console.log(this.editedItem)
        this.updateUser(this.editedItem)
        this.initialize()
        
        this.close()

      } else {
        try {
          this.storeUser(this.editedItem) 
          this.initialize() 
          // this.close()  

        } catch (error) {
          console.log(error)
        }
      }

    },

    async storeUser(user) {
      try {
          const { data } = await axios.post('/api/users', user)   
          this.initialize()   
          this.close()       
      } catch (error) {
          this.nameErrors = error.response.data.errors.name
          this.emailErrors = error.response.data.errors.email
          this.passwordErrors = error.response.data.errors.password
      }

    },

    async updateUser(user) {
      const { data } = await axios.patch('/api/users/update/' + user.id, user)
    },

    async destroyUser(user) {
      const { data } = await axios.delete('/api/users/destroy/' + user.id, {data: user})

      console.log(data);

      this.initialize()
    }

  },

  metaInfo() {
      return { title: this.$t('users') }
  }

}
</script> 

<style scoped>

</style>
