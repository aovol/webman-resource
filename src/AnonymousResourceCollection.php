<?php

namespace WebmanResource;

use Countable;
use IteratorAggregate;
use support\Request;

class AnonymousResourceCollection extends ResourceCollection implements Countable, IteratorAggregate
{
    /**
     *
     * @var string
     */
    public $collects;

    /**
     *
     * @param mixed $resource
     * @param string $collects
     */
    public function __construct($resource, $collects)
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }
}
