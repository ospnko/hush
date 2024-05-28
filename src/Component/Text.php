<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Text implements ComponentInterface
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
        <p $attributes>$this->text</p>
        HTML;
    }
}
