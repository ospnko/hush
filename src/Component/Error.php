<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Error implements ComponentInterface
{
    /**
     * @param string[] $errors
     */
    public function __construct(
        private array $errors = [],
    ) {
    }

    public function render(): string
    {
        $errorsString = implode(', ', $this->errors);

        return <<<HTML
        <small class="error">$errorsString</small>
        HTML;
    }
}
