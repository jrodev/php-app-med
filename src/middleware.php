<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
$app->add(new \Slim\Middleware\Session([
  'name' => 'dummy_session',
  //'autorefresh' => true,
  'lifetime' => '5 min'
]));

// Register globally to app
$container['session'] = function ($c) {
  return new \SlimSession\Helper;
};


// IMPLEMENTAR
// https://blog.programster.org/slim3-use-middleware-to-check-user-is-logged-in
// https://www.slimframework.com/docs/v3/concepts/middleware.html
