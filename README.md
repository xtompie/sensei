# Sensei

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

- [Sensei](#sensei)
  - [Foundation](#foundation)
  - [Installation](#installation)
  - [Documentation](#documentation)
    - [Http](#http)
    - [Console](#console)
    - [Env](#env)
    - [Container](#container)
    - [Discovery](#discovery)
    - [Controller](#controller)
    - [Typed Request](#typed-request)
    - [Console Command](#console-command)
    - [AOP](#aop)
    - [DB](#db)

## Foundation

Sensei is a flexible system built with external libraries, enabling easy customization of its core functions.
The system is designed with minimal abstraction, focusing on simple code and a straightforward application flow.
Each module having its own folder in the src directory for easier management and expansion.
The source code is strictly typed, with PHPStan set to level 9 for early error detection and better IDE integration.
Predefined commands can be found in composer.json under the scripts section.

## Installation

```shell
composer create-project xtompie/sensei my-project
cd my-project
composer setup
```

## Documentation

### Http

`composer serve` - Starts developer server

### Console

`php console` - Runs application console

### Env

The Env mechanism handles environment variables via a .env file, enabling configuration of items like database credentials or API keys without changing the source code.

All variables are defined in [App\Shared\Env\Env](https://github.com/xtompie/sensei/blob/master/src/Shared/Env/Env.php).

- `php console app:env:setup`: Initializes environment variables then they must be filled.
- `php console app:env:check`: Validates that the required environment variables are properly set.

### Container

Dependencies are automatically resolved by the container. Simply declare them in the constructor, and the container takes care of the rest.

The container used in the application is [xtompie/container](https://github.com/xtompie/container). Services are shared by default.

The container is connected to the application through [App\Shared\Container\Container](https://github.com/xtompie/sensei/blob/master/src/Shared/Container/Container.php), where bindings and providers are defined.

### Discovery

The Discovery mechanism scans the source code for classes with names that end with a specific suffix and that implement a given interface.

For console commands, discovery looks for classes in the `src/` directory with names ending in `Command` that implement the interface [App\Shared\Console\Command](https://github.com/xtompie/sensei/blob/master/src/Shared/Console/Command.php). This allows automatic discovery of new command by only adding one new file into code base.

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

Routes can be defined either via [ControllerMeta](https://github.com/xtompie/sensei/blob/master/src/Example/UI/Controller/MetaController.php) or with [Routing attributes](https://github.com/xtompie/sensei/blob/master/src/Shared/Http/Route) targeting class like in code above.

The `__invoke()` method of a controller can request any dependencies from the container. If multiple methods need dependencies, it's better to inject them through the constructor. If only the `__invoke` method needs them, pass them as arguments to that method.

Route parameters can be accessed by defining them as arguments in the `__invoke()` method or by using `$request->getAttribute(string $name)`.

The request and response follow the [PSR-7](https://www.php-fig.org/psr/psr-7/) standard.

To create responses, use the methods in [App\Shared\Http\Response::*](https://github.com/xtompie/sensei/blob/master/src/Shared/Http/Response.php).

Handling controller result can be further modified in [App\Shared\Http\Kernel](https://github.com/xtompie/sensei/blob/master/src/Shared/Http/Kernel.php) i nethod `response`.

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

### Console Command

```php
use App\Shared\Console\Command;
use App\Shared\Console\Output;
use App\Shared\Console\Signature\Argument;
use App\Shared\Console\Signature\Description;
use App\Shared\Console\Signature\Name;
use App\Shared\Console\Signature\OptionOptional;

#[Name('pet:update')]
#[Description('Updates pet properties')]
#[Argument('id', 'ID of pet')]
#[OptionOptional('name', 'New name')]
#[OptionOptional('desc', 'New Description')]
class UpdatePetCommand implements Command
{
    public function __invoke(string $id, string $name, string $desc, Output $output): void
    {
        // ...
        $output->writeln('Updated');
    }
}
```

Commands are resolved through `App\Registry\Console::commands()` and the Discovery mechanism, which searches for classes with the suffix `Command` that implement `App\Shared\Console\Command`.

Application console is handled using the [symfony/console](https://symfony.com/doc/current/console.html).

Command singnature can be defined either via CommandMeta or with [Singnature attributes](https://github.com/xtompie/sensei/blob/master/src/Shared/Console/Signature) targeting class like in code above.

The `__invoke()` method of a command can request any dependencies from the container. If multiple methods need dependencies, it's better to inject them through the constructor. If only the `__invoke` method needs them, pass them as arguments to that method.

Command arguments and options can be accessed by defining them as arguments in the `__invoke()` method or by using [Input](https://github.com/xtompie/sensei/blob/master/src/Shared/Console/Input.php).

[Output](https://github.com/xtompie/sensei/blob/master/src/Shared/Console/Output.php) for generating output.

Exit code can be set by [ExitCode](https://github.com/xtompie/sensei/blob/master/src/Shared/Console/ExitCode.php) or just __invoke can return int.

Handling command result can be further modified in [\App\Shared\Console\Bridge](https://github.com/xtompie/sensei/blob/master/src/Shared/Console/Bridge.php) in method `result`.

### AOP

AOP - Aspect-Oriented Programming

```php
use App\Shared\Http\Controller;
use App\Shared\Http\Route\Path;
use App\Shared\Http\Response;

#[Path('/example')]
class ExampleController implements Controller
{
    #[Api]
    public function __invoke(): Response
    {
        return Response::ok('Foobar');
    }
}

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use App\Shared\Http\Request;
use App\Shared\Http\Response;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Api implements Advice
{
    public function __invoke(Invocation $invocation, Request $request): mixed
    {
        if ($request->bearer() !== 'secret1234') {
            return Request::forbidden();
        }

        return $invocation();
    }
}
```

To Controllers and Commands `__invoke` and Aop Advice can be added.
All advice works as AOP Around / Middleware / Chain.

For other methods, other Services content must be sourrended with Aop pointcut e.g.

```php
use App\Shared\Aop\Aop;

class LuckyNumber
{
    #[Echo]
    public function __invoke(): int
    {
        return Aop::aop(__METHOD__, get_func_args(), function () {
            return rand(1, 100);
        });
    }
}

use App\Shared\Aop\Advice;
use App\Shared\Aop\Invocation;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Echo implements Advice
{
    public function __invoke(Invocation $invocation): mixed
    {
        $result = $invocation();
        echo "$result\n";
        return $result
    }
}

$number = new LuckyNumber();
$number(); // prints: 42
$number(); // prints: 13
```

`LuckyNumber->__invoke` contents is sourrended with Aop pointcut.
Then all Attributes of type `Advice` assigned `LuckyNumber->__invoke` are executed.
In `Advice->__invoke()` there is special arugment `Invocation $invocation` which is an chain of all advices and orignal function main content.
The `__invoke()` method of a Advice can request any dependencies from the container.
Controllers and Command

### DB

Schema is resolved through Discovery mechanism, which searches for classes with the suffix `Schema` that implement `App\Shared\Schema\Schema`. E.g.

```php
namespace App\Article\Infrastructure\Schema;

use App\Shared\Schema\Column;
use App\Shared\Schema\Schema;
use App\Shared\Schema\Table;
use App\Shared\Schema\StringType;
use App\Shared\Schema\TextType;
use Generator;

class TenantSchema implements Schema
{
    /**
     * @return Generator<Table>
     */
    public function tables(): Generator
    {
        yield new Table(
            name: 'article',
            columns: [
                new Column(name: 'id', type: new StringType(), primary: true),
                new Column(name: 'title', type: new StringType(), primary: true),
                new Column(name: 'body', type: new TextType(), primary: true),
            ],
        );
    }
}
```

- `php console app:db:diff` - Migrations are generated based on the differences between the schema defined in the classes and the schema in the database.

- `php console app:db:migrate` - Applies all pending database migrations to synchronize the schema with the latest changes.

[xtompie/dao](https://github.com/xtompie/dao) is used for executing database queries.

[xtompie/aql](https://github.com/xtompie/aql) is used for building query statements.
