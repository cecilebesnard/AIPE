<?php

namespace AppBundle\Services\Utils;


class StringService
{
    public function generateUniqId()
    {
        $result = bin2hex(openssl_random_pseudo_bytes(16));
        return $result;
    }
}