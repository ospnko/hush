<?php

namespace Ospnko\Hush\Enum;

enum Svg: string
{
    case Arrow = '/svg/arrow.svg';
    case BreadcrumbDelimiter = '/svg/breadcrumb-delimiter.svg';
    case Checkmark = '/svg/checkmark.svg';
    case Clear = '/svg/clear.svg';
    case Close = '/svg/close.svg';
    case Delete = '/svg/delete.svg';
    case Edit = '/svg/edit.svg';
    case Error = '/svg/error.svg';
    case Eye = '/svg/eye.svg';
    case Search = '/svg/search.svg';
    case Success = '/svg/success.svg';
    case Upload = '/svg/upload.svg';
    case Warning = '/svg/warning.svg';

    public function render(): string
    {
        return file_get_contents(__DIR__ . '/../../asset/' . $this->value);
    }
}
