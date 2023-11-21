<template>
    <v-card>
        <v-card-title>
            <h3>{{ $t('settings') }}</h3>
        </v-card-title>

        <v-card-subtitle>
            {{ $t(subtitle) }}
        </v-card-subtitle>
        <v-divider></v-divider>

        <v-card-text>
              <transition name="fade" mode="out-in">
                <router-view />
              </transition>                
        </v-card-text>
      <v-navigation-drawer
        absolute
        permanent
        right
      >
        <template v-slot:prepend>
          <v-list-item two-line >
            <v-list-item-avatar>
              <img :src="user.photo_url">
            </v-list-item-avatar>
  
            <v-list-item-content>
              <v-list-item-title>{{ user.name }}</v-list-item-title>
              <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>

        </template>
  
        <v-divider></v-divider>
  
        <v-list dense>
          <router-link v-if="item.route" v-for="item in items" :key="item.title" :to="{ name: item.route }">
            <v-list-item
              :class="{ 'active': item.route === $route.name }"
            >
              <v-list-item-icon>
                <v-icon :class="{'icon-active': item.route === $route.name}">{{ item.icon }}</v-icon>
              </v-list-item-icon>
    
              <v-list-item-content>
                <v-list-item-title>{{ $t(item.title) }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </router-link>
          <!-- <router-link v-if="item.route" v-for="(item, index) in menuItems" :key="index" :to="{ name: item.route }">
            <v-list-item 
              dark 
              :class="{ 'active': item.route === $route.name }"
            >
            

              <v-list-item-icon>
              <v-icon :class="{'icon-active': item.route === $route.name}">{{ item.icon }}</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                  <v-list-item-title>{{ $t(item.title) }}</v-list-item-title>
              </v-list-item-content>
                
          </v-list-item>              
        </router-link> -->
        </v-list>
      </v-navigation-drawer>
    </v-card>
  </template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    middleware: 'auth',
    data () {
      return {
        items: [
            {          
                icon: 'mdi-account-edit',
                title: 'profile',
                route: 'settings.profile'
            },
            {
                icon: 'mdi-lock',
                title: 'password',
                route: 'settings.password'
            }
        ],
      }
    },
    computed: {
         ...mapGetters({
            user: 'auth/user'
        }),   

        subtitle() {
            const foundItem = this.items.find(item => item.route === this.$route.name);
            return foundItem ? foundItem.title : '';
        }
    },



  }
</script>

<style scoped>
  a {
    text-decoration: none;
  }

    
  .v-list-item.active {
    width: 95%;
    background-color: rgba(124, 124, 124, 0.2);
    border-radius: 0px 16px 16px 0px;
  }
  .icon-active {
    color: #FA845C;
  }
</style>