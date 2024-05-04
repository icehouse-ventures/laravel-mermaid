<?php

namespace IcehouseVentures\LaravelMermaid\Support;

class Builder
{
    public static function generateDiagram(array $data, string $type = 'graph'): string
    {
        $diagram = "```mermaid\n";
        $diagram .= "$type\n";

        foreach ($data as $item) {
            $diagram .= "$item\n";
        }

        $diagram .= "```";

        return $diagram;
    }
}