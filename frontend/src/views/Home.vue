<template>
  <div class="home">
    <v-container fluid style="height: 100%">
      <v-row style="position:absolute; width: 100%">
        <v-col offset="3" cols="6" offset-md="5" md="2">
          <v-img :src="require('@/assets/logo-1.svg')"/>
        </v-col>
      </v-row>
      <v-row style="height: 100%" class="d-flex align-center">
        <v-col offset-md="1" md="5">
          <v-row>
            <v-col class="home__header">
              Живи проще и вкуснее!
            </v-col>
          </v-row>
          <v-row>
            <v-col class="home__content">
              Menu maker конструирует умное меню по вашим предпочтениям и автоматически составляет список покупок
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-btn color="primary" x-large class="px-8 mr-10">
                Войти
              </v-btn>
              <v-btn color="primary" x-large class="px-8" outlined>
                Зарегистрироваться
              </v-btn>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator'
import HelloWorld from '@/components/HelloWorld.vue' // @ is an alias to /src

@Component({
  components: {
    HelloWorld
  },
  data() {
    return {}
  }
})
export default class Home extends Vue {
}
</script>

<style lang="scss" scoped>
.home {
  height: 100%;
  background: radial-gradient(20vw 20vw at 10% 10%, #D8EFFF 0%, rgba(227, 242, 253, 0) 100%),
  radial-gradient(15vw 15vw at 40% 50%, rgba(255, 226, 183, 0.83) 0%, rgba(255, 233, 201, 0) 100%),
  radial-gradient(15vw 15vw at 70% 80%, #D8EFFF 0%, rgba(227, 242, 253, 0) 100%),
  radial-gradient(20vw 20vw at 110% 80%, rgba(255, 226, 183, 0.83) 0%, rgba(255, 233, 201, 0) 100%);


  .home__header {
    font-family: Roboto;
    font-size: 55px;
    font-weight: 600;
    line-height: 64px;
    letter-spacing: 0.01em;
    text-align: left;
  }

  .home__content {
    font-family: Montserrat;
    font-size: 32px;
    font-weight: 400;
    line-height: 45px;
    letter-spacing: 0em;
    text-align: left;
  }
}
</style>
