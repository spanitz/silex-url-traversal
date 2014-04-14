Usage
=====

```php
<?php
use spanitz\Silex\Provider\TraversalControllerProvider;

$app = new Silex\Application();

$app->mount('/', new TraversalControllerProvider(
    $myRootFactory,
    $myController
));
```
