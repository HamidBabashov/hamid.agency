<?php

namespace App\Services\Domain;

final class DomainCheckResult
{
    public const AVAILABLE = 'available';
    public const TAKEN = 'taken';
    public const UNKNOWN = 'unknown';

    public function __construct(
        public readonly string $domain,
        public readonly string $status,
        public readonly ?string $provider = null,
    ) {
    }

    public static function available(string $domain, ?string $provider = null): self
    {
        return new self($domain, self::AVAILABLE, $provider);
    }

    public static function taken(string $domain, ?string $provider = null): self
    {
        return new self($domain, self::TAKEN, $provider);
    }

    public static function unknown(string $domain, ?string $provider = null): self
    {
        return new self($domain, self::UNKNOWN, $provider);
    }

    public function isAvailable(): bool
    {
        return $this->status === self::AVAILABLE;
    }
}
