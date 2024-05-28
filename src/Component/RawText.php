<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Interface\ComponentInterface;

class RawText implements ComponentInterface
{
    public function __construct(
        private string $text,
    ) {
    }

    public function render(): string
    {
        return htmlspecialchars($this->text);
    }
}
