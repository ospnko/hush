<?php

namespace Ospnko\Hush\Component\Modal;

use Ospnko\Hush\Enum\Svg;
use Ospnko\Hush\Interface\ComponentInterface;

class ConfirmModal implements ComponentInterface
{
    public function __construct(
        private string $title,
        private string $message,
        private string $actionUrl,
        private string $method = 'POST',
        private string $confirmLabel = 'Confirm',
        private string $csrfToken = '',
    ) {
    }

    public function render(): string
    {
        $icon = Svg::Trash->render();
        $csrf = $this->csrfToken !== ''
            ? '<input type="hidden" name="_token" value="' . htmlspecialchars($this->csrfToken) . '">'
            : '';
        $methodField = !in_array(strtoupper($this->method), ['GET', 'POST'])
            ? '<input type="hidden" name="_method" value="' . strtoupper($this->method) . '">'
            : '';
        $title = $this->title !== '' ? htmlspecialchars($this->title) : 'Confirm';
        $actionUrl = htmlspecialchars($this->actionUrl);
        $confirmLabel = htmlspecialchars($this->confirmLabel);

        return <<<HTML
        <div class="modal confirm-modal">
            <form method="POST" action="$actionUrl" id="confirm-form">
                $csrf$methodField
                <div class="confirm-modal-icon">$icon</div>
                <h2 class="confirm-modal-title">$title</h2>
                <p class="confirm-modal-message">{$this->message}</p>
                <div class="confirm-modal-actions">
                    <a class="button button-light modal-close">Cancel</a>
                    <button type="button" class="button confirm-modal-danger-btn async-modal-link" data-is_form="1">$confirmLabel</button>
                </div>
            </form>
        </div>
        HTML;
    }
}
