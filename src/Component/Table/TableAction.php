<?php

namespace Ospnko\Hush\Component\Table;

use Ospnko\Hush\Component\Link;
use Ospnko\Hush\Interface\ComponentInterface;

class TableAction implements ComponentInterface
{
    public function __construct(
        private string $note,
        private string $link,
        private string $icon = '',
        private bool $isAsyncModal = false,
    ) {
    }

    public function render(): string
    {
        return (new Link(
            link: $this->link,
            content: $this->icon !== ''
                ? $this->icon
                : $this->note,
            isAsyncModal: $this->isAsyncModal,
            attributes: ['title' => $this->note],
        ))->render();
    }
}
