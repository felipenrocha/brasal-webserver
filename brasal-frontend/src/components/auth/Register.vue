


<template>
  <div class="section"></div>
  <main>
    <center>
      <div class="section"></div>

      <h5 class="indigo-text">Criar Conta</h5>
      <div class="section"></div>

      <div class="container">
        <!-- INPUT FORM START -->
        <div class="z-depth-1 grey lighten-4 row"
          style="display: inline-block; padding: 32px 64px 32px 64px; border: 1px solid #EEE; width: 75%;">

          <form class="col s12" method="post">
            <div class='row'>
              <div class='col s12'>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type="text" v-model="name" />
                <label for='name'>Nome Completo</label>
              </div>
            </div>
            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type="text" v-model="email" />
                <label for='email'>Email</label>
              </div>
            </div>
            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='password' v-model="password" />
                <label for='password'>Senha</label>
              </div>
            </div>
            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='password' name='password_confirmation' id='password_confirmation'
                  v-model="password_confirmation" />
                <label for='password_confirmation'>Confirme sua senha</label>
              </div>
              <label style='float: right;'>

              </label>
            </div>
            <!-- INPUT FORM END -->

            <br />
            <center>
              <div class='row' v-if="!loading">
                <a href="#!" v-on:click="register" class='col s12 btn btn-large waves-effect indigo'>Criar</a>
              </div>

              <div class="preloader-wrapper big active" v-if="loading">
                <div class="spinner-layer spinner-blue-only">
                  <div class="circle-clipper left">
                    <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                    <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                    <div class="circle"></div>
                  </div>
                </div>
              </div>

            </center>
          </form>
        </div>
      </div>
      <router-link to="/login">
        <a>
          Já possui uma conta? Login
        </a>
      </router-link>
      <div class='row'>
        <p v-if="erro" style="color:#d50000;">
          {{ erro }}</p>
      </div>
    </center>

    <div class="section"></div>
    <div class="section"></div>

  </main>
</template>
<script>

import axios from 'axios'
import router from '../../router'
import hasher from '../../services/hasher'


//consts
// TODO: put this in env file
const API_DEV_URL = 'http://localhost:8000'

//variabless
export default {
  name: 'RegisterComponent',
  mixins: [hasher],
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      erro: null,
      response: '',
      loading: false
    }
  },

  methods: {


    /*
      Descrição:
        Esta função envia uma solicitação POST para o endpoint de registro da API (API_DEV_URL + '/register') usando Axios.
        A solicitação inclui os seguintes dados:
            -> name (string): Nome do usuario.
            -> email (string): O email do usuário.
            -> password (string): A senha do usuário.
            -> password_confirmation (string): A confirmação senha do usuário.
        Se a solicitação de registro for bem-sucedida, seus dados são cadastrados no Banco de dados PostgreSQL (BACKEND) e
      é redirecionado para home.
          Se ocorrer algum erro durante a solicitação de registro, a função registra o erro no console e chama a função errorHandler
      para lidar com o erro.
     */


    register() {
      this.loading = true;
      axios
        .post((API_DEV_URL + '/register'), {
          'name': this.name,
          'email': this.email,
          'password': hasher(this.password),
          'password_confirmation': hasher(this.password_confirmation)
        })
        .then(response => {
          console.log('Resposta: ', response)
          this.response = response['data']['message']
          alert('Conta criada!');
          router.push('login');
          this.loading = false;

        })
        .catch((error) => {
          this.loading = false;

          console.log(error);
          this.erro = this.errorHandler(error);
        });;
    },

    /*
    Descrição:
      Tratamento de erros do backend para serem exibidos no frontend.
    */
    errorHandler(error) {

      error = JSON.parse(error['request']['response']);
      console.log(error);
      if (error['password'] && error['email'] && error['name']) {
        return error['password'][0] + ' ' + error['email'][0] + ' ' + error['name'][0];;
      }
      else if (error['password'] && error['email']) {
        return error['password'][0] + ' ' + error['email'][0];
      }
      else if (error['email'] && error['name']) {
        return error['email'][0] + ' ' + error['name'][0];;
      }
      else if (error['password'] && error['name']) {
        return error['password'][0] + ' ' + error['email'][0] + ' ' + error['name'][0];;
      }
      else if (error['password']) {
        return error['password'][0];
      }
      else if (error['email']) {

        return error['email'][0];
      }
      else if (error['message']) {
        return error['message'];
      }
      return ''
    }
  }
}

</script>


<style scoped>
body {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
}

main {
  flex: 1 0 auto;
}

body {
  background: #fff;
}

</style>

