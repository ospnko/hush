<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Video implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $source,
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes))->render();

        return <<<HTML
        <video src="$this->source" $attributes></video>
        HTML;
    }
}
