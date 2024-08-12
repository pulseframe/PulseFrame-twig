<?php

namespace PulseFrame\Support\Template;

use PulseFrame\Facades\Config;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\TwigFunction;

class Engine
{
  private $twig;

  public function __construct($debug = false)
  {
    $path = array_merge([__DIR__ . '/../../base/src/views/'], (array) Config::get('twig', 'path'));
    $loader = new FilesystemLoader($path);

    $this->twig = new Environment($loader);
    $debug === true ?? $this->twig->addExtension(new DebugExtension());
  }

  /**
   * Render a template with the given data.
   *
   * @param string $template The name of the template.
   * @param array $data The data to pass to the template.
   * @return string The rendered HTML content.
   */
  public function render(string $template, array $data = []): string
  {
    return $this->twig->render($template, $data);
  }

  /**
   * Add a custom function to the Twig environment.
   *
   * @param string $name The name of the function.
   * @param callable $callback The function callback.
   */
  public function addFunction(string $name, callable $callback)
  {
    $function = new TwigFunction($name, $callback);
    $this->twig->addFunction($function);
  }
}
