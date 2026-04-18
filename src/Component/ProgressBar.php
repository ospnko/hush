<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class ProgressBar implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $label,
        private int $value,
        private int $total,
        private string $color = '#22c55e',
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $this->attributes['class'] = 'stat-card' . (isset($this->attributes['class']) ? ' ' . $this->attributes['class'] : '');
        $attributes = (new Attribute($this->attributes))->render();

        $label = htmlspecialchars($this->label);
        $pct = $this->total > 0 ? round($this->value / $this->total * 100) : 0;
        $color = htmlspecialchars($this->color);
        $count = htmlspecialchars($this->value . ' / ' . $this->total);

        return <<<HTML
        <div $attributes>
            <div class="stat-card-header">
                <div class="stat-card-label">$label</div>
                <div style="font-size: 13px; font-weight: 600; opacity: 0.7;">$count</div>
            </div>
            <div style="background: rgba(255,255,255,0.07); border-radius: 6px; height: 8px; overflow: hidden;">
                <div style="width: {$pct}%; background: $color; height: 100%; border-radius: 6px; transition: width 0.4s ease;"></div>
            </div>
            <div style="font-size: 24px; font-weight: 700;">{$pct}%</div>
        </div>
        HTML;
    }
}
