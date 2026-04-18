<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Stat implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $label,
        private string $value,
        private string $icon = '',
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $this->attributes['class'] = 'stat' . (isset($this->attributes['class']) ? ' ' . $this->attributes['class'] : '');
        $attributes = (new Attribute($this->attributes))->render();

        $label = htmlspecialchars($this->label);
        $value = htmlspecialchars($this->value);
        $iconHtml = $this->icon !== '' ? '<div class="icon">' . $this->icon . '</div>' : '';

        return <<<HTML
        <div $attributes>
            $iconHtml
            <div class="content">
                <small>$label</small>
                <p>$value</p>
            </div>
        </div>
        HTML;
    }
}
