<?php

namespace Ospnko\Hush\Component\Breadcrumb;

use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class Breadcrumb implements ComponentInterface
{
    /**
     * @param array<string,string> $crumbs
     */
    public function __construct(
        private array $crumbs,
    ) {
    }

    public function addItem(
        string $text,
        string $link = '',
    ): self {
        $this->crumbs[$link] = $text;

        return $this;
    }

    public function render(): string
    {
        $result = [];

        foreach ($this->crumbs as $link => $text) {
            $result[] = (new BreadcrumbItem(text: $text, link: $link))->render();
        }

        return '<div class="breadcrumbs">'
            . implode(
                Svg::BreadcrumbDelimiter->render(),
                $result,
            )
            . '</div>';
    }
}
