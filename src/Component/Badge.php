<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Badge implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $text,
        private string $color = 'gray',
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $this->attributes['class'] = 'badge badge-' . $this->color
            . (isset($this->attributes['class']) ? ' ' . $this->attributes['class'] : '');

        $attributes = (new Attribute($this->attributes))->render();
        $text = htmlspecialchars($this->text);

        return "<span $attributes>$text</span>";
    }
}
