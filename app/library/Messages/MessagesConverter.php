<?php

namespace Library\Messages;

use Phalcon\Messages\Message;

class MessagesConverter
{
    /**
     * @param $messages array<Message> | Messages
     */
    public static function convertCollectionForLog($messages) : array
    {
        $response = [];
        foreach ($messages as $message) {
            $response = [
                ...$response,
                ...self::convertForLog($message)
            ];
        }
        return $response;
    }

    public static function convertForLog(Message $message) : array
    {
        $transformed = self::logTransformer($message);
        $filtered = self::removeEmptyItems($transformed);

        $result = [];
        foreach($filtered as $key => $value) {
            $result[] = $key . ': ' . $value;
        }

        return $result;
    }

    private static function logTransformer(Message $message) : array
    {
        return [
            'Code' => $message->getCode(),
            'Message' => $message->getMessage(),
            'MetaData' => $message->getMetaData(),
        ];
    }

    private static function removeEmptyItems(array $items) : array
    {
        return array_filter(
            $items,
            function ($item) {
                return !empty($item);
            }
        );
    }

    /**
     * @param $messages array<Message> | Messages
     */
    public static function convertCollectionForApi($messages) : array
    {
        $response = [];
        foreach ($messages as $message) {
            $response[] = self::convertForApi($message);
        }
        return $response;
    }

    public static function convertForApi(Message $message) : array
    {
        $transformed = self::apiTransformer($message);
        return self::removeEmptyItems($transformed);
    }

    private static function apiTransformer(Message $message) : array
    {
        return [
            'status' => $message->getCode(),
            'detail' => $message->getMessage(),
            'source' => $message->getMetaData(),
        ];
    }

}
