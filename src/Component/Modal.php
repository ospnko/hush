<?php

namespace Ospnko\Hush\Component;

use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class Modal implements ComponentInterface
{
    public function __construct(
        private string $content,
        private string $title = '',
        private string $footer = '',
        private bool $isHeadingShown = true,
    ) {
    }

    public function render(): string
    {
        $heading = '';
        $svg = Svg::Close->render();

        if ($this->isHeadingShown) {
            $heading = <<<HTML
            <div class="heading">
                <h1>$this->title</h1>
                <a class="modal-close">
                    $svg
                </a>
            </div>
            HTML;
        }

        $footer = '';

        if ($this->footer !== '') {
            $footer = <<<HTML
            <div class="footer">$this->footer</div>
            HTML;
        }

        return <<<HTML
        <div class="modal">
            $heading
            <div>
                $this->content
            </div>
            $footer
        </div>
        HTML;
    }
}
