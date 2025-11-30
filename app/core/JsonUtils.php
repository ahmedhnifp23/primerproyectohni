<?php

class JsonUtils {
    public static function encodeUnescaped($data): string {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    //Function that serializes an objecto to an associative array.
    public static function serialize(object $obj): array {
        if (method_exists($obj, 'jsonSerialize')) {
            return $obj->jsonSerialize();
        }
        return [];
    }

    //Function that decodes a json string to an associative array
    public static function decodeAssociative(string $json): array {
        return json_decode($json, true);
    }

    //Function that serializes an array of objects to an array of associative arrays
    public static function serializeArray(array $objects): array {
        $serializedArray = [];
        foreach ($objects as $object) {
            if (method_exists($object, 'jsonSerialize')) {
                $serializedArray[] = $object->jsonSerialize();
            }
        }
        return $serializedArray;
    }

}