<?php

namespace Detail\Filtering\Exception;

class FilterException extends RuntimeException
{
    /**
     * @param array $messages
     * @return FilterException
     */
    public static function fromMessages(array $messages)
    {
        $formattedMessages = array();
        $flattenedMessages = self::flattenMessages($messages);

        foreach ($flattenedMessages as $rule => $ruleMessages) {
            $formattedMessages[] = sprintf('"%s": %s', $rule, $ruleMessages);
        }

        return new static(implode("; ", $formattedMessages));
    }

    /**
     * @param array $messages
     * @param string $prefix
     * @return array
     */
    protected static function flattenMessages(array $messages, $prefix = '')
    {
        $result = array();

        foreach ($messages as $rule => $message) {
            $rule = $prefix . (empty($prefix) ? '' : '.') . $rule;

            if (is_array($message)) {
                $result = array_merge($result, self::flattenMessages($message, $rule));
            } else {
                $result[$rule] = $message;
            }
        }

        return $result;
    }
}
