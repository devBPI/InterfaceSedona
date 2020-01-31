<?php
declare(strict_types=1);

namespace App\Model\Traits;

/**
 * Trait PrintTrait
 * @package App\Model\Traits
 */
trait PrintTrait
{
    static $separator = ' - ';

    /**
     * @param string $firstText
     * @param string|null $secondText
     * @return string
     */
    private function concatenateData(?string $firstText, ?string $secondText): string
    {
        $finalText = $firstText ?? '';

        if (!empty($secondText)) {
            $finalText .= self::$separator.$secondText;
        }

        return $finalText;
    }
}
