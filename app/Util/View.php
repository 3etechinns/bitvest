<?php
namespace App\Util;

class View
{

  private $vars = [];

  public function addVars(array $vars)
  {
    $this->vars += $vars;
  }

  public function render($view, $vars = [])
  {
    $vars = $this->vars + $vars;
    include '../app/View/' . $view . '.phtml';
  }
}