<?php

namespace Ospnko\Hush\Component\Modal;

use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class InfoModal implements ComponentInterface
{
    public function __construct(
        private string $title,
        private string $message,
    ) {
    }

    public function render(): string
    {
        $icon = Svg::ModalInfo->render();
        $title = htmlspecialchars($this->title);
        $message = htmlspecialchars($this->message);

        return <<<HTML
        <div class="modal status-modal info-modal">
            <div class="status-modal-icon">$icon</div>
            <h2 class="status-modal-title">$title</h2>
            <p class="status-modal-message">$message</p>
            <div class="status-modal-actions">
                <a class="button button-light modal-close">OK</a>
            </div>
        </div>
        HTML;
    }
}
