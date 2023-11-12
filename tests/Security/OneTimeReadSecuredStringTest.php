<?php

declare(strict_types=1);

namespace Security;

use App\Shared\Security\OneTimeReadSecuredString;
use PHPUnit\Framework\TestCase;

final class OneTimeReadSecuredStringTest extends TestCase
{
    public function test_can_be_read_only_one_time() : void
    {
        $securedString = new OneTimeReadSecuredString('secret');

        $this->assertSame('**********', (string) $securedString);
        $this->assertSame('secret', $securedString->value());
        $this->assertSame('**********', $securedString->value());
    }
}
