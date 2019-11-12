<?php

namespace App\Model\Exception;


/**
 * Class ErrorAccessApiException
 * @package App\Model\Exception
 */
class ErrorAccessApiException extends \DomainException
{
    public const MESSAGE = 'Le service est inaccessible pour l\'instant. Veuillez réessayer ultérieurement.';
}
