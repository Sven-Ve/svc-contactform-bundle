<?php

namespace Svc\ContactformBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SvcContactformBundle extends Bundle
{
  public function getPath(): string
  {
    return \dirname(__DIR__);
  }
}
