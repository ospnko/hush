<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class BlockInputSearch implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     * @param array<string,string> $hiddenFields
     */
    public function __construct(
        private string $name,
        private string $placeholder = '',
        private string $value = '',
        private string $action = '',
        private string $method = 'GET',
        private array $attributes = [],
        private array $hiddenFields = [],
    ) {
    }

    public function render(): string
    {
        $hiddenHtml = '';
        foreach ($this->hiddenFields as $fieldName => $fieldValue) {
            $hiddenHtml .= '<input type="hidden" name="' . htmlspecialchars($fieldName) . '" value="' . htmlspecialchars($fieldValue) . '">';
        }

        $val = htmlspecialchars($this->value);
        $hasContentClass = $val !== '' ? ' has-content' : '';

        $this->attributes['class'] = 'search-input' . (isset($this->attributes['class']) ? ' ' . $this->attributes['class'] : '');
        $this->attributes['oninput'] = 'this.parentElement.classList.toggle(\'has-content\', this.value.length > 0)';
        $this->attributes['placeholder'] = ' ';

        $attributesHtml = '';
        foreach ($this->attributes as $key => $attrValue) {
            $attributesHtml .= ' ' . $key . '="' . htmlspecialchars($attrValue) . '"';
        }

        $realPlaceholder = htmlspecialchars($this->placeholder);
        $searchIcon = Svg::Search->render();
        $clearIcon = Svg::Clear->render();
        $arrowIcon = Svg::Arrow->render();

        $clearClick = "let inp = this.parentElement.querySelector('[data-hush-search-input]'); inp.value = ''; inp.dispatchEvent(new Event('input')); setTimeout(() => this.parentElement.submit(), 1);";

        return '<div class="block-search">'
            . '<form method="' . $this->method . '" action="' . $this->action . '" class="' . $hasContentClass . '">'
            . $hiddenHtml
            . '<input type="text" name="' . $this->name . '" value="' . $val . '" data-hush-search-input="true"' . $attributesHtml . '>'
            . '<div class="placeholder-overlay">' . $realPlaceholder . '</div>'
            . '<div class="search-icon">' . $searchIcon . '</div>'
            . '<div class="clear-btn" onclick="' . htmlspecialchars($clearClick) . '">' . $clearIcon . '</div>'
            . '<div class="submit-btn" onclick="this.parentElement.submit();">' . $arrowIcon . '</div>'
            . '</form>'
            . '</div>';
    }
}
