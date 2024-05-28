<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Component\Error;
use Ospnko\Hush\Interface\ComponentInterface;

class Textarea implements ComponentInterface
{
    /**
     * @param string[] $errors
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $name,
        private string $value = '',
        private string $placeholder = '',
        private array $errors = [],
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes))->render();
        $errorsString = (new Error($this->errors))->render();

        return <<<HTML
        <textarea name="$this->name" placeholder="$this->placeholder" $attributes>$this->value</textarea>
        $errorsString
        HTML;
    }
}
