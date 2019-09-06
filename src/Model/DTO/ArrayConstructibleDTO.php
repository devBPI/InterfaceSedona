<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User:  FOULLOUS Anas
 * Date: 29/01/19
 * Time: 16:23
 */

namespace App\Model\DTO;


class ArrayConstructibleDTO
{
    /**
     * @param array $expectedKeys
     * @param array $input
     * @return void
     */
    public static function checkRequiredKeys(array $expectedKeys, array $input)
    {
        $inputKeys = array_keys($input);
        if (count(array_intersect(array_unique($expectedKeys), $inputKeys)) !== count($expectedKeys))
        {
            throw new \InvalidArgumentException(
                sprintf(
                    "Missing some mandatory keys : %s",
                    implode( ',', array_diff($inputKeys, $expectedKeys))
                )
            );

        }
    }
}