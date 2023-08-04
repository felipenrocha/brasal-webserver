<template>
    <!-- <Navbar></Navbar> -->
    <div class="container" style="padding: 5%" v-if="loaded">
        <div class="row">
            <div class='col s4'>

                <h6>Bem Vindo, {{ nome }}!</h6>
                <h6>Email: {{ email }}</h6>
            </div>
            <div class='col s6'>
            </div>
            <div class='col s2'>
                <a class="btn-floating btn green" v-on:click="addTask" title="Criar tarefa">
                    <router-link to="/create">
                        <i placeholder="Criar tarefa" class="material-icons">note_add</i></router-link>

                </a>
                &nbsp;
                <a href="#!" class="btn-floating btn blue" v-on:click="logout" title="Sair">
                    <i class="material-icons">exit_to_app</i>

                </a>

            </div>
        </div>
        <table class="highlight">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Data - Hora</th>

                    <th>Status</th>
                    <th>Editar</th>
                    <th>Remover</th>
                    <th>Expandir</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de linha de tarefa -->
                <tr v-for="(tarefa, index) in tarefas" :key="index">
                    <td>{{ tarefa.titulo }}</td>
                    <td>{{ tarefa.data_tarefa }}</td>

                    <td>{{ tarefa.status }}</td>
                    <td>
                        <!-- Botão para editar -->
                        <a class="btn-floating btn-small blue" href="#"><i class="material-icons"
                                @click="editTarefa(tarefa.id)">edit</i></a>
                    </td>
                    <td>
                        <!-- Botão para remover -->
                        <a class="btn-floating btn-small red" href="#"><i class="material-icons"
                                @click="deleteTarefa(tarefa.id)">delete</i></a>
                    </td>
                    <td>
                        <!-- Botão para expandir -->
                        <a class="btn-floating btn-small green" @click="toggleDescricao(index)">
                            <i v-if="!tarefa.expandido" class="material-icons">expand_more</i>
                            <i v-else class="material-icons">expand_less</i>
                        </a>
                    </td>
                </tr>

            </tbody>
        </table>

        <!-- Seção de descrição (mostrada dinamicamente) -->
        <div v-for="(tarefa, index) in tarefas" :key="index" class="task-description">
            <div v-if="tarefa.expandido">
                <div class="card" style="padding: 5%">
                    <div class="card-content">
                        <h5 class="title">{{ tarefa.titulo }}</h5>
                        <h6> Data: {{ tarefa.data_tarefa }}</h6>

                        <p>{{ tarefa.descricao }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
const API_DEV_URL = 'http://localhost:8000'

import router from '../../router';
import axios from 'axios';

export default {
    data() {
        return {
            nome: '',
            email: '',
            token: '',
            AuthStr: '',
            loaded: false,
            tarefas: []
        }
    },
    beforeMount() {
        //check token before mounting:
        this.checkToken();
        // monta as tarefas antes de carregar a pagina
        this.montaTarefas();
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
                        // token auth
                        headers: { 'Authorization': this.AuthStr }
                    })
                    .then(response => {
                        console.log('Resposta: ', response);
                        this.nome = response['data']['name']
                        this.email = response['data']['email']
                        this.loaded = true;


                    })
                    .catch((error) => {
                        alert('Token expirado ou inválido, você será redirecionando para página de Login');
                        router.push('login',);

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
                    router.push('login');
                })
                .catch((error) => {
                    alert('nao foi possivel deslogar')

                });;

        },
        toggleDescricao(index) {
            //mostrar a seção expandida da tabela
            this.tarefas[index].expandido = !this.tarefas[index].expandido;
        },
        montaTarefas() {
            /*
            Método responsável para montar as  tarefas para serem mostradas no frontend
            */
            axios
                .get((API_DEV_URL + '/api/tarefas/get'), {
                    headers: { 'Authorization': this.AuthStr }
                })
                .then(response => {
                    // pega dados das tarefas em array
                    let tarefas = response['data'];
                    // monta tarefas
                    for (let i = 0; i < tarefas.length; i++) {
                        let tarefa = tarefas[i];
                        console.log(tarefa);

                        this.tarefas.push({
                            id: tarefa['id'],
                            titulo: tarefa['titulo'],
                            data_tarefa: tarefa['data_tarefa'],
                            status: tarefa['status_id'],
                            descricao: tarefa['descricao'],
                            expandido: false
                        })
                    }
                }).catch(err => {
                    // caso do token nao estar mais disponivel.
                    console.log(err);
                    // alert('Usuario foi deslogado, pro favor, entre novamente.');
                });
        },


        // crud functions frontend

        deleteTarefa(id) {
            console.log('id tarefa', id);
            axios
                .delete((API_DEV_URL + '/api/tarefas/delete/' + id), {
                    headers: { 'Authorization': this.AuthStr }
                })
                .then(response => {
                    console.log(response);
                    //remontar tarefas
                    location.reload();
                })
                .catch(err => {
                    // erro
                    console.log(err);
                    alert('Não foi possível deletar a tarefa, tente novamente mais tarde.');
                });
        },
        // edit will be done in another route:
        //edicao sera feita em outra rota
        editTarefa(id) {

            router.push('edit/' + id);
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
