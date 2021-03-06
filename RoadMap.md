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


## Ate aqui ja temos todo o nosso CRUD funcionando


## Criando uma Trait

Uma Trait e uma classe que pode ser carregada dentro de outra classe sem precisar ser extendida. Uma vez carregada dentro da classe utilizando o namespace ``use`` e possivel utilizar todos os metodos disponiveis na Trait. Dessa forma podemos criar uma unica classe com os metodos do nosso CRUD e carrega-la em todos os nossos controllers modificando somente a model que sera utilizada. Fazermos isso durante o carregamento da Trait ao criar o ``__construct`` da classe que vai importar a Trait.

Va ate ``/app/http/controllers/api/BanksController`` e recorte todo o codigo interno a classe, o arquivo tem que ficar assim:
```php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BanksController extends Controller
{
  //nada aqui
}
```

Agora crie um novo aquivo chamado ``ApiControllerTrait.php`` em ``/app/http/controllers``. Declare o namespace ``App\Http\Controllers``, carregue o http request ``Illuminate\Http\Request`` e  declare um trait com o mesmo nome do arquivo:
```php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

trait ApiControllerTrait {}
```
Dentro dessa classe cole todo o codigo que foi recortado anteriormente do arquivo ``app/http/controllers/api/BanksController.php``. Agora substituia todas as chamadas da model como essa ``\App\Bank`` para ``$this->model``.

Va ate ``app/http/controllers/api/BanksController.php`` e adicione o seguinte codigo dentro da classe:
```php

use \App\Http\Controllers\ApiControllerTrait; //soh pra garantir. Isso e dentro das "{}" da classe

protected $model;

public function __construct(\App\Bank $model) {

  $this->model = $model;

}
```


## Adicionando relacionamento entre models

Va para ``app/Account.php`` digite o seguinte codigo:
```php
public function bank() {
  return $this->belongsTo('App\Bank');
}
```
Acima criamos uma funcao com o nome da model que queremos relacionar ``Bank``. Como resultado da funcao retornamos a model em questao atraves do metodo ``belongsTo()`` passando a model como parametro ``\App\Bank``.

Agora quando formos fazer requisicoes no banco onde exista um relacionamento entre essas duas models utilizaremos o metodo ``with()`` para retornar os registros referentes a essa relacao. Por exemplo:
```php
public function show($id)
{
  $data = $this->model->with(['bank'])->findOrFail($id);

    return response()->json($data);
}
```  
Utilizamos ``with(['bank'])`` para executar a funcao ``bank`` criada na model ``Account`` e assim retornar os dados da relacao.  


## Aprimoramento do relacionamento

A funcao de relacionamento anterior funcionava perfeitamente quando e requisitado atraves do controller ``accounts``, ja que a tabela ``account`` tem um relacionamento com a tabela ``bank``. Mas quando requisitamos atraves do controler ``banks`` isso gera um erro ja que ``bank`` nao tem um relacionamento com a tabela ``account`` diretamente. Logo teremos que modificar o sistema de relacionamento para identificar essas diferencas.

Va ate ``app/http/api/AccountsController.php`` e adicione o seguinte codigo logo apos a variavel protegida ``$model``.
```php
protected $relationships = ['bank'];
```
Aqui adicionamos um novo atributo ``$relationships`` que recebe o valor padrao ``['bank']`` que corresponde a tabela ``bank`` que queremos buscar os registro relacionados quando fizermos uma requisicao na tabela ``acount``.

Agora va ate ``app/http/Controllers/AccountsController.php`` e altere a action "show" para ficar da seguinte forma.
```php
public function show($id)
{
  $data = $this->model->with($this->relationships())
    ->findOrFail($id);

    return response()->json($data);
}
```
Dentro do metodo ``with()`` foi alterado o parametro passado que antes era ``['bank']`` e agora e uma chamada a um novo metodo ``relationships()`` que ainda iremos criar.

Agora no fim desse mesmo arquivo adicione o seguinte codigo.
```php
public function relationships() {
  if(isset($this->relationships)) {
    return $this->relationships;
  }else {
    return [];
  }
}
```
Esse metodo verifica se o metodo ``relationships()`` retorna algum valor valido, que no caso seria o valor declarado na variavel protegida ``protected $relationships = ['bank'];`` declarada dentro do arquivo correspondente ao controller de "Accounts" . caso ele retorne um valor valido, esse valor e utilizado para fazer uma requisicao que retorna registros do banco, caso nao exista valor ele nao retornara nada, assim quando executamos esse codigo pelo controller ``banks``, esse metodo nao retorna nada ja que nao declaramos nenhuma variavel ``$relationships`` nesse controller.


