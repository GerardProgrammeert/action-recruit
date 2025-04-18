<?php

namespace App\Clients\GitHubClient;

use InvalidArgumentException;

class GitHubSearchQueryBuilder
{
    private array $conditions = [];
    private string $keywords = '';
    private array $allowedOperators = ['=', '>' , '<'];
    private int $perPage = 100;
    private int $offset = 1;

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

        $condition = ($operator === '=') ? "$field:$value" : "$field:$operator$value";
        $this->conditions[] = $condition;

        return $this;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }
    /**
     *@return array<string, array<string, int,string>>
     */
    public function toArray(): array
    {
        $query = $this->keywords . ' ' . implode(' ', $this->conditions);

        return [
            'query' =>
                [
                    'q' => $query,
                    'per_page' => $this->perPage,
                    'page' => $this->offset,
                ],
        ];
    }

    private function isOperatorAllowed(string $operator): bool
    {
        return in_array($operator, $this->allowedOperators, true);
    }
}
