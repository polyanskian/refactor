<?php

namespace NW\WebService\References\Operations\Notification\Interfaces;

interface SerializerInterface
{
    public function normalize($data, array $settings = []): array;
    public function denormalize(string $class, $data);
}
