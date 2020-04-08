<?php

namespace QuizApp\Util;

/**
 * Class UrlBuilder
 * @package QuizApp\Util
 */
class UrlBuilder
{

    /**
     * UrlBuilder constructor.
     */
    public function __construct()
    {
    }

    public function getUrl(array $queryParameters, int $page = 1): string
    {
        $resultString = '?';
        if ($page !== 1) {
            $resultString .= "page=$page&";
        }
        foreach ($queryParameters as $name => $value) {
            if ($value !== '') {
                $resultString .= "$name=$value&";
            }
        }

        return substr($resultString, -1);
    }
}
