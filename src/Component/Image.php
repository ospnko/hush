<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Image implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $link,
        private string $alt = "",
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes))->render();

        return <<<HTML
        <img src="$this->link" alt="$this->alt" $attributes>
        HTML;
    }
}
