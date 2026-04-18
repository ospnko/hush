<?php

namespace Ospnko\Hush\Component\Modal;

use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class SuccessModal implements ComponentInterface
{
    public function __construct(
        private string $heading = 'Success!',
        private string $text = '',
        private bool $isReloadNeeded = true,
    ) {
    }

    public function render(): string
    {
        $icon = Svg::ModalSuccess->render();
        $okClass = $this->isReloadNeeded ? 'button page-reload' : 'button modal-close';
        $heading = htmlspecialchars($this->heading);
        $textHtml = $this->text !== ''
            ? '<p class="status-modal-message">' . htmlspecialchars($this->text) . '</p>'
            : '';

        return <<<HTML
        <div class="modal status-modal success-modal">
            <div class="status-modal-icon">$icon</div>
            <h2 class="status-modal-title">$heading</h2>
            $textHtml
            <div class="status-modal-actions">
                <a class="$okClass">OK</a>
            </div>
        </div>
        HTML;
    }
}
