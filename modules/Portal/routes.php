<?php
// Routes

/*$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/
class NameSpaceFinder {

    private $namespaceMap = [];
    private $defaultNamespace = 'global';

    public function __construct()
    {
        $this->traverseClasses();
    }

    private function getNameSpaceFromClass($class)
    {
        // Get the namespace of the given class via reflection.
        // The global namespace (for example PHP's predefined ones)
        // will be returned as a string defined as a property ($defaultNamespace)
        // own namespaces will be returned as the namespace itself

        $reflection = new \ReflectionClass($class);
        return $reflection->getNameSpaceName() === ''
                ? $this->defaultNamespace
                : $reflection->getNameSpaceName();
    }

    public function traverseClasses()
    {
        // Get all declared classes
        $classes = get_declared_classes();

        foreach($classes AS $class)
        {
            // Store the namespace of each class in the namespace map
            $namespace = $this->getNameSpaceFromClass($class);
            $this->namespaceMap[$namespace][] = $class;
        }
    }

    public function getNameSpaces()
    {
        return array_keys($this->namespaceMap);
    }

    public function getClassesOfNameSpace($namespace)
    {
        if(!isset($this->namespaceMap[$namespace]))
            throw new \InvalidArgumentException('The Namespace '. $namespace . ' does not exist');

        return $this->namespaceMap[$namespace];
    }

}

$app->get('[/[index[/index[/]]]]', 'IndexController:index');

$app->get('/menu[/index[/]]', 'MenuController:index');

//$app->get('/cocina[/index[/]]', 'CocinaController:index');

$app->get('/test', function ($request, $response, $args) {

    d($this->session); exit;

});
