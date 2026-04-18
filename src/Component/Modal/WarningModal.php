<?php

namespace Ospnko\Hush\Component\Modal;

use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class WarningModal implements ComponentInterface
{
    public function __construct(
        private string $heading = 'Warning!',
        private string $text = '',
    ) {
    }

    public function render(): string
    {
        $icon = Svg::ModalWarning->render();
        $heading = htmlspecialchars($this->heading);
        $textHtml = $this->text !== ''
            ? '<p class="status-modal-message">' . htmlspecialchars($this->text) . '</p>'
            : '';

        return <<<HTML
        <div class="modal status-modal warning-modal">
            <div class="status-modal-icon">$icon</div>
            <h2 class="status-modal-title">$heading</h2>
            $textHtml
            <div class="status-modal-actions">
                <a class="button button-light modal-close">OK</a>
            </div>
        </div>
        HTML;
    }
}
