<?php

namespace App\Services;

use InvalidArgumentException;

class GitHubSearchQueryBuilder
{
    private array $conditions = [];
    private string $keywords = '';
    private array $allowedOperators = ['=', '>' , '<'];
    private int $perPage = 100;
    private int $page = 1;

    public function setKeywords(string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function where(string $field, string $operator, $value): self
    {
        if (!$this->isOperatorAllowed($operator)) {
            throw new InvalidArgumentException('Invalid operator: ' . $operator);
        }

        if ($operator === '=') {
            $condition = "{$field}:{$value}";
        }
        else {
            $condition = "{$field}:{$operator}{$value}";
        }
        $this->conditions[] = $condition;

        return $this;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function toArray(): array
    {
        $query = $this->keywords . ' ' . implode('+', $this->conditions);
//dump($query);
//dump($this->page);
        return [
            'q' => $query,
            'per_page' => $this->perPage,
            'page' => $this->page,
        ];
    }

    private function isOperatorAllowed(string $operator): bool
    {
        return in_array($operator, $this->allowedOperators, true);
    }
}
