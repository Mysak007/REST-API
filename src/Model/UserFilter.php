<?php

namespace App\Model;

class UserFilter
{
    public const DEFAULT_ITEMS_PER_PAGE = 10;

    protected int $page = 1;
    protected int $limit = 10;

    protected string $sortByColumn = '';
    protected string $sortDirection = '';

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getSortByColumn(): string
    {
        return $this->sortByColumn;
    }

    public function setSortByColumn(string $sortByColumn): void
    {
        $this->sortByColumn = $sortByColumn;
    }

    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    public function setSortDirection(string $sortDirection): void
    {
        $this->sortDirection = $sortDirection;
    }

    public static function calculateTotalPage(int $usersTotalCount): int
    {
        return (int)ceil($usersTotalCount / self::DEFAULT_ITEMS_PER_PAGE);
    }
}
