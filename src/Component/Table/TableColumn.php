<?php

namespace Ospnko\Hush\Component\Table;

use Ospnko\Hush\Interface\ComponentInterface;

class TableColumn implements ComponentInterface
{
    public function __construct(
        private string $content,
    ) {
    }

    public function render(): string
    {
        return <<<HTML
        <td>$this->content</td>
        HTML;
    }
}
