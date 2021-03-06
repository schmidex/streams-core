<?php

namespace Streams\Core\Criteria\Contract;

use Streams\Core\Entry\Contract\EntryInterface;
use Streams\Core\Repository\Contract\RepositoryInterface;

/**
 * Interface CriteriaInterface
 *
 * @link    http://pyrocms.com/
 * @author  PyroCMS, Inc. <support@pyrocms.com>
 * @author  Ryan Thompson <ryan@pyrocms.com>
 */
interface CriteriaInterface
{

    /**
     * Return all entries.
     * 
     * @return Collection
     */
    public function all();

    /**
     * Return an entry by ID.
     * 
     * @param string $id
     * @return nunll|EntryInterface
     */
    public function find($id);

    /**
     * Return the first entry only.
     * 
     * @return null|EntryInterface
     */
    public function first();

    /**
     * Add criteria for sorting entries.
     *
     * @param string $field
     * @param string|null $direction
     * @param string|null $value
     * @return $this
     */
    public function orderBy($field, $direction = 'asc');

    /**
     * Limit the entries returned.
     *
     * @param int $limit
     * @param int|null $offset
     * @return $this
     */
    public function limit($limit, $offset = 0);

    /**
     * Add criteria for returning entries.
     *
     * @param string $field
     * @param string|null $operator
     * @param string|null $value
     * @return $this
     */
    public function where($field, $operator = null, $value = null);

    /**
     * Get the criteria results.
     * 
     * @return Collection
     */
    public function get();

    /**
     * Count the criteria results.
     * 
     * @return int
     */
    public function count();

    /**
     * Create a new entry.
     *
     * @param array $attributes
     * @return EntryInterface
     */
    public function create(array $attributes = []);

    /**
     * Save an entry.
     *
     * @param  EntryInterface $entry
     * @return bool
     */
    public function save(EntryInterface $entry);

    /**
     * Delete an entry.
     *
     * @param EntryInterface $entry
     * @return bool
     */
    public function delete(EntryInterface $entry);

    /**
     * Delete all entries.
     *
     * @return void
     */
    public function truncate();

    /**
     * Return a new entry instance.
     *
     * @param array $attributes
     * @return EntryInterface
     */
    public function newInstance(array $attributes = []);
}
