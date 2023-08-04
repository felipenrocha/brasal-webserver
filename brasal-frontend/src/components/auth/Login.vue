


<template>
  <div class="section"></div>
  <main>
    <center>
      <div class="section"></div>

      <h5 class="indigo-text">Login</h5>
      <div class="section"></div>

      <div class="container">
        <div class="z-depth-1 grey lighten-4 row"
          style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

          <form class="col s12" method="post">
            <div class='row'>
              <div class='col s12'>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type="text" v-model="email" placeholder="Email" />
                <label for='email'>Email</label>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input class='validate' type='password' name='password' id='password' v-model="password" />
                <label for='password'>Senha</label>
              </div>
              <label style='float: right;'>

              </label>
            </div>

            <br />
            <center>
              <div class='row'><a href="#!" v-on:click="login" class='col s12 btn btn-large waves-effect indigo'
                  v-if="!loading">
                  Login
                </a>
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
      <router-link to="/register">
        <a>Criar conta </a>
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
import hasher from '../../services/hasher'
//consts
// TODO: put this in env file
const API_DEV_URL = 'http://localhost:8000'

//variabless
export default {
  name: 'LoginComponent',
  mixins: [hasher],
  data() {
    return {
      email: '',
      password: '',
      erro: null,
      loading: false
    }
  },

  methods: {


    /*
      Descrição:
        Esta função lida com o processo de login do usuário.
        Ela envia uma solicitação POST para o endpoint de login da API (API_DEV_URL + '/login') usando o Axios.
        A solicitação inclui os seguintes dados:
          -> email (string): O email do usuário.
          -> password (string): A senha do usuário.
        Se a solicitação de login for bem-sucedida, a função registra a resposta no console, salva o token no cache e redireciona para a home.
        Se ocorrer algum erro durante a solicitação de login, a função registra o erro no console e chama a função errorHandler para lidar com o erro.
    */
    login() {
      this.loading = true;
      // TODO ! IMPORTANT! hash frontend password;
      axios
        .post((API_DEV_URL + '/login'), {
          'email': this.email,
          'password': hasher(this.password)
        })
        .then(response => {
          console.log('Resposta: ', response);
          this.erro;
          // set token
          localStorage.setItem('user', response['data']['bearer_token']);
          window.location.href = 'home'
          this.loading = false;

        })
        .catch((error) => {
          console.log(error);
          this.erro = this.errorHandler(error);
          this.loading = false;

        });;
    },
    /*
    Descrição:
      Tratamento de erros do backend para serem exibidos no frontend.
    */
    errorHandler(error) {

      error = JSON.parse(error['request']['response']);
      if (error['password'] && error['email']) {
        return error['password'][0] + ' ' + error['email'][0];
      }
      else if (error['password']) {
        return error['password'][0];
      }
      else if (error['email']) {
        return error['email'][0];
      }
      else if (error['message']) {
        return 'Usuário ou senha incorretos.';
      }
      return ''
    },


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

