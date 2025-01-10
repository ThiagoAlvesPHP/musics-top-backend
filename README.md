# Musics Top

## Referência
[Projeto - GitHub](https://github.com/jansenfelipe/top5-tiao-carreiro)

## Tecnologias

Tecnologias usadas no projeto:

  * Laravel
  * PHP
  * Composer
  * Docker & Laravel Sail

## Serviços Usados

Consulta se a URL do Youtube era valida e captura dos dados do vídeo do Youtube.

## Para Iniciar

  * Ambiente:
    - É necessário que o docker esteja instalado para uma instalação rápida
    - É necessário ter o composer
  
  * Instalação:
    - Instale as dependencias do projeto na raiz do mesmo com o seguinte comando: `composer install`
    - Copie e cole o arquivo `.env.example` e o renomeie para `.env`
    - Descomente as chaves com as iniciai `DB_` do arquivo `.env` e troque o valor da chave `DB_CONNECTION` para `mysql`
    - Execute o comando do sail para gerar a build das imagens e iniciar os containers e o projeto: `./vendor/bin/sail up -d`
    - Execute o comando `./vendor/bin/sail artisan key:generate` para gerar uma chave para o seu projeto Laravel
    - Execute o comando `./vendor/bin/sail artisan jwt:secret` para gerar uma chave para assinar os seus tokens
    - Execute o comando `./vendor/bin/sail artisan migrate` para criar as tabelas do banco de dados
    - Execute o comando `./vendor/bin/sail artisan db:seed` para executar o seed do popular a tabela de usuário com uma conta administrativa

        *Credenciais da Conta*

        Email: `admin@musicstop.com.br` | Senha: `admin123`

  * Uso:
    - Para acessar o projeto basta [Clicar Aqui](http://0.0.0.0/)
    - Para acessar o dashboard a rota é em `/admin`

### Rotas

![Rota de Login](https://github.com/ThiagoAlvesPHP/musics-top-backend/blob/master/readme/login.png)
![Rota de Registro de Usuário](https://github.com/ThiagoAlvesPHP/musics-top-backend/blob/master/readme/register_user.png)
![Rota de Registro da Música](https://github.com/ThiagoAlvesPHP/musics-top-backend/blob/master/readme/register_music.png)
![Rota de Listar as Músicas](https://github.com/ThiagoAlvesPHP/musics-top-backend/blob/master/readme/musics.png)
![Rota de Ver a Música](https://github.com/ThiagoAlvesPHP/musics-top-backend/blob/master/readme/music.png)

