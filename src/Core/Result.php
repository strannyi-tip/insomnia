<?php

namespace StrannyiTip\Insomnia\Core;

use StrannyiTip\Insomnia\Exception\FieldNotPresentException;

/**
 * Result.
 */
final class Result
{
    /**
     * Request result data.
     *
     * @var array
     */
    private array $data = [];


    /**
     * Result.
     *
     * @param array $data Result data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Is data has field.
     *
     * @param string $field Field
     *
     * @return bool
     */
    public function has(string $field): bool
    {
        return \array_key_exists($field, $this->data);
    }

    /**
     * Get result field.
     *
     * @param string $field Field
     *
     * @return mixed
     */
    public function get(string $field): mixed
    {
        if (!$this->has($field)) {
            throw new FieldNotPresentException('Field ' . $field . ' not present in response data.');
        }

        return $this->data[$field];
    }

    /**
     * Is empty result present.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return \count($this->data);
    }
}