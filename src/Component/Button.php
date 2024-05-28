<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Button implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $content,
        private bool $isAsyncModal = false,
        private array $attributes = [],
    ) {
    }

    public function render(): string
    {
        if ($this->isAsyncModal) {
            $this->attributes['class'] = 'async-modal-link' . (
            isset($this->attributes['class'])
                ? ' ' . $this->attributes['class']
                : ''
            );
        }

        $attributes = (new Attribute($this->attributes))->render();

        return <<<HTML
        <button $attributes>$this->content</button>
        HTML;
    }
}
