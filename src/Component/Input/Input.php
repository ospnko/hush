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
        protected string $type,
        protected string $name,
        protected string $placeholder,
        protected string $value,
        protected bool $isRequired = false,
        protected array $attributes = [],
        protected array $errors = [],
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
        $escapedPlaceholder = htmlspecialchars($this->placeholder);

        return <<<HTML
        <input type="$this->type" name="$this->name" value="$this->value" placeholder="$escapedPlaceholder" $attributes>
        $errorsString
        HTML;
    }
}
