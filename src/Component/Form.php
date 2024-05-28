<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Form implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $action,
        private string $method,
        private string $content,
        private array $attributes = [],
        private string $csrfField = '',
    ) {
        $this->action = htmlspecialchars($this->action);
        $this->method = htmlspecialchars($this->method);
    }

    public function render(): string
    {
        $attributes = (new Attribute($this->attributes, "\n"))->render();

        $formMethod = !in_array($this->method, ['GET', 'POST'])
            ? 'POST'
            : $this->method;

        return <<<HTML
        <form
            action="$this->action"
            method="$formMethod"
            $attributes>
            <input type="hidden" name="_method" value="$this->method" />
            $this->csrfField
            $this->content
        </form>
        HTML;
    }
}
