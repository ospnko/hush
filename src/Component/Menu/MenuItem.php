<?php

namespace Ospnko\Hush\Component\Menu;

use Ospnko\Hush\Component\Attribute;
use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class MenuItem implements ComponentInterface
{
    /**
     * @param array<string,string> $attributes
     * @param null|callable(menu:Menu):Menu $submenu
     */
    public function __construct(
        private string $link,
        private string $text,
        private bool $isActive = false,
        private array $attributes = [],
        private ?Menu $submenu = null,
    ) {
        $this->link = htmlspecialchars($link);
        $this->text = htmlspecialchars($text);
    }

    public function render(): string
    {
        if ($this->submenu !== null) {
            $this->attributes['class'] = 'has-submenu ' . ($this->attributes['class'] ?? '');
        }

        $attributes = (new Attribute($this->attributes))->render();

        $linkAttributes = (new Attribute(
            $this->isActive
                ? ['class' => 'active']
                : ['href' => $this->link]
        ))->render();

        $submenuString = $this->submenu?->render();

        $arrowString = $this->submenu !== null
            ? Svg::Arrow->render()
            : '';

        return <<<HTML
        <li $attributes>
            <a $linkAttributes>
                $this->text
                $arrowString
            </a>
            $submenuString
        </li>
        HTML;
    }
}
