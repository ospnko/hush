<?php

namespace Ospnko\Hush\Enum;

enum Svg: string
{
    case Arrow = '/svg/arrow.svg';
    case BreadcrumbDelimiter = '/svg/breadcrumb-delimiter.svg';
    case Checkmark = '/svg/checkmark.svg';
    case Clear = '/svg/clear.svg';
    case Close = '/svg/close.svg';
    case Coupon = '/svg/coupon.svg';
    case Delete = '/svg/delete.svg';
    case Edit = '/svg/edit.svg';
    case Error = '/svg/error.svg';
    case Eye = '/svg/eye.svg';
    case Logout = '/svg/logout.svg';
    case ModalError = '/svg/modal-error.svg';
    case ModalInfo = '/svg/modal-info.svg';
    case ModalSuccess = '/svg/modal-success.svg';
    case ModalWarning = '/svg/modal-warning.svg';
    case Orders = '/svg/orders.svg';
    case Search = '/svg/search.svg';
    case Success = '/svg/success.svg';
    case Trash = '/svg/trash.svg';
    case Upload = '/svg/upload.svg';
    case Users = '/svg/users.svg';
    case View = '/svg/view.svg';
    case Warning = '/svg/warning.svg';

    public function render(): string
    {
        $path = __DIR__ . '/../../asset/' . ltrim($this->value, '/');
        return file_get_contents($path);
    }
}
