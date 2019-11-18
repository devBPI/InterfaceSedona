<?php

namespace App\Model;


/**
 * Class ErrorApiResponse
 * @package App\Model
 */
class ErrorApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * ErrorApiResponse constructor.
     * @param $data
     */
    public function __construct($data)
    {
        if (isset($data->message)) {
            $this->message = $data->message;
        }
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}
