<?php

namespace App\Contracts;

use App\Services\Domain\DomainCheckResult;

/**
 * Domen provayderi üçün interfeys.
 * Yeni provayder qoşmaq üçün bu interfeysi tətbiq edən adapter yazın
 * və config/domain.php + DomainServiceProvider-də qeyd edin.
 */
interface DomainRegistrar
{
    /** Provayderin qısa adı (log və debug üçün). */
    public function name(): string;

    /**
     * Bir neçə domeni bir dəfəyə yoxlayır.
     *
     * @param  array<int, string>  $domains  tam domen adları, məs. ["hamid.az", "hamid.com"]
     * @return array<string, DomainCheckResult> domen => nəticə
     */
    public function check(array $domains): array;
}
