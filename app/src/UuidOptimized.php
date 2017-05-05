<?php

namespace Damiano\Uuid;

use Ramsey\Uuid\UuidInterface;

class UuidOptimized
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * UuidOptimized constructor.
     * @param UuidInterface $uuid
     */
    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Return an optimized uuid string
     *
     * @return string
     */
    public function optimized(): string
    {
        $uuidStringParts = explode('-', (string)$this->uuid);

        return sprintf('%s-%s-%s-%s-%s',
            $uuidStringParts[2],
            $uuidStringParts[1],
            $uuidStringParts[0],
            $uuidStringParts[3],
            $uuidStringParts[4]
        );
    }

    /**
     * Return an optimized uuid in binary string
     *
     * @return string
     */
    public function optimizedForPersistence(): string
    {
        return hex2bin(str_replace('-', '', $this->optimized()));
    }
}