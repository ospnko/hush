<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Style implements ComponentInterface
{
    public function __construct(
        private string $content,
    ) {
    }

    public function render(): string
    {
        return <<<HTML
        <style>$this->content</style>
        HTML;
    }
}
