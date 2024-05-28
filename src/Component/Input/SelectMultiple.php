<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Component\Error;
use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class SelectMultiple implements ComponentInterface
{
    /**
     * @param array<string|int,string> $options
     * @param int[]|string[] $values
     * @param string[] $errors
     */
    public function __construct(
        private string $name,
        private array $options = [],
        private array $values = [],
        private array $errors = [],
        private string $placeholder = '',
        private string $searchPlaceholder = '',
        private bool $isRequired = false,
        private bool $isSearchable = false,
    ) {
    }

    public function render(): string
    {
        $optionsString = $this->renderOptions();
        $vanillaOptionsString = $this->renderVanillaOptions();

        $valueDesign = $this->renderValueDesign();
        $valuesString = $this->renderValues($valueDesign);
        $errorsString = (new Error($this->errors))->render();

        $arrowSvg = Svg::Arrow->render();

        $searchField = '';

        if ($this->isSearchable) {
            $searchField = (new InputSearch(
                type: 'text',
                name: '',
                placeholder: $this->searchPlaceholder,
                value: '',
            ))->render();
        }

        $isValuesEmptyClass = $this->values === [] ? 'empty' : '';

        return <<<HTML
        <div class="select multiple">
            <div class="design" style="display: none">
                $valueDesign
            </div>
            <div class="vanilla" style="display: none">
                <select name="$this->name[]" multiple>
                    $vanillaOptionsString
                </select>
            </div>
            <div class="input">
                <ul class="values $isValuesEmptyClass">
                    $valuesString
                </ul>
                <p>$this->placeholder</p>
                $arrowSvg
            </div>
            <ul class="options">
                $searchField
                $optionsString
            </ul>
        </div>
        $errorsString
        HTML;
    }

    private function renderOptions(): string
    {
        $checkmarkSvg = str_replace(
            ['width="20"', 'height="20"'],
            ['width="16"', 'height="16"'],
            Svg::Checkmark->render(),
        );

        $result = [];

        foreach ($this->options as $value => $text) {
            $attributesArray = [];

            if (in_array($value, $this->values)) {
                $attributesArray['class'] = 'selected';
            }

            $attributes = (new Attribute($attributesArray))->render();

            $result[] = <<<HTML
            <li data-value="$value" $attributes>
                <div class="marked">
                    $checkmarkSvg
                </div>
                $text
            </li>
            HTML;
        }

        return implode("\n", $result);
    }

    private function renderVanillaOptions(): string
    {
        $result = [];

        foreach ($this->options as $value => $text) {
            $attributesArray = [];

            if (in_array($value, $this->values)) {
                $attributesArray['selected'] = '';
            }

            $attributes = (new Attribute($attributesArray))->render();

            $result[] = <<<HTML
            <option value="$value" $attributes>$text</option>
            HTML;
        }

        return implode("\n", $result);
    }

    private function renderValueDesign(): string
    {
        $clearSvg = Svg::Clear->render();

        return <<<HTML
        <li data-value="{value}">
            {text}
            $clearSvg
        </li>
        HTML;
    }

    private function renderValues(string $design): string
    {
        $result = [];

        foreach ($this->values as $value) {
            $text = $this->options[$value];

            $result[] = \str_replace(
                ['{value}', '{text}'],
                [$value, $text],
                $design,
            );
        }

        return implode("\n", $result);
    }
}
