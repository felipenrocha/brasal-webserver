<template>
    <!-- <Navbar></Navbar> -->
    <div id="app" class="container" style="padding:5%" v-if="loaded">
        <div class="row">
            <div class='col s4'>
                <!-- {{ this.$route.params.id }} -->
                <h6>Bem Vindo, {{ this.nome }}!</h6>
                <h6>Email: {{ this.email }}</h6>
            </div>
            <div class='col s6'>
            </div>
            <div class='col s2'>
                <a class="btn-floating btn green">
                    <router-link to="/home">
                        <i class="material-icons" title="Ver tarefas" >list</i></router-link>

                </a>
                &nbsp;
                <a href="#!" class="btn-floating btn blue" title="Sair" v-on:click="logout">
                    <i class="material-icons">exit_to_app</i>

                </a>

            </div>
        </div>
        <div style="padding: 10%;">
            <form class="col s12" @submit.prevent="submitForm">
                <div class="row">
                    <div class="input-field">
                        <!-- <label for="titulo">Título:</label> -->
                        <input type="text" id="titulo" class="validate" v-model="titulo" required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field">
                        <label for="titulo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Data</label>

                        <VueDatePicker  month-name-format="long"  model-type="dd/MM/yyyy hh : mm" v-model="date" aria-placeholder="data"></VueDatePicker>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field">
                        <!-- <label for="descricao">Descrição:</label> -->
                        <textarea id="descricao" class="materialize-textarea validate" v-model="descricao"
                            required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field">
                        <select style="width: 100%; height: 100%; display: flex;" v-model="status_id" @change="get_key()">
                            <option selected disabled>Selecione o Status da tarefa</option>
                            <option v-for="status in status_list" v-bind:value="status.id" v-bind:key="status.id">
                                {{ status.nome }}
                            </option>
                            <label>Selecione Status</label>
                        </select>

                    </div>
                </div>
                <div v-if="!loading">
                    <button type="submit" class="btn" v-on:click="edit">Salvar</button>
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
            </form>
        </div>
        <div class='row' style="padding:5%; font-weight: bold;">
            <p v-if="message" style="color:#0bd500;">
                {{ message }}</p>
        </div>
        <div class='row'>
            <p v-if="erro" style="color:#d50000;">
                {{ erro }}</p>
        </div>
    </div>
</template>

<script>
const API_DEV_URL = 'http://localhost:8000'

import router from '../../router';
import axios from 'axios';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {

    data() {
        return {

            titulo: '',
            date: null,
            descricao: '',
            loaded: false,
            nome: '',
            email: '',
            status_list: [],
            status_id: null,
            loading: false,
            erro: '',
            user_id: null,
            message: ''
        }
    },
    components: { VueDatePicker },

    beforeMount() {
        //check token before mounting:
        this.checkToken();
        // monta lista de status
        this.montaStatus();
        // monta input das tarwefas
        this.montaTarefa();



    },
    methods: {
        checkToken() {
            /*
                A função checkToken() é responsável por verificar se o usuário está autenticado através da presença de um token 
            armazenado no armazenamento local (local storage) do navegador. 
                Se o token estiver presente, a função utiliza o token como cabeçalho de autorização (Bearer token) e faz uma solicitação
            de API para obter os dados do usuário. 
                Caso a solicitação seja bem-sucedida, os dados do usuário, como nome e email, são extraídos da resposta e armazenados nas
            variáveis this.nome e this.email. Além disso, a variável this.loaded é definida como true, indicando que os dados do usuário 
            foram carregados.
                Por outro lado, se o token não estiver presente no armazenamento local ou a solicitação de API falhar (por exemplo, devido
            à expiração ou invalidez do token), a função exibirá um alerta informando ao usuário que o token é inválido ou expirou e redirecionará
            o usuário para a página de login.
            
                */

            if (localStorage.getItem("user") === null) {
                alert('Usuario não está logado, você será redirecionando para página de Login');
                router.push('login');
            }
            else {
                this.token = localStorage.getItem("user");
                console.log(this.token);
                this.AuthStr = 'Bearer ' + this.token;

                axios
                    .get((API_DEV_URL + '/user'), {
                        headers: { 'Authorization': this.AuthStr }
                    })
                    .then(response => {
                        console.log('Resposta: ', response);
                        this.user_id = response['data']['id'];
                        this.nome = response['data']['name'];
                        this.email = response['data']['email'];
                        this.loaded = true;



                    })
                    .catch((error) => {
                        alert('Token expirado ou inválido, você será redirecionando para página de Login');
                        router.push('login');

                    });;
            }

        },
        logout() {
            // funcao responsavel para realizar o logoff do usuario
            // uma requisicao para apagar o token eh feita na api e é removido o token do storage (não necessário)
            axios
                .get((API_DEV_URL + '/logout'), {
                    headers: { 'Authorization': this.AuthStr }
                })
                .then(response => {
                    // remove localstoreage user  token
                    localStorage.removeItem("user");
                    // go back to login
                    router.push('');
                });


        },
        montaStatus() {
            // monta variavel status para ser mostrada no  fropntend
            // essa rota nao precisa de autoreizacao jwt.

            axios
                .get((API_DEV_URL + '/api/status/get'))
                .then(response => {

                    response = response['data']


                    // monta status
                    for (let i = 0; i < response.length; i++) {
                        let status = response[i];

                        this.status_list.push({
                            'id': status['id'],
                            'nome': status['name']
                        });

                    }

                });
        },
        montaTarefa() {
            // metodo para montar os input da tarefa que deseja ser editada

            axios
                .get((API_DEV_URL + '/api/tarefas/get/' + this.$route.params.id),
                    { headers: { 'Authorization': this.AuthStr } })
                .then(response => {

                    response = response['data'];

                    console.log('response', response);
                    // monta status

                    this.titulo = response['titulo'];
                    this.descricao = response['descricao'];
                    this.status_id = response['status_id']
                    this.date = response['data_tarefa'];
                    this.loaded = true;


                });
        },
        edit() {
            // a autorizacao para checar se o usuario possui acesso a essa tarefa sera feita no backend;
            // function to edit request and save new task
            // // funcao para requisicao put e atualizar a tarefa nova;

           

            let data = {
                'titulo': this.titulo,
                'descricao': this.descricao,
                'status_id': this.status_id,
                'responsavel_id': this.user_id,
                'data_tarefa': this.date
            }
            axios.put((API_DEV_URL + '/api/tarefas/update/' + this.$route.params.id), data, {
                headers: { 'Authorization': this.AuthStr }
            })
                .then(response => {
                    console.log(response);
                    this.message = response['data']['message'];
                    this.loading = false;
                    // redirect to dashboard
                    alert(this.message);
                    router.push('/');


                })
                .catch((error) => {
                    if (error['response']['data']) {
                        this.erro = error['response']['data'];

                    } else {
                        console.log();
                        this.erro = error
                        alert('nao foi possivel Criar a tarefa, erro: ' + this.erro);

                    }
                    this.loading = false;

                });;




        },
        get_key() {
            console.log('value', this.status_id);
        }



    }
}
</script>

<style>
/* Estilo personalizado para a tabela */
.container {
    padding-top: 20px;
}

/* Estilização das ações */
.btn-floating.btn-small {
    margin-right: 5px;
}

/* Estilização do botão de remover */
.btn-floating.btn-small.red {
    background-color: #f44336;
}

/* Estilização do botão de expandir */
.btn-floating.btn-small.green {
    background-color: #4caf50;
}

/* Estilização das linhas de hover na tabela */
table.highlight tbody tr:hover {
    background-color: #f2f2f2;
}
</style>
