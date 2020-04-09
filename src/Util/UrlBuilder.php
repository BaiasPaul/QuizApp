<?php

namespace QuizApp\Util;

/**
 * Class UrlBuilder
 * @package QuizApp\Util
 */
class UrlBuilder
{

    /**
     * Build a query parameter string
     *
     * @param array $queryParameters
     * @return string
     */
    public function getUrl(array $queryParameters): string
    {
        $resultString = '?';
        foreach ($queryParameters as $name => $value) {
            if ($value !== '') {
                $resultString .= "$name=$value&";
            }
        }

        return substr($resultString, 0, -1);
    }

    /**
     * The search bar uses hidden inputs
     *
     * @param array $queryParameters
     * @param $skipParameter
     * @return string
     */
    public function getSearchUrl(array $queryParameters, $skipParameter): string
    {
        $resultString = '';
        foreach ($queryParameters as $name => $value) {
            if ($name !== $skipParameter && $value !== '') {
                $resultString .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
            }
        }

        return $resultString;
    }
}
