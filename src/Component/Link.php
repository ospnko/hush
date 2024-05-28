<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class Link implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     */
    public function __construct(
        private string $link,
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
        <a href="$this->link" $attributes>$this->content</a>
        HTML;
    }
}
