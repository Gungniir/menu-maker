<script setup lang="ts">
import logo from '@/assets/logo-1.svg'
import girl from '@/assets/girl.svg'
</script>

<template>
  <div class="home">
    <v-container fluid="fluid" style="height: 100%">
      <v-row style="position:absolute; width: 100%">
        <v-col offset="3" cols="6" offset-md="5" md="2">
          <v-img eager :src="logo"/>
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
              <v-btn color="primary" x-large class="px-8 mr-10" to="login">
                Войти
              </v-btn>
              <v-btn color="primary" x-large class="px-8" outlined>
                Зарегистрироваться
              </v-btn>
            </v-col>
          </v-row>
        </v-col>
        <v-col>
          <v-img max-height="90vh" eager :src="girl"/>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<style lang="scss" scoped>
.home {
  height: 100%;
  background: radial-gradient(20vw 20vw at 10% 10%, #D8EFFF 0%, rgba(227, 242, 253, 0) 100%),
  radial-gradient(15vw 15vw at 40% 50%, rgba(255, 226, 183, 0.83) 0%, rgba(255, 233, 201, 0) 100%),
  radial-gradient(15vw 15vw at 70% 80%, #D8EFFF 0%, rgba(227, 242, 253, 0) 100%),
  radial-gradient(20vw 20vw at 110% 80%, rgba(255, 226, 183, 0.83) 0%, rgba(255, 233, 201, 0) 100%);


  .home__header {
    font-family: Roboto, serif;
    font-size: 55px;
    font-weight: 600;
    line-height: 64px;
    letter-spacing: 0.01em;
    text-align: left;
  }

  .home__content {
    font-family: Montserrat, serif;
    font-size: 32px;
    font-weight: 400;
    line-height: 45px;
    letter-spacing: 0;
    text-align: left;
  }
}
</style>
