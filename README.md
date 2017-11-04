# GraphiQL PSR-7 Middleware

You can add a [GraphiQL](https://github.com/graphql/graphiql) interface to your project with this PSR-7 Middleware

[![](src/graphiql/interface.png)](http://graphql.org/swapi-graphql)

## Install
```
composer require rromanovsky/graphiql-middleware
```

## Usage
- Slim
    ```php
    use GraphiQLMiddleware\GraphiQLMiddleware;
    // 1) Allow middleware to be executed on '/graphiql' route
    $app->add(new GraphiQLMiddleware(['route' => '/graphiql']));
    // 2) Allow middleware to be executed on '/graphiql' route
    $app->get("/graphiql", function($request, $response, $args) {
        return $response;
    })->add(new GraphiQLMiddleware(['ingoreRoute' => true]));
    // 3) Allow middleware to be executed on any route
    $app->add(new GraphiQLMiddleware(['ingoreRoute' => true]));
    ```