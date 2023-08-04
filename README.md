

## Case Dev Jr. Brasal - Sistema de Gerenciamento de tarefas.

### Requisitos:

    PHP >= 8.2.8

    PostgreSQL >= 15.3

    Lumen >= 10.0.0

    Vue.js >= 2.7s



#### 1 -  Configurando PostgreSQL (Banco de dados):

        Para utilizar o sistema é necessário criar um banco de dados, a criação das tabelas será feita depois automaticamente pela CLI do php, precisamos somente criar e configurar o banco de dados.

        Criando banco de dados:

        Dentro da CLI do PostgreSQL utilize o comando:

            $ \c databaseName
        
        Onde databaseName é o nome do banco de dados. Depois, precisamos dizer para o Lumen como conectar com nosso banco de dados, isso é feito no arquivo .env que deve ser criada na root do diretório 'brasal-backend', a seguir temos um exemplo de como deve ser um arquivo .env:
```
# .env:
            APP_NAME=Lumen
            APP_ENV=local
            APP_KEY=
            APP_DEBUG=true
            APP_URL=http://localhost
            APP_TIMEZONE=UTC

            LOG_CHANNEL=stack
            LOG_SLACK_WEBHOOK_URL=


            DB_CONNECTION=pgsql
            DB_HOST=127.0.0.1
            DB_PORT=5432
            DB_DATABASE=
            DB_USERNAME=
            DB_PASSWORD=

            CACHE_DRIVER=file
            QUEUE_CONNECTION=sync

```

        Obs.:  Deve ser colocado o nome, usuario e senha do usuário de PostgreSQL em DB_DATABASE, DB_USERNAME e DB_PASSWORD respectivamente baseado no nome do Banco de dados que foi realizada no passo anterior.

```
    Pronto! Seu banco de dados está criado e configurado para conversar com a aplicação Lumen.
```


#### 2 -  Configurando Lumen (Backend):

##### 2.1.1 Composer
``` 
    Dentro do diretório brasal-backend, instale os pacotes com o comando:
            $ composer install
``` S
##### 2.1.2 JWT Auth

```
    O sistema utiliza uma autenticação por token e portanto, é necessário o download e instalação da biblioteca jwt-auth, realizada pelo composer:

        $ composer require tymon/jwt-auth


    Gerar uma chave API para autenticação:

        $ php artisan jwt:secret
```
``````

##### 2.2 Tabelas
        Iremos utilizar o 'php artisan' para criar as tabelas e semear alguns dados no banco.
```   
Para criar as tabelas utilizamos o comando:

    $ php artisan migrate
 
    Agora vamos semear a tabela Status com o comando:

    $ php artisan db:seed --class=StatusSeeder
```

##### 2.3 Executar servidor:
``` 
    Pronto! O servidor está configurado e pronto para ser executado, para executá-lo utilize o comando: 

        $ php -S localhost:8000 -t public
    
    A aplicação será executada localmente e estará respondendo na porta 8000
``` 

#### 3 -  Configurando Vue.js (Frontend):

A configuração é feita com npm (node.js)
``` 
    Dentro do diretório brasal-frontend, instale os pacotes com o comando:
            $ npm install
``` 


```
Agora execute o servidor:

            $ npm run dev

E pronto! O servidor está configurado e rodando o frontend e backend, podemos acessar a aplicação frontend em localhost:5173 e usar suas funcionalidades;
```


````
Observações:
     1 - Para semear a tabela tarefa é necessário criar um usuário, isso pode ser feito pelo site no frontend na rota '/register', como a chave é criptografada tanto no frontend quanto no backend, é recomendado realizar essas operações no frontend e não em uma aplicação como Postman.

     2 - Ao utilizar aplicações REST como Postman, lembre-se de colocar o token no cabeçalho da requisição, sem o token a api não autoriza diversas rotas das tarefas. O token pode facilmente ser recuperado ao abrir o LocalStorage do frontend e copiar o valor da variável 'user'.

```` 

