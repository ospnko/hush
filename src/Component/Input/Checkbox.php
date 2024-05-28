<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class Checkbox implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $name,
        private bool $isChecked = false,
        private string $label = '',
        private int|string $value = 1,
        private array $attributes = [],
    ) {
        $this->label = htmlspecialchars($this->label);
    }

    public function render(): string
    {
        $this->attributes['class'] = 'checkbox ' . ($this->attributes['class'] ?? '');

        $attributes = (new Attribute($this->attributes))->render();
        $isCheckedString = $this->isChecked ? 'checked' : '';

        $checkmarkSvg = Svg::Checkmark->render();

        return <<<HTML
        <label $attributes>
            <input type="checkbox" name="$this->name" value="$this->value" $isCheckedString>
            <div class="checked">
                $checkmarkSvg
            </div>
            <div class="unchecked"></div>
            <p>$this->label</p>
        </label>
        HTML;
    }
}
