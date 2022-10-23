![Build Status](https://github.com/danibranas/stream-pipeline-php/workflows/Build/badge.svg)

# Stream Pipeline

A Stream based pipeline pattern implementation.

The Pipeline pattern uses ordered stages to process a sequence of input values. Each implemented task is represented by
a stage of the pipeline. You can think of pipelines as similar to assembly lines in a factory, where each item in the
assembly line is constructed in stages.

## How it works

Stream pipeline allows you to go from writing expressions like:

```php
$input = [' B1 ', ' B2', 'a1 ', ' a2 ', 'a3', ' b1', ' b2', 'b3'];
$elements = [];

foreach ($input as $e) {
    $elem = strtoupper(trim($e));

    if (substr($e, 0, 1) === 'B' && !in_array($elem, $elements)) {
        $elements[] = $elem;
    }
}

return implode(',', $elements);
```

to writing functional expressions like:

```php
return Stream::of(' B1 ', ' B2', 'a1 ', ' a2 ', 'a3', ' b1', ' b2', 'b3')
    ->map(Strings::trim())
    ->map(Strings::toUpper())
    ->filter(Strings::startsWith('B'))
    ->distinct()
    ->collect(Collectors::join(','));
```

## Getting started

The library is available as a Composer package on Packagist.org.

To install in your project, just run:

```shell
composer require dbrans/stream-pipeline
```

After this runs successfully, you can include the main class in your application logic:

```php
use StreamPipeline\Stream;
```

## Usage

You can initialize an _Stream_ to use it:

```php
use StreamPipeline\Stream;

$arrStream = Stream::fromIterable($myArray);
// or
$arrStream = Stream::of(' B1 ', ' B2', 'a1 ', ' a2 ', 'a3', ' b1', ' b2', 'b3')
```

A _Stream_ object exposes several methods to operate with its elements:

```php
use StreamPipeline\Collectors;
use StreamPipeline\Iterators\NumberGenerator;
use StreamPipeline\Operations\Strings;

// ...

$arrStream
    ->map(Strings::trim())
    ->map(Strings::toUpper())
    ->filter(Strings::startsWith('B'))
    ->distinct()
    ->forEach(function ($e) {
        echo $e;
    });
```

The _Stream_ class is immutable, so each chaining method returns a new _Stream_.

The execution of a _Stream_ is lazy, so the elements are iterated just one time only when a terminal operation
(_forEach_, _reduce_, _toArray_, _collect_...) is called.

### Pipe operations

Each method allows a callable argument:

```php
$arrStream
    ->filter(function ($e) {
        return $e % 2 === 0;
    })
    ->map(function ($e) {
        return $e + 10;
    })
    ->toArray();
```

The library exposes some common operations to better readability:

```php
$arrStream
    ->filter(Numbers::isEven())
    ->map(Numbers::plus(10))
    ->collect(Collectors::sum());
```

### _Stream_ Methods

**Initialization static operations**:

- `of(...$elements): StreamInterface`
- `fromIterable(iterable $collection): StreamInterface`
- `iterate($initialValue, callable $stepOperation): StreamInterface`

**Pipe operations**:

- `map(callable $operation): StreamInterface`
- `filter(callable $operation): StreamInterface`
- `peek(callable $operation): StreamInterface`
- `limit(int $limit): StreamInterface`
- `skip(int $number): StreamInterface`
- `distinct(?callable $distinctBy = null): StreamInterface`
- `flatMap(?callable $operation = null): StreamInterface`
- `concat(iterable $elements): StreamInterface`

**Terminal operations**:

- `findFirst()`
- `count(): int`
- `forEach(callable $callback): void`
- `anyMatch(callable $condition): bool`
- `allMatch(callable $condition): bool`
- `noneMatch(callable $condition): bool`
- `reduce(callable $operation, $initialValue)`
- `toArray(): array`
- `collect(callable $collector)`

### Pre-defined Collectors

There are pre-defined collector functions with some common operations.
You can use them with the terminal operator `collect()`:

- `Collectors::join(string $delimiter = '')`
- `Collectors::sum(?callable $mapper = null)`
- `Collectors::groupBy(?callable $classifier = null)`

For example:

```php
Stream::of('a', 'b', 'c', 'd', 'e', 'f')
    ->limit(5)
    ->collect(Collectors::join(','));
```