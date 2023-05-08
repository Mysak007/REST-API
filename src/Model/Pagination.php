<?php

namespace App\Model;

use JMS\Serializer\Annotation\Groups as JMSGroups;
use JMS\Serializer\Annotation\VirtualProperty;

class Pagination
{
    private UserFilter $filter;

    #[JMSGroups(["pagination"])]
    private array $nodes;

    #[JMSGroups(["pagination"])]
    private int $total;

    public function __construct(UserFilter $filter, array $nodes, int $total)
    {
        $this->filter = $filter;
        $this->nodes = $nodes;
        $this->total = $total;
    }

    #[VirtualProperty]
    #[JMSGroups(["pagination"])]
    public function getTotalPages(): int
    {
        return (int)ceil($this->total / $this->filter->getLimit());
    }

    #[VirtualProperty]
    #[JMSGroups(["pagination"])]
    public function getCurrentPage(): int
    {
        return $this->filter->getPage();
    }

    public function getFilter(): UserFilter
    {
        return $this->filter;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