## Desenvolvendo a autenticacao

Primeiro instalamos o "Passport"
```php
composer require laravel/passport
```
Agora va ate ``config/app.php`` e registre o servico. Digite o seguinte codigo dentro do array da ``providers``.
```php
Laravel\Passport\PassportServiceProvider::class,        
```
Apos baixados os arquivos do Passport ele ira gerar algumas tabelas de Migrations que devem ser criadas no banco. Para isso execute o seguinte comando.
```php
php artisan migrate
```
O proximo passo e instalar o Passport. Para isso execute  o seguinte codigo.
```php
php artisan passport:install
```
Ao executar o "install" o passport ira gerar alguns ID's e algumas senhas que devem ser salvas em um lugar para que voce possa utilizar depois.
Pronto agora temos nossas tabelas e tudo pronto para comecar trabalhar com o Passport.


## configurando o Passport

Va ate ``app/User.php`` e adicione uma chamada para o namespace do Passport.
```php
use Laravel\Passport\HasApiTokens;
```
Essa classe sera utilizada como uma Trait assim como fizemos com o controller da nossa API. Para isso dentro da classe ``User`` adicione ``HasApiTokens`` ao lado de ``Notifiable``.

Agra va ate ``config/auth.php`` e navegue a te encontrar um array de configuracoes chamado ``guards``. Haverao dois elementos, "web" e "api" cada um define as configuracoes de suas respectivas plataformas. Aqui iremos alterar o valor do elemento "DRIVER" de "tokens" para "passport" no elemento "API".  


Agora precisamos carregar o servico para monitorar nossas rotas.
Va ate ``app/providers/AuthServiceProvider.php``. Primeiro carregaremos o passport atraves de uma chamada ao seu namespace.
```php
use Laravel\Passport\Passport;
```  
Agora dentro do metodo ``boot`` faremos uma chamada ao metodo ``routes()`` da classe Passport que carregamos acima. O codigo do metodo ficara assim:
```php
public function boot()
{
  $this->registerPolicies();
  Passport::routes();
}
```


## Ativando o registro de usuarios

O servico de usuarios (AUTH) nao precisa do Passport para funcionar, ele e uma ferramenta padrao do Laravel Framework. Ou seja voce pode usar mesmo sem o Passport.
Execute o seguinte comando.
```php
php artisan make:auth
```
Com esse comando o Artisan gera todos os arquivos necessarios para que voce possa cadastrar e manipular os registros de usuarios. basta acessar a url ``localhost:8000/`` e voce vera os links do canto superior direito da tela.


## Configuracoes de rotas para o Passport

Agora vamos configurar as rotas para que o Passport possa ter acesso ao que e acessado dentro da aplicacao atraves das rotas e garante assim o controle do acesso para as areas da aplicacao de acordo com as configuracoes de acesso.

Va ate ``routes/api.php`` e altere as rotas digitadas para ficar da seguinte forma.
```php
Route::group(['middleware' => ['auth:api']], function() {
      Route::middleware('auth:api')->get('/user', function (Request $request) {
          return $request->user();
      });

      Route::resource('/banks', 'Api\BanksController');
      Route::resource('/accounts', 'Api\AccountsController');
    }
);
```
Dessa forma o Passport podera verificar se ha usuario logado e se esse usuairo tem permissao para acessar as rotas dentro desse grupo de rotas.

Verifique na sessao de REQUISICOES como realiza-las apos adicionar o Passport.


## Resolvendo problemas de CORS

Erros de CORS acontecem quando voce tenta acessar um servidor atraves de outro servidor externo. Para resolver esse problema precisamos realizar algumas configuracoes no Laravel para que isso seja permitido.

