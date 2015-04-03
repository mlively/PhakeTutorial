<?php
namespace PhakeStore;

interface EventService
{
    /**
     * Fires events into our event service
     *
     * @param string $type
     * @param array $context
     * @return mixed
     */
    public function fireEvent($type, array $context);
}