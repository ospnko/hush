<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class RawHtml implements ComponentInterface
{
    public function __construct(
        private string $content,
    ) {
    }

    public function render(): string
    {
        return $this->content;
    }
}
