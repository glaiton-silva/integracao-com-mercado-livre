
# Integração com Mercado Livre

Este projeto realiza a integração com a API do Mercado Livre, permitindo a criação e gerenciamento de produtos diretamente na plataforma.

## Pré-requisitos

1. **Configuração do Mercado Livre:**
   Antes de utilizar o sistema, é necessário configurar os parâmetros do Mercado Livre. Acesse a plataforma do Mercado Livre e obtenha os dados de autenticação necessários.

2. **Autenticação:**
   Acesse a URL `/auth` para realizar a autenticação e obter o token necessário para realizar as operações com a API do Mercado Livre.

3. **Configuração do .env:**
   Cadastre os dados de autenticação do Mercado Livre no arquivo `.env`. Exemplo:

   ```
   APP_NAME=Laravel
   APP_ENV=local
   APP_KEY=base64:qVloyvkBFlU6rf56QSmj2kXFR3uBuQPAahvSK/ppnl0=
   APP_DEBUG=true
   APP_TIMEZONE=UTC
   APP_URL=http://localhost
   
   APP_LOCALE=en
   APP_FALLBACK_LOCALE=en
   APP_FAKER_LOCALE=en_US
   
   APP_MAINTENANCE_DRIVER=file
   # APP_MAINTENANCE_STORE=database
   
   PHP_CLI_SERVER_WORKERS=4
   
   BCRYPT_ROUNDS=12
   
   LOG_CHANNEL=stack
   LOG_STACK=single
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=debug
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mercado_livre
   DB_USERNAME=root
   DB_PASSWORD=root
   
   SESSION_DRIVER=database
   SESSION_LIFETIME=120
   SESSION_ENCRYPT=false
   SESSION_PATH=/
   SESSION_DOMAIN=null
   
   BROADCAST_CONNECTION=log
   FILESYSTEM_DISK=local
   QUEUE_CONNECTION=database
   
   CACHE_STORE=database
   CACHE_PREFIX=
   
   MEMCACHED_HOST=127.0.0.1
   
   REDIS_CLIENT=phpredis
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   
   MAIL_MAILER=log
   MAIL_SCHEME=null
   MAIL_HOST=127.0.0.1
   MAIL_PORT=2525
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   
   AWS_ACCESS_KEY_ID=
   AWS_SECRET_ACCESS_KEY=
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=
   AWS_USE_PATH_STYLE_ENDPOINT=false
   
   VITE_APP_NAME="${APP_NAME}"
   
   MERCADO_LIVRE_CLIENT_ID=
   MERCADO_LIVRE_CLIENT_SECRET=
   MERCADO_LIVRE_REDIRECT_URI=
   ```

## Passo a Passo da Instalação

1. Clone o repositório:

   ```
   git clone git@github.com:glaiton-silva/integracao-com-mercado-livre.git
   cd integracao-com-mercado-livre
   ```

2. Instale as dependências com o Composer:

   ```
   composer install
   ```

3. Instale as dependências do Vite:

   ```
   npm install
   ```

4. Configure o arquivo `.env` conforme as instruções acima.

5. Realize a autenticação acessando a URL `/auth` para gerar o token do Mercado Livre.

6. Execute as migrações:

   ```
   php artisan migrate
   ```

7. Agende a execução diária dos produtos e categorias no cron job configurado.

   **O agendamento será feito automaticamente.** O sistema trará os produtos e categorias do Mercado Livre a cada dia.

## Como Funciona

A cada execução, o **agendamento (schedule)** traz os produtos e categorias do Mercado Livre, garantindo que o sistema esteja sempre sincronizado com os dados da plataforma.

---

Caso tenha mais dúvidas ou problemas, não hesite em entrar em contato!
