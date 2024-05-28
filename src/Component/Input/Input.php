<?php

namespace Ospnko\Hush\Component\Input;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Component\Error;
use Ospnko\Hush\Interface\ComponentInterface;

class Input implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     * @param string[] $errors
     */
    public function __construct(
        private string $type,
        private string $name,
        private string $placeholder,
        private string $value,
        private bool $isRequired = false,
        private array $attributes = [],
        private array $errors = [],
    ) {
        $this->value = htmlspecialchars($value);
    }

    public function render(): string
    {
        if ($this->errors !== []) {
            $this->attributes['class'] = 'has-error' . (
                isset($this->attributes['class'])
                    ? ' ' . $this->attributes['class']
                    : ''
            );
        }

        if ($this->isRequired) {
            $this->attributes['required'] = '';
        }

        $attributes = (new Attribute($this->attributes))->render();
        $errorsString = (new Error($this->errors))->render();

        return <<<HTML
        <input type="$this->type" name="$this->name" value="$this->value" placeholder="$this->placeholder" $attributes>
        $errorsString
        HTML;
    }
}
