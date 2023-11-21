<template>
    <div data-app>
        <v-data-table 
            :search="search" 
            :headers="headers" 
            :items="users" 
            sort-by="names" 
            :loading="loading"
            loading-text="Loading... Please wait"
        >
            <template v-slot:top>
                <v-toolbar flat>
                    <div class="d-flex align-center justify-space-between">
                        <v-toolbar-title><strong>{{ $t('users') }}</strong></v-toolbar-title>

                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    class="mx-2"
                                    fab
                                    dark
                                    color="deep-purple"
                                    v-bind="attrs" v-on="on"
                                    x-small
                                >
                                    <v-icon dark >
                                    mdi-plus
                                    </v-icon>
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="text-h5">{{ formTitle }}</span>
                                </v-card-title>
        
                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-text-field 
                                              v-model="editedItem.name" 
                                              label="Name"
                                              :error-messages="nameErrors" 
                                              outlined
                                            ></v-text-field>
                                  
                                        </v-row>
                                        <v-row>
                                            <v-text-field 
                                                type="email" 
                                                v-model="editedItem.email" 
                                                :error-messages="emailErrors"
                                                label="Email"
                                                outlined
                                            ></v-text-field>
                                        </v-row>
                                        <v-row>
                                            <v-text-field 
                                                type="password" 
                                                v-if="editedIndex === -1" 
                                                v-model="editedItem.password" 
                                                :error-messages="emailErrors"
                                                label="Password"
                                                outlined
                                            ></v-text-field>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
        
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="#D32F2F" text @click="close">
                                        Cancel
                                    </v-btn>
                                    <v-btn color="#1976D2" text @click="save">
                                        Save
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>                        
                    </div>


                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>


                    <v-dialog v-model="dialogDelete" max-width="500px">
                        <v-card>
                            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="#D32F2F" text @click="closeDelete">Cancel</v-btn>
                                <v-btn color="#1976D2" text @click="deleteItemConfirm">OK</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon color="#1976D2" class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon color="#D32F2F" @click="deleteItem(item)">
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
      headers: [
        {
          text: 'ID',
          align: 'end',
          sortable: true,
          value: 'id',
        },
        {
          text:  'name',
          align: 'center',
          sortable: true,
          value: 'name',
        },
        {
          text: 'Email',
          align: 'center',
          sortable: true,
          value: 'email',
        },
        {
          text: 'Actions',
          value: 'actions',
          sortable: false,
        },

      ],
      users: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        name: '',
        email: '',
      },
      defaultItem: {
        id: 0,
        name: '',
        email: '',
      },

      nameErrors: [],
      emailErrors: [],
      passwordErrors: [],
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New User' : 'Edit User'
      },
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
    },

    methods: {
      async initialize () {


        const { data } = await axios.get('/api/users')

        this.users = data.data             

        this.loading = false       
        // console.log(data.data)
      },

      editItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
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
