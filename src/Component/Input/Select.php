<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Component\Error;
use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class Select implements ComponentInterface
{
    /**
     * @param array<string|int,string> $options
     * @param string[] $errors
     */
    public function __construct(
        private string $name,
        private array $options = [],
        private string|int $value = '',
        private array $errors = [],
        private string $searchPlaceholder = '',
        private string $placeholder = '',
        private bool $isRequired = false,
        private bool $isSearchable = false,
        private string $asyncSearchUrl = '',
    ) {
    }

    public function render(): string
    {
        $optionsString = $this->renderOptions();
        $vanillaOptionsString = $this->renderVanillaOptions();
        $errorsString = (new Error($this->errors))->render();

        $currentValue = $this->options[$this->value] ?? $this->placeholder;
        $arrowSvg = Svg::Arrow->render();

        $searchField = '';

        if ($this->isSearchable) {
            $searchField = (new InputSearch(
                type: 'text',
                name: '',
                placeholder: $this->searchPlaceholder,
                value: '',
                attributes: $this->asyncSearchUrl !== ''
                    ? ['data-async_url' => $this->asyncSearchUrl]
                    : [],
            ))->render();
        }

        $optionTemplate = htmlspecialchars($this->getOptionTemplate($this->getCheckmarkSvg()));
        $vanillaOptionTemplate = htmlspecialchars($this->getVanillaOptionTemplate());

        return <<<HTML
        <div class="select"
            data-vanilla_template="$vanillaOptionTemplate"
            data-template="$optionTemplate">

            <div class="vanilla" style="display: none">
                <select name="$this->name">
                    $vanillaOptionsString
                </select>
            </div>
            <div class="input" data-placeholder="$this->placeholder">
                <p>$currentValue</p>
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

    public function renderOptions(): string
    {
        $checkmarkSvg = $this->getCheckmarkSvg();

        $result = [];

        foreach ($this->options as $value => $text) {
            $attributesArray = [];

            if ($value == $this->value) {
                $attributesArray['class'] = 'selected';
            }

            $attributes = (new Attribute($attributesArray))->render();

            $result[] =
            str_replace(
                ['{value}', '{text}'],
                [$value, $text],
                $this->getOptionTemplate($checkmarkSvg, $attributes),
            );
        }

        return implode("\n", $result);
    }

    public function renderVanillaOptions(): string
    {
        $result = [];

        foreach ($this->options as $value => $text) {
            $attributesArray = [];

            if ($value == $this->value) {
                $attributesArray['selected'] = '';
            }

            $attributes = (new Attribute($attributesArray))->render();

            $result[] = str_replace(
                ['{value}', '{text}'],
                [$value, $text],
                $this->getVanillaOptionTemplate($attributes),
            );
        }

        return implode("\n", $result);
    }

    private function getCheckmarkSvg(): string
    {
        return str_replace(
            ['width="20"', 'height="20"'],
            ['width="16"', 'height="16"'],
            Svg::Checkmark->render(),
        );
    }

    private function getOptionTemplate(string $checkmarkSvg, string $attributes = ''): string
    {
        return <<<HTML
            <li data-value="{value}" $attributes>
                <div class="marked">
                    $checkmarkSvg
                </div>
                {text}
            </li>
        HTML;
    }

    private function getVanillaOptionTemplate(string $attributes = ''): string
    {
        return <<<HTML
            <option value="{value}" $attributes>{text}</option>
        HTML;
    }
}
