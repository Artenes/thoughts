<?php

namespace Thoughts;

use Illuminate\Database\Eloquent\Model;
use Thoughts\Contracts\SearchableResource;

/**
 * A searchable resource in the application.
 *
 * @package Thoughts
 */
class Searchable extends Model
{

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * Index a resource for search.
     *
     * @param SearchableResource $resource
     * @return Searchable
     */
    public function indexResource(SearchableResource $resource)
    {

        $searchable = $this->getInstance($resource->indexType(), $resource->indexIdentifier());

        $searchable->body = $resource->indexBody();

        $searchable->meta = json_encode($resource->indexMeta());

        $searchable->save();

        return $searchable;

    }

    /**
     * Retrieves the searchable instance for a resource.
     *
     * @param $type
     * @param $identifier
     * @return Searchable
     */
    public function getInstance($type, $identifier)
    {

        return static::where([['identifier', $identifier], ['type', $type]])->first() ?: new static(['identifier' => $identifier, 'type' => $type]);

    }

    /**
     * @param $value
     * @return array
     */
    public function getMetaAttribute($value)
    {

        return json_decode($value, true);

    }

}
