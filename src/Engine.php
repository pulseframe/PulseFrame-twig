<?php

namespace PulseFrame\Support\Template;

use PulseFrame\Facades\Config;
use PulseFrame\Facades\Storage;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\TwigFunction;

class Engine
{
  private $twig;

  public function __construct()
  {
    try {
      $debug = Config::get('twig', 'debug');
    } catch (\Exception) {
      $debug = false;
    };

    $viewPaths = array_merge([__DIR__ . '/../views/'], (array) Config::get('twig', 'path'));

    $cacheDir = Storage::path('/framework/template/cache');
    if (!is_dir($cacheDir)) {
      mkdir($cacheDir, 0777, true);
    }

    $loader = new FilesystemLoader($viewPaths);

    $this->twig = new Environment($loader, [
      'cache' => $cacheDir,
      'debug' => $debug,
    ]);

    if ($debug) {
      $this->twig->addExtension(new DebugExtension());
    }
  }


  /**
   * Render a template with the given data.
   *
   * @param string $template The name of the template.
   * @param array $data The data to pass to the template.
   * @return string The rendered HTML content.
   */
  public function render(string $template, array $data = [], $includeExtension = true): string
  {
    $extension = $includeExtension ? '.twig' : '';

    return $this->twig->render($template . $extension, $data);
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