Para fazer isso podemos configurar o Apache ou o webserver que estamos usando, mas aqui faremos utilizando um plugin.
```php
composer require barryvdh/laravel-cors
```
Uma vez instalado o plugin va ate ``/config/app.php`` e adicione o seguinte codigo dentro do array de providers.
```php
Barryvdh\Cors\ServiceProvider::class,
```
Agora precisamos gerar os arquivos de configuracao do cors.
```php
php artisan vendor:publish --provider="Barryvdh\Cors\ServiceProvider"
```
Por ultimo va ate ``/routes/api.php`` para adicionar o middleware que rodara antes do auth para evitar que aconteca o erro de cors. Dentro do grupo de rotas da sua api altere a seguinte linha.
```php
Route::group([
  'middleware' => ['cors', 'auth:api']
  ]
```
Va ate ``/app/http/kernel.php`` e adicione ``'cors' => \Barryvdh\Cors\HandleCors::class,`` dentro do array "$routeMiddleware".


### REQUISICOES

#### GET

Requisicao get para listas dados utilizando a action index

``/banks``

Requisicao get para visualizar apenas um registro utilizando a action show

``/banks/2``  >>  controller/id

Requisicao get com limitador

``/bank?limit=10``

Requisicao get com ordenacao

``/banks?id,asc`` >> campo,ordem

``/banks?id,desc``

``/banks?code,asc``

``/banks?code,desc``

Requisicao get combinada limite e ordenacao

``/bank?limit=10&order=id,desc``

Requisicao get por campo especifico (where)

``/banks?where[id]=2``  >> where[campo]=valor

Requisicao get usando like

``/banks?like=title,teste`` >> like=campo,valor


#### POST

Requisicao post para inserir um banco

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

Requisicao put para atualizacao de dados no banco de dados

``/api/banks/2``  >> prefixo/controller/id

Para atualizar os dados fazemos basicamente o mesmo processo de quando estamos inserindo dados no banco, a diferenca e que aqui utilizamos o metodo htttp PUT e passamos o id do registro a ser atualizado. No mais todos os procedimentos sao os mesmos que na insercao de dados. Cabecalhos e dados continuam sendo necessarios em ambos os metodos 'raw' e 'urlencoded'.


#### DELETE

Requisicao delete para deletar dados no banco de dados

``api/banks/2``  >> prefixo/controller/id

Para deletar dados do banco utilizamos o metodo http delete. Aqui junto com a url so precisamos passar o cabecalho  
```js
[
  {"key":"Content-Type","value":"application/json"}
]
```


## Requisicoes Passport

#### Requisitar token de acesso

Com o Passport devidamente instalado e com todas as suas configuracoes corretas, nos basta fazer requisicoes. Funciona da seguinte forma, faremos uma requisicao ao servidor utilizando um dos ID's e senha gerados na instalacao do Passport para conseguir um token para que com ele possamos ter acesso a API e assim consumir os dados por ela providos.

Primeiro de tudo, como estamos utilizando o Auth para interceptar a rota, precisamos requisitar um token para que ele libere o acesso aos dados.

Utilizando o POSTMAN, selecionaremos o metodo POST e o metodo de envio ``x-www-form-urlencoded`` para enviar alguns dados no body para que o Passport valide o usuario sao eles:

**Dados passados no body**

| Chave | Valor |
| - | - |
| grant_type | password |
| client_id | 2 |
| client_secret | sdffwlkrkjdsklfewf |
| username | rodrigo |
| password | 123456 |
| scope | vazio |

**O que representam as chaves e os valores**

| chave | o que e |
| - | - |
| grant_type | e o campo que sera usado para autenticar |
| client_id | e o id do cliente gerado pelo Passport no ato da instalacao |
| client_secret | e a senha gerada pelo Passport no ato da instalacao |
| username | email do usuario registrado na aplicacao |
| password | senha do usuario |
| scope | vazio |

Apos configurarmos os dados necessarios, basta requisitar nosso token para ter acesso a aplicacao. Por padrao a rota que deve ser acessada para isso e ``localhost:8000/oauth/token`` basta enviar um POST seguindo as instrucoes acima.


#### Requisicoes Passport

A unica mudanca em relacao as requisicoes que faziamos antes sem o Passport ativado e que agora precisaremos enviar na requisicao um header ``Authorization`` com o valor do token recebido pelo servidor precedido pelo tipo do token, exemplo ``Bearer flfsase54f45fwf5fwf``. Fica assim:

| chave | valor |
| - | - |
| Authorization | Bearer dfdfsf4sfs545sf54 |

Com isso configurado basta realizar as requisicoes como eram realizadas anteriormente ao Passport.













x
