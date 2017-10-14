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

Precisamos tambem criar a rora que sera utilizada para a Api
Va para ``/routes/api.php`` e adicione o seguinte codigo
```php
Route::resource('/banks', 'Api\BanksController');
```
para accessar essa rota basta utilizar ``/api/banks`` no browser



## Criando uma Model

Usando o console ou terminal de sua preferencia va ate ``/`` e execute o seguinte comando
```php
php artisan make:model Banks
```


## Acessando o banco de dados **GET**

Va ate ``/app/http/controllers/api/BanksController``, dentro da action 'index' digite o seguinte codigo
```php
$data = \App\Bank::paginate();
return response()->json($data);
```
Acima acessamos a model ``Bank`` e utilizamos o metodo ``paginate()`` para fazer um select no banco e retornar os registros encontrados de forma organizada atribuindo esse resultado a variavel ``$data``.

Depois retornamos esses dados em formato json utilizando o metodo ``response()`` e o metodo ``json()`` passando a variavel ``$data`` para formatar os dados.


## Salvando dados no banco de dados **POST**

Va ate ``/app/http/controllers/api/BanksController``, dentro da action 'store' digite o seguinte codigo
```php
$data = \App\Bank::create($request->all());
return response()->json($data);
```
Acima acessamos a model ``Bank`` e utilizamos o metodo ``create()`` para salvar no banco os valores passados como parametro, no caso passamos como parametro todos os dados que vieram na requisicao ``$request`` utilizando o metodo ``all()``. Por fim retornamos os dados em formato json, essa ultima parte serve somente para as etapas de desenvolvimento.


## Editando e atualizando dados do banco de dados **PUT**

Va ate ``/app/http/controllers/api/BanksController``, dentro da action 'store' digite o seguinte codigo
```php
$data = \App\Bank::findOrFail($id);
$data->update($request->all());
return response()->json($data);
```
Acima acessamos a model ``Bank`` e utilizamos o metodo ``findOrFail($id)`` para procurar no banco somente o registro com o id em questao. Depois utilizamos o metodo ``update()`` para atualizar os dados no banco com todos os dados que foram passados como parametro ``($request->all())`` que no caso foram todos os dados contidos em ``$request``, o metodo ``all()`` ja e bastante conhecido nosso, ele retorna todos os valores da ``$request``. Por fim retornamos os dados em formato json para fins de teste durante o desenvolvimento.


## Deletando dados no banco de dados **DELETE**

Va ate ``/app/http/controllers/api/BanksController``, dentro da action 'delete' digite o seguinte codigo
```php
\App\Bank::destroy($id);
return response()->json(['status' => 'registro deletado']);
```
Acima acessamos a model ``Bank`` e utilizamos o metodo ``delete()`` passando o id do registro que deve ser deletado como parametro. Por fim retornamos uma mensagem com status.






### REQUISICOES

#### GET

* requisicao get para listas dados utilizando a action index
``/banks``

* requisicao get para visualizar apenas um registro utilizando a action show
``/banks/2``  >>  controller/id

* requisicao get com limitador
``/bank?limit=10``

* requisicao get com ordenacao
``/banks?id,asc`` >> campo,ordem
``/banks?id,desc``
``/banks?code,asc``
``/banks?code,desc``

* requisicao get combinada limite e ordenacao
``/bank?limit=10&order=id,desc``

* requisicao get por campo especifico (where)
``/banks?where[id]=2``  >> where[campo]=valor

* requisicao get usando like
``/banks?like=title,teste`` >> like=campo,valor


#### POST

* requisicao post para inserir um banco
``/api/banks``  >> prefixo/controller
Opcao 1 - Utilizando o POSTMAN basta selecionar a opcao 'POST' e o formato de envio 'x-www-form-urlencoded' e depois preencher os campos que devem ser salvos no banco de dados Utilizando as opcoes chave = valor na opcao 'BODY' do POSTMAN.

Opcao 2 - Utilizando o POSTMAN basta selecionar a opcao 'POST' e o formato de envio 'raw'. Nesse metodo e necessario passar os cabecalhos
```js
[
  {"key":"Accept","value":"application/json"},
  {"key":"Content-Type","value":"application/json"}
]
```
Que podem ser passados na area de 'headers' do POSTMAN. E ainda e necessario passar os dados atraves do 'BODY' em formato json
```js
{
  "title": "Banco Json",
  "code": 200
}
```


#### PUT

* requisicao put para atualizacao de dados no banco de dados
``/api/banks/2``  >> prefixo/controller/id

Para atualizar os dados fazemos basicamente o mesmo processo de quando estamos inserindo dados no banco, a diferenca e que aqui utilizamos o metodo htttp PUT e passamos o id do registro a ser atualizado. No mais todos os procedimentos sao os mesmos que na insercao de dados. Cabecalhos e dados continuam sendo necessarios em ambos os metodos 'raw' e 'urlencoded'.































x
