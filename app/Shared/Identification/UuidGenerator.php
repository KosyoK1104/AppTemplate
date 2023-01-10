<?php

declare(strict_types=1);

namespace App\Shared\Identification;

use App\Shared\Identification\Exceptions\InvalidUuidArgument;
use Ramsey\Uuid\Codec\TimestampFirstCombCodec;
use Ramsey\Uuid\UuidFactory as RamseyUuidFactory;

final class UuidGenerator implements UuidGeneratorInterface
{
    private RamseyUuidFactory $uuidGenerator;

    public function __construct(
        RamseyUuidFactory $uuidGenerator
    ) {
        $uuidGenerator->setCodec(new TimestampFirstCombCodec($uuidGenerator->getUuidBuilder()));
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param class-string $class
     * @return Uuid
     */
    public function generate(string $class) : Uuid
    {
        $implements = class_implements($class);
        if (!in_array(Uuid::class, $implements, true)) {
            throw InvalidUuidArgument::forClass($class);
        }

        return new $class($this->uuidGenerator->uuid4());
    }
}
