<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Label implements ComponentInterface
{
    public function __construct(
        private string $content,
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes))->render();

        return <<<HTML
        <label $attributes>$this->content</label>
        HTML;
    }
}
