<?php

declare(strict_types=1);

namespace App\Shared\Security;

final class OneTimeReadSecuredString extends SecuredString
{
    public function value() : string
    {
        $value = $this->value;
        $this->wipe();
        return $value;
    }
}
