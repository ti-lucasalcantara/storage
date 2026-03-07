# Testes da API de Storage de Arquivos

## Explicação rápida (para iniciantes)

### O que são testes no CodeIgniter 4?

Testes automatizados são código que **chama** sua aplicação (ou partes dela) e **verifica** se o resultado é o esperado. Em vez de testar tudo na mão no Postman, você escreve cenários uma vez e roda com um comando; se algo quebrar no futuro, o teste avisa.

O CI4 usa **PHPUnit** como motor de testes. Os arquivos de teste ficam na pasta `tests/` e cada classe estende um *TestCase* e usa *traits* para banco de dados e requisições HTTP.

### Teste unitário x teste de feature

- **Teste unitário**: testa uma **unidade** isolada (uma classe, um método), muitas vezes com dependências “mockadas”. Ex.: testar só o método `gerarNomeHash()` do `StorageArquivoService`, sem passar pela rota nem pelo banco real.
- **Teste de feature**: testa um **fluxo completo** como o usuário/sistema usa: uma requisição HTTP (GET, POST, etc.) à sua API e a resposta (status, JSON, arquivo). Passa por rotas, controller, service, model e banco.

### Por que começar com teste de feature aqui?

Para a API de Storage, o que importa é: “quando eu mando POST /arquivos com um arquivo, a API grava e devolve o esperado?” e “quando mando GET /arquivos/1/download, recebo o arquivo?”. Isso é um **fluxo completo**, então **testes de feature** são mais adequados e didáticos: você vê a API funcionando de ponta a ponta, com banco e arquivos, sem precisar simular tudo na mão.

---

## Estrutura dos testes

```
tests/
├── TESTES_STORAGE.md                 ← este arquivo (explicação e como rodar)
├── _support/
│   └── Storage/
│       ├── AuxiliarStorageTest.php   ← criação de arquivos fake e limpeza
│       └── UploadedFileParaTeste.php ← UploadedFile para testes (move/isValid)
└── feature/
    └── ArquivosApiTest.php           ← testes de feature da API
```

- **AuxiliarStorageTest**: cria arquivos temporários para upload e limpa o diretório de storage usado nos testes (`teste/phpunit`), para não poluir o storage “real”.
- **ArquivosApiTest**: faz as requisições (POST, GET, DELETE) e verifica respostas HTTP, JSON e banco.

---

## Banco de dados de teste

O CI4, em ambiente de teste (`ENVIRONMENT === 'testing'`), usa o grupo de conexão **`tests`** definido em `app/Config/Database.php`. O padrão do projeto é SQLite em memória (`:memory:`), ou seja:

- Nenhum banco físico é obrigatório para rodar os testes.
- As migrations (incluindo a que cria `tb_storage_arquivos`) rodam automaticamente antes dos testes que usam `DatabaseTestTrait`.
- Os dados são descartados ao final da execução.

Para usar MySQL/MariaDB em testes (opcional), configure no `Database.php` o grupo `tests` com host, usuário, senha e database de teste, ou use variáveis de ambiente no `phpunit.xml`.

- **Upload com sucesso (Teste 1)**: como em ambiente de teste o request do CI4 não popula `$_FILES` para multipart, o fluxo de upload é testado **via Service**: criamos um arquivo temporário, envolvemos em `UploadedFileParaTeste` (que usa `copy` em vez de `move_uploaded_file` e `isValid()` compatível com arquivo local) e chamamos `StorageArquivoService::salvarArquivoEnviado()`. Assim validamos registro no banco, arquivo no disco e resposta.

---

## Arquivos temporários e limpeza

- Os testes de upload usam arquivos **temporários** criados em `sys_get_temp_dir()` (ex.: um PDF fake com conteúdo mínimo).
- Os arquivos **salvos** pela API durante os testes vão para o mesmo diretório de storage da aplicação, dentro do segmento **`teste/phpunit/`** (sistema = `teste`, módulo = `phpunit`).
- Após os testes, a classe auxiliar **remove** a pasta `writable/storage/teste/` para não deixar lixo no seu storage real.

---

## Como rodar os testes

Na raiz do projeto (onde está o `composer.json`):

### Todos os testes do projeto

```bash
composer test
```

ou:

```bash
vendor\bin\phpunit
```

(No Windows use `vendor\bin\phpunit`.)

### Só a classe de testes da API de Storage

```bash
vendor\bin\phpunit tests/feature/ArquivosApiTest.php
```

### Um único método de teste

```bash
vendor\bin\phpunit tests/feature/ArquivosApiTest.php --filter testUploadComSucesso
```

Substitua `testUploadComSucesso` pelo nome do método que quiser (ex.: `testDownloadDeArquivoInexistente`, `testExclusaoLogicaAlteraStatusEDeletedAt`).

### Com saída mais legível (testdox)

```bash
vendor\bin\phpunit tests/feature/ArquivosApiTest.php --testdox
```

Isso mostra os nomes dos testes em formato de frase, facilitando entender o que passou ou falhou.
