Sensei is a modular-first, flexible system with no framework, built on minimalism, low abstraction, and simple logic.

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

- [Foundation](#foundation)
- [Installation](#installation)
- [Env](#env)
- [Container](#container)
- [Discovery](#discovery)
- [Controller](#controller)
- [Typed Request](#typed-request)

### Foundation

Sensei is a flexible system built with external libraries, enabling easy customization of its core functions.
The system is designed with minimal abstraction, focusing on simple code and a straightforward application flow.
The project follows a modular-first approach, with each module having its own folder in the src directory for easier management and expansion.
The source code is strictly typed, with PHPStan set to level 9 for early error detection and better IDE integration.
Predefined commands can be found in composer.json under the scripts section.

### Installation

```shell
composer create-project xtompie/sensei my-project
cd my-project
composer setup
```

### Env

The Env mechanism handles environment variables via a .env file, enabling configuration of items like database credentials or API keys without changing the source code.

All variables are defined in [App\Shared\Env\Env](https://github.com/xtompie/sensei/blob/master/src/Shared/Env/Env.php).

- `php console app:env:setup`: Initializes environment variables then they must be filled.
- `php console app:env:check`: Validates that the required environment variables are properly set.

### Container

Dependencies are automatically resolved by the container. Simply declare them in the constructor, and the container takes care of the rest.

The container used in the system is [xtompie/container](https://github.com/xtompie/container). Services are shared by default.

The container is connected to the system through [App\Shared\Container\Container](https://github.com/xtompie/sensei/blob/master/src/Shared/Container/Container.php), where bindings and providers are defined.

### Discovery

The Discovery mechanism scans the source code for classes with names that end with a specific suffix and that implement a given interface.

For console commands, discovery looks for classes in the `src/` directory with names ending in `Command` that implement the interface [App\Shared\Console\Command](https://github.com/xtompie/sensei/blob/master/src/Shared/Console/Command.php). This allows automatic discovery of new controllers by only adding one new file into code base.

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

Controllers are Single Action Controllers.

Routing is handled using the [symfony/routing](https://symfony.com/doc/current/routing.html).

Routes can be defined either via [ControllerMeta](https://github.com/xtompie/sensei/blob/master/src/Example/UI/Controller/MetaController.php) or with [Routing attributes](https://github.com/xtompie/sensei/blob/master/src/Shared/Http/Route) targeting class like code above.

The `__invoke()` method of a controller can request any dependencies from the container. If multiple methods need dependencies, it's better to inject them through the constructor. If only the `__invoke` method needs them, pass them as arguments to that method.

Route parameters can be accessed by defining them as arguments in the `__invoke()` method or by using `$request->getAttribute(string $name)`.

The request and response follow the [PSR-7](https://www.php-fig.org/psr/psr-7/) standard.

To create responses, use the methods in [App\Shared\Http\Response::*](https://github.com/xtompie/sensei/blob/master/src/Shared/Http/Response.php).

Response generation can be further modified in [App\Shared\Http\Kernel->response()](https://github.com/xtompie/sensei/blob/master/src/Shared/Http/Kernel.php).

### Typed Request

We have a DTO class which can be created from primitive using [xtompie/type](https://github.com/xtompie/typed)

```php
use Xtompie\Typed\Max;
use Xtompie\Typed\Min;
use Xtompie\Typed\NotBlank;
use Xtompie\Typed\Typed;

class CatBody
{
    public function __construct(
        #[NotBlank]
        protected string $name,

        #[NotBlank]
        #[Min(0)]
        #[Max(30)]
        protected int $age,
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function age(): int
    {
        return $this->age;
    }
}
```

In controller we handle it:

```php
use App\Shared\Http\Controller;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use App\Shared\Http\Route\Path;

#[Path('/example/typed')]
class TypedController implements Controller
{
    public function __invoke(Request $request): Response
    {
        $pet = Typed::object(CatBody::class, $request->body());
        if ($pet instanceof ErrorCollection) {
            Resposne::badRequest(errors: $pet);
        }

        return Response::json(['age' => $pet->age()]);
    }
}
```

The `__invoke` argument automatically resolves typed objects of type `App\Shared\Http\Contract\Body` and `App\Shared\Http\Contract\Query`.
If a typed argument cannot be resolved, a bad request response with errors is returned, and `__invoke` is not called.

```php
// ...
use App\Shared\Http\Contract\Body;

class PetBody implements Body
{
    // ...
}

// ...

#[Path('/example/typed')]
class TypedController implements Controller
{
    public function __invoke(PetBody $pet): Response
    {
        return Response::json(['age' => $pet->age()]);
    }
}
```
