
Sensei is a modular first flexible system with no framework, built on minimalism, low abstraction, and simple logic.

```php
use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

#[Path('/lucky/number')]
class LuckyController implements Controller
{
    public function __invoke(): Response
    {
        $number = random_int(0, 100);
        return Response::html('<html><body>Lucky number: ' . $number . '</body></html>');
    }
}
```

### Foundation

Sensei is a flexible system built using external libraries, allowing for easy customization of its core functions.

The system is designed with minimal abstraction, using simple code and a straightforward application flow.

The project uses a **modular-first** approach. Each module has its own folder in the `src` directory, making it easy to manage and expand the application.

The source code is strictly typed, and the project is configured with PHPStan at level 9, ensuring early error detection and better integration with IDEs.

There are also predefined commands check composer.json -> scripts.

### Installation

```shell
composer create-project xtompie/sensei my-project
cd my-project
composer setup
```

### Env

The Env mechanism manages environment variables through a `.env` file. It allows configuration of variables such as database credentials or API keys without altering the source code.

All variables are defined in `App\Shared\Env\Env` ([App\Shared\Env\Env](https://github.com/xtompie/sensei/tree/master/src/Shared/Env/Env.php)).

There are two commands related to environment management:

- `php console app:env:setup`: Initializes environment variables then they must be filled.
- `php console app:env:check`: Validates that the required environment variables are properly set.

### Container

Dependencies are resolved automatically by the container. It is enough to declare them in the constructor, and the container will handle the rest.

The container used in the system is [xtompie/container](https://github.com/xtompie/container). Services are shared by default.

The container is connected to the system through `App\Shared\Container\Container` ([App\Shared\Container\Container](https://github.com/xtompie/sensei/tree/master/src/App/Shared/Container/Container.php)), where bindings and providers are defined.

### Discovery

The Discovery mechanism scans the source code for classes with names that end with a specific suffix and that implement a given interface.

For controllers, discovery looks for classes in the `src/` directory with names ending in `Controller` that implement the interface `App\Shared\Http\Controller`. This allows automatic discovery of new controllers by simply adding a class.

### Controller

```php
use App\Shared\Http\Controller;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Route\GET;
use App\Shared\Http\Route\POST;

#[Path('/article/{id}'), GET, POST]
class ArticleController implements Controller
{
    public function __invoke(string $id, Request $request): Response
    {
        // argument id or $id = $request->getAttribute('id');
        if ($id !== '1') {
            return Response::notFound();
        }
        return Response::ok('1');
    }
}
```

Controllers are resolved through `App\Registry\Http::controllers()` and the Discovery mechanism, which searches for classes with the suffix `Controller` that implement `App\Shared\Http\Controller`.

Routing is handled using the `symfony/routing` package ([Symfony Routing Documentation](https://symfony.com/doc/current/routing.html)).

Routes can be defined either via `ControllerMeta` e.g., [MetaController](https://github.com/xtompie/sensei/blob/master/src/Example/UI/Controller/MetaController.php) or with attributes like code above.

The `__invoke()` method can request any dependencies from the container.

Parameters from the route can be accessed by defining them as arguments in the `__invoke()` method or by calling `$request->getAttribute(string $name)`.

The request and response follows the PSR-7 standard.

To create responses, use the methods available in `App\Shared\Http\Response::*`.

Response generation can be further modified in [App\Shared\Http\Kernel->response()](https://github.com/xtompie/sensei/tree/master/src/Shared/Http/Kernel.php).