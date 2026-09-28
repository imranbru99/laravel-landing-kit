<?php

declare(strict_types=1);

namespace App\Sections;

use App\Contracts\SectionTypeInterface;

abstract class BaseSection implements SectionTypeInterface
{
    abstract public function key(): string;

    abstract public function label(): string;

    abstract public function category(): string;

    public function icon(): string
    {
        return 'heroicon-o-rectangle-stack';
    }

    public function schema(): array
    {
        return [];
    }

    public function defaults(): array
    {
        return [];
    }

    public function view(): string
    {
        return 'sections.'.str_replace('_', '-', $this->key());
    }

    public function preview(): string
    {
        // Clean default SVG placeholder thumbnail
        return '<svg class="w-full h-full text-slate-400" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="200" height="120" rx="6" fill="#F8FAFC"/><rect x="20" y="25" width="80" height="12" rx="3" fill="#CBD5E1"/><rect x="20" y="45" width="160" height="6" rx="2" fill="#E2E8F0"/><rect x="20" y="58" width="140" height="6" rx="2" fill="#E2E8F0"/><rect x="20" y="75" width="50" height="18" rx="4" fill="#10B981"/></svg>';
    }

    /**
     * Standard design & spacing options applicable to any section.
     */
    public function defaultStyle(): array
    {
        return [
            'padding_top' => 'py-10',
            'padding_bottom' => 'py-10',
            'bg_color' => 'bg-white',
            'text_color' => 'text-slate-900',
            'container_width' => 'max-w-5xl',
            'custom_class' => '',
            'custom_id' => '',
        ];
    }
}
