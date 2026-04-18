<?php

namespace Ospnko\Hush\Component\Modal;

use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class ErrorModal implements ComponentInterface
{
    public function __construct(
        private string $heading = 'Error!',
        private string $text = '',
    ) {
    }

    public function render(): string
    {
        $icon = Svg::ModalError->render();
        $heading = htmlspecialchars($this->heading);
        $textHtml = $this->text !== ''
            ? '<p class="status-modal-message">' . htmlspecialchars($this->text) . '</p>'
            : '';

        return <<<HTML
        <div class="modal status-modal error-modal">
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
