<?php

namespace Thoughts\Contracts;

/**
 * Contract for resources that can be searched.
 *
 * @package Thoughts\Contracts
 */
interface SearchableResource
{

    /**
     * @return int
     */
    public function indexIdentifier();

    /**
     * @return string
     */
    public function indexType();

    /**
     * @return string
     */
    public function indexBody();

    /**
     * @return array
     */
    public function indexMeta();

}
