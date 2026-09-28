<?php

declare(strict_types=1);

namespace App\Sections;

class ConfigurableSection extends BaseSection
{
    public function __construct(
        protected string $key,
        protected string $label,
        protected string $category,
        protected string $icon = 'heroicon-o-rectangle-stack',
        protected array $defaults = [],
        protected array $schema = [],
        protected ?string $viewName = null,
        protected ?string $previewSvg = null
    ) {}

    public function key(): string
    {
        return $this->key;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function icon(): string
    {
        return $this->icon;
    }

    public function defaults(): array
    {
        return $this->defaults;
    }

    public function schema(): array
    {
        return $this->schema;
    }

    public function view(): string
    {
        return $this->viewName ?: 'sections.' . str_replace('_', '-', $this->key);
    }

    public function preview(): string
    {
        if ($this->previewSvg) {
            return $this->previewSvg;
        }

        return parent::preview();
    }
}
