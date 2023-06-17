<?php

namespace Sigep\EloquentEnhancements;

/**
 * This function checks if the array is associative or not
 * Note: the function only looks if all keys are numerical. We have better ways to check, but they can be trouble if
 * the array has missing keys
 * @param array $array
 * @return bool
 */
function isAssoc(array $array): bool
{
    if (empty($array)) {
        return false;
    }

    return  ctype_digit(implode('', array_keys($array))) === false;
}

function cleanCollection(array $collection): array {
    if (isAssoc($collection)) {
        throw new \InvalidArgumentException('The collection must be a list of objects');
    }

    $cleanded = array_filter($collection, function ($item) {
        return !empty(array_filter($item));
    });

    return array_values($cleanded);
}
