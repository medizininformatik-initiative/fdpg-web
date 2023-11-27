<?php

namespace ACPT\Utils\PHP;

class Arrays
{
    /**
     * Reindex an indexed array
     *
     * @param $array
     * @return array
     */
    public static function reindex($array)
    {
        $index = 0;
        $return = [];

        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $newKey = $key;
            } else {
                $newKey = $index;
                ++$index;
            }

            $return[$newKey] = is_array($value) ? self::reindex($value) : $value;
        }

        // Sort alphabetically, numeric first then alpha
        ksort($return, SORT_NATURAL);

        return $return;
    }
}