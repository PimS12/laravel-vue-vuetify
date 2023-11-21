<template>
    <v-app>
      <!-- Левое меню -->
      <v-navigation-drawer 
        elevation="2"
        outlined
        app fixed 
        v-model="drawer" 
        v-if="user"
        class="secondary"
      >
        <v-list dense class="pa-0">
          <div app fixed class="pa-2 secondary darken-2">
            <div class="ml-4"><h2 class="white--text">{{ appName }}</h2></div>
            <div class="ml-4 text--secondary"><h6  class="white--text">1.1.0</h6></div>
          </div>
  
          <!-- <v-divider></v-divider> -->

            <div class="pa-2"></div>
            <router-link v-if="item.route" v-for="(item, index) in menuItems" :key="index" :to="{ name: item.route }">
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
            </router-link>

            <v-list-item v-else dark>
              <v-list-item-content><strong>{{ $t(item.title) }}</strong></v-list-item-content>
            </v-list-item>
          </v-list>

      </v-navigation-drawer>
  
      <!-- Верхняя панель -->
      <v-app-bar 
        color="#890E4F"
        app fixed
        dark
      >
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" v-if="user"></v-app-bar-nav-icon>
        <h2 v-else>{{ appName }}</h2>
        <div>
          <locale-dropdown />          
        </div>


        <v-spacer></v-spacer>

        <v-menu v-if="user"
          rounded="b-xl"
          offset-y
        >
          <template v-slot:activator="{ on, attrs }">
            <div
              v-bind="attrs"
              v-on="on"
            >          
                <v-avatar size="40">
                  <img :src="user.photo_url" alt="John">
                </v-avatar>

                {{ user.name }}
                <v-icon>mdi-chevron-down</v-icon>                
            </div>
          </template>
          <v-list>
            <v-list-item 
              link :to="{ name: 'settings.profile' }"
            >
              <v-list-item-icon :class="{'icon-active': $route.name === 'settings.profile'}">
                <v-icon >mdi-cog</v-icon>
              </v-list-item-icon>
  
              <v-list-item-content>
                  <v-list-item-title>{{ $t('settings') }}</v-list-item-title>
              </v-list-item-content>                
  
            </v-list-item>

            <v-list-item link  @click.prevent="logout">
          
              <v-list-item-icon>
                <v-icon>mdi-exit-to-app</v-icon>
              </v-list-item-icon>
  
              <v-list-item-content>
                  <v-list-item-title>{{ $t('logout') }}</v-list-item-title>
              </v-list-item-content>                
  
            </v-list-item>
          </v-list>
        </v-menu>

        <div v-else>
            <v-btn 
              text 
              link 
              :to="{ name: 'login' }"
            >
              <span>{{ $t('login') }}</span>
            </v-btn>
        
            <v-btn
              text
              link
              :to="{ name: 'register' }"
            >
              <span>{{ $t('register') }}</span>
            </v-btn>
        </div>
      </v-app-bar>

      <!-- Основной контент -->
      <v-main class="grey lighten-5 mt-5">
        <v-container fluid>
          <child />
        </v-container>
      </v-main>
    </v-app>
  </template>
  
  <script>
    import { mapGetters } from 'vuex'
    import LocaleDropdown from '~/components/LocaleDropdown'

    export default {
    name: 'AdminLayout',
    components: {
        LocaleDropdown
    },

    data: () => ({
        appName: window.config.appName,
        drawer: true,
        menuItems: [
          { title: 'main_menu'},
          { title: 'home', icon: 'mdi-home', route: 'home' },
          { title: 'admin_menu'},
          { title: 'users', icon: 'mdi-account-group', route: 'users.index' },
          // Добавьте другие пункты меню по необходимости
        ],
        menuUser: [
          { title: 'profile', route: 'settings.profile' },
          { title: 'logout', route: 'logout' },
        ],

    }),

    computed: mapGetters({
        user: 'auth/user'
    }),

    methods: {
        async logout () {
        // Log out the user.
        await this.$store.dispatch('auth/logout')

        // Redirect to login.
        this.$router.push({ name: 'login' })
        }
    }
    }
  </script>
  
  <style scoped>
  a {
    text-decoration: none;
  }

  .custom-background {
    background-color: #890E4F;
  }

  .v-list-item.active {
    width: 95%;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 0px 16px 16px 0px;
  }
  .icon-active {
    color: #FA845C;
  }
  </style>