<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Block implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $content,
        private string $headline = '',
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes))->render();

        $heading = $this->headline !== ''
            ? (new Heading($this->headline))->render()
            : '';

        return <<<HTML
        <section $attributes>
            $heading
            $this->content
        </section>
        HTML;
    }
}
