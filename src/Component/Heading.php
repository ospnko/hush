<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Heading implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $text,
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes))->render();

        return <<<HTML
        <h1 $attributes>$this->text</h1>
        HTML;
    }
}
