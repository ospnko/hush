<?php

namespace Ospnko\Hush\Component\Table;

use Ospnko\Hush\Interface\ComponentInterface;

class TableRow implements ComponentInterface
{
    public function __construct(
        private string $content,
    ) {
    }

    public function render(): string
    {
        return <<<HTML
        <tr>$this->content</tr>
        HTML;
    }
}
