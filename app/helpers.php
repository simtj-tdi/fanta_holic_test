<?php

if (!function_exists('elements')) {
    /**
     * Elements
     *
     * Returns only the array items specified. Will return a default value if
     * it is not set.
     *
     * @param array
     * @param array
     * @param mixed
     * @return    mixed    depends on what the array contains
     */
    function elements($items, array $array, $default = null)
    {
        $return = array();
        is_array($items) OR $items = array($items);
        foreach ($items as $item) {
            $return[$item] = array_key_exists($item, $array) ? $array[$item] : $default;
        }
        return $return;
    }
}