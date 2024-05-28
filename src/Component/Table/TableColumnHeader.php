<?php

namespace Ospnko\Hush\Component\Table;

use Ospnko\Hush\Interface\ComponentInterface;

class TableColumnHeader implements ComponentInterface
{
    public function __construct(
        private string $content,
    ) {
    }

    public function render(): string
    {
        return <<<HTML
        <th>$this->content</th>
        HTML;
    }
}
