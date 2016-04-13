# kontuak-rest-api
A REST API client for the project [Kontuak](https://github.com/AitorGuerrero/kontuak). It includes the domain code through [composer](https://getcomposer.org/).

The idea is that the domain being totally decoupled from the REST API, it could (and will) be integrated also in a [RPC](https://en.wikipedia.org/wiki/Remote_procedure_call) API.

Based in Symfony2, FOSRestBundle and APIDocBundle.

## Init
- [Download / install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
- `$ composer install`
- `$ app/console server:run`
- Go to [http://127.0.0.1:8000/api/doc]()