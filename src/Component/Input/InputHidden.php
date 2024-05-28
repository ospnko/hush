<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Interface\ComponentInterface;

class InputHidden implements ComponentInterface
{
    public function __construct(
        private string $name,
        private string $value,
        private array $attributes = [],
    ) {
        $this->value = htmlspecialchars($value);
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes))->render();

        return <<<HTML
            <input type="hidden" name="$this->name" value="$this->value" $attributes>
        HTML;
    }
}
