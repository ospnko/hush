<?php

namespace Ospnko\Hush\Component\Breadcrumb;

use Ospnko\Hush\Component\Link;
use Ospnko\Hush\Component\Text;
use Ospnko\Hush\Interface\ComponentInterface;

class BreadcrumbItem implements ComponentInterface
{
    public function __construct(
        private string $text,
        private string $link = '',
    ) {
    }

    public function render(): string
    {
        $text = htmlspecialchars($this->text);

        return $this->link === ''
            ? (new Text($text))->render()
            : (new Link($this->link, $text))->render();
    }
}
