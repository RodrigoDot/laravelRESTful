# Aprendendo a criar uma API RESTful usando Laravel e Angular4

<link rel="stylesheet" href="https://use.fontawesome.com/0b45c1657d.css" />

## Requerimentos

* PHP
* Composer (instalacao global)
* SGBD


## Importacao de pacotes

* Esqueleto de projeto Laravel
```console
composer create-project --prefer-dist laravel/laravel
```


## Configurar o banco de dados

* Em ``/.env`` altere os seguintes dados de acordo com as cofiguracoes do banco que voce ira utilizar
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravelr
DB_USERNAME=root
DB_PASSWORD=
```


## Criando as tabelas de Migration

Usando o console ou terminal de sua preferencia va ate ``/`` e execute o seguinte comando
```php
php artisan make:migration create_account
```
Agora va para ``/app/database/migrations``, encontre o arquivo que voce criou e edite. Voce pode utilizar os exemplos que ja vem com o esqueleto do Laravel ou pesquisar na documentacao oficial como criar os seus arquivos de migrations. Repita o processo para todas as tabelas que voce ira utilizar


## Criando as Seeds

Usando o console ou terminal de sua preferencia va ate ``/`` e execute o seguinte comando
```php
php artisan make:seed BanksTableSeeder
```
Agora va para ``/app/database/seeds``, encontre o arquivo que voce criou e edite.


## Utilizando as tabelas de Migration e as Seeds para gerar as tabelas e registros no banco de dados

Usando o console ou terminal de sua preferencia va ate ``/`` e execute o seguinte comando
```php
php artisan migrate
```
Esse comando ira criar as tabelas no banco de dados

Agora para criar os registros nas tabelas execute o seguinte comando
```php
php artisan db:seed
```

Caso aconteca de aparecer o erro que diz que a tabela nao existe execute o seguinte comando e em seguida execute o comando anterior
```php
composer dump-autoload
```


## Criando o CRUD

Usando o console ou terminal de sua preferencia va ate ``/`` e execute o seguinte comando
```php
php arisan make:controller Api/BanksController --resource
```
Agora va ate ``/app/http/controllers/api`` e encontre o arquivo que voce criou.


## Criando uma rota

Va para ``/routes/web.php`` e adicione o seguinte codigo
```php
Route::resource('/banks', 'Api\BanksController');
```
Agora utilizando o terminal ou console va ate ``/`` e execute o seguinte comando
```php
php artisan serve
```
Agora utilizando o browser acesse ``localhost:8000/banks`` e voce vera uma pagina em branco


## Criando uma Model

Usando o console ou terminal de sua preferencia va ate ``/`` e execute o seguinte comando
```php
php artisan make:model Banks
```




























x
