<?php

namespace App\Services;

/**
 * Processes search criteria for given entity.
 */
class EntityProcessor
{
    /**
     * @var string
     */
    private $entity;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    private $builder;

    /**
     * @param string $entity
     */
    public function __construct(string $entity)
    {
        $this->entity = $entity;
        $this->builder = $entity::query();
    }

    /**
     * Apply search criteria to entity builder.
     *
     * @param SearchCriteria $searchCriteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function process(SearchCriteria $searchCriteria): \Illuminate\Database\Eloquent\Builder
    {
        $this->processRelationships($searchCriteria);
        $this->processWhere($searchCriteria);
        $this->processSortOrder($searchCriteria);
        $this->processLimit($searchCriteria);

        return $this->builder;
    }

    /**
     * Apply relationships.
     *
     * @param SearchCriteria $searchCriteria
     * @return void
     */
    private function processRelationships(SearchCriteria $searchCriteria): void
    {
        $relationships = $searchCriteria->getRelationships();
        $entityName = $this->entity;

        foreach ($relationships as $relationship) {
            $this->builder = $entityName::joinRelationship($relationship);
        }
    }

    /**
     * Apply where conditions.
     *
     * @param SearchCriteria $searchCriteria
     * @return void
     */
    private function processWhere(SearchCriteria $searchCriteria): void
    {
        $whereConditions = $searchCriteria->getWhere();

        foreach ($whereConditions as $where) {
            $this->builder->where($where['key'], $where['operator'], $where['value']);
        }
    }

    /**
     * Apply sort order.
     *
     * @param SearchCriteria $searchCriteria
     * @return void
     */
    private function processSortOrder(SearchCriteria $searchCriteria): void
    {
        $orderConditions = $searchCriteria->getSortOrders();

        foreach($orderConditions as $order => $columns) {
            if ($order === 'rand') {
                $this->builder->inRandomOrder();
                continue;
            }
            if (\in_array($order, ['asc', 'desc'])) {
                foreach($columns as $column){
                    $this->builder->orderBy($column, $order);
                }
            }
        }
    }

    /**
     * Apply limit.
     *
     * @param SearchCriteria $searchCriteria
     * @return void
     */
    private function processLimit(SearchCriteria $searchCriteria): void
    {
        $limit = $searchCriteria->getLimit();

        if (!empty($limit)) {
            if (!empty($limit['offset'])) {
                $this->builder->offset($limit['offset']);
            }
            if (!empty($limit['limit'])) {
                $this->builder->take($limit['limit']);
            }
        }
    }
}
