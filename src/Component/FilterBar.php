<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class FilterBar implements ComponentInterface
{
    /**
     * @param array<string,string> $options
     * @param array<string,string> $extraParams
     */
    public function __construct(
        private string $label,
        private array $options,
        private string $active,
        private string $baseUrl,
        private string $paramName,
        private array $extraParams = [],
    ) {
    }

    public function render(): string
    {
        $extra = '';
        foreach ($this->extraParams as $k => $v) {
            if ($v !== '') {
                $extra .= '&' . urlencode($k) . '=' . urlencode($v);
            }
        }

        $items = '';
        foreach ($this->options as $key => $text) {
            $activeClass = $this->active === $key ? ' active' : '';
            $href = $this->baseUrl . '?' . urlencode($this->paramName) . '=' . urlencode($key) . $extra;
            $items .= '<a href="' . $href . '" class="admin-filter-link' . $activeClass . '">'
                . htmlspecialchars($text) . '</a>';
        }

        $labelHtml = $this->label !== ''
            ? '<span class="filter-bar-label">' . htmlspecialchars($this->label) . '</span>'
            : '';

        return '<div class="filter-bar-wrap">'
            . $labelHtml
            . '<div class="admin-filter-bar">' . $items . '</div>'
            . '</div>';
    }
}
