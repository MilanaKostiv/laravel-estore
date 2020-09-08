<?php

namespace App\Services;

/**
 * Gather conditions for custom requests.
 */
class SearchCriteria
{
    /**
     * @var string[]
     */
    private $resetOptions = ['limit', 'order', 'where', 'relationship'];

    /**
     * @var array
     */
    private $data = [];

    /**
     * Add relationship data to Search Criteria.
     *
     * @param string $relationship
     * @return void
     */
    public function addRelationship(string $relationship): void
    {
        $this->data['relationship'][] = $relationship;
    }

    /**
     * Get added relationships.
     *
     * @return array
     */
    public function getRelationships(): array
    {
        return $this->data['relationship'] ?? [];
    }

    /**
     * Add where condition.
     *
     * Available values for operator: =, >, <, <=, >=, <>, !=, <=>, like
     *
     * @param string $key
     * @param string $operator
     * @param $value
     * @return void
     */
    public function addWhere(string $key, string $operator, $value): void
    {
        $this->data['where'][] = [
            'key' => $key,
            'operator' => $operator,
            'value' => $value
        ];
    }

    /**
     * Get added where conditions.
     *
     * @return array
     */
    public function getWhere(): array
    {
        return $this->data['where'] ?? [];
    }

    /**
     * Add order to search criteria.
     * Available values for order: rand, asc, desc
     *
     * @param $order
     * @param array $fields
     * @return void
     */
    public function addSortOrder($order, $fields = []): void
    {
        $this->data['order'][$order] = $fields;
    }

    /**
     * Get sort order.
     *
     * @return array
     */
    public function getSortOrders(): array
    {
        return $this->data['order'] ?? [];
    }

    /**
     * Add limit to search criteria.
     *
     * @param int|null $limit
     * @param int|null $offset
     * @return void
     */
    public function addLimit(?int $limit, ?int $offset = null): void
    {
        if ($limit !== null) {
            $this->data['limit']['limit'] = $limit;

            if ($offset !== null) {
                $this->data['limit']['offset'] = $offset;
            }
        }
    }

    /**
     * Get search limit.
     *
     * @return array
     */
    public function getLimit(): array
    {
        return $this->data['limit'] ?? [];
    }

    /**
     * Reset search criteria parts.
     *
     * @param string|null $resetPart
     * @return void
     */
    public function reset(?string $resetPart = null): void
    {
        $resetParts = [];
        if ($resetPart === null) {
            $resetParts = $this->resetOptions;
        } elseif (\in_array($resetPart, $this->resetOptions)) {
            $resetParts = [$resetPart];
        }

        if (!empty($resetParts)) {
            foreach ($resetParts as $option) {
                $this->data[$option] = [];
            }
        }
    }
}