<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class StatCard implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $label,
        private string $value,
        private string $subtext = '',
        private string $icon = '',
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $this->attributes['class'] = 'stat-card' . (isset($this->attributes['class']) ? ' ' . $this->attributes['class'] : '');
        $attributes = (new Attribute($this->attributes))->render();

        $label = htmlspecialchars($this->label);
        $value = htmlspecialchars($this->value);
        $subtext = htmlspecialchars($this->subtext);

        $iconHtml = $this->icon !== ''
            ? '<div class="stat-card-icon">' . $this->icon . '</div>'
            : '';

        $subtextHtml = $this->subtext !== ''
            ? '<div class="stat-card-subtext">' . $subtext . '</div>'
            : '';

        return <<<HTML
        <div $attributes>
            <div class="stat-card-header">
                <div class="stat-card-label">$label</div>
                $iconHtml
            </div>
            <div class="stat-card-value">$value</div>
            $subtextHtml
        </div>
        HTML;
    }
}
