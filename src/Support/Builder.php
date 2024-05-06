<?php

namespace IcehouseVentures\LaravelMermaid\Support;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Builder
{

    // Entry point for generating a diagram from an array
    public static function generateDiagramFromArray(array $data, ?string $type = null): string
    {
        
        $diagram = self::formatArrayToLines($data);
        $diagram = self::setDiagramType($type) . $diagram;
        
        return $diagram;
    }

    // Format the array to lines to match markdown and mermaid format
    protected static function formatArrayToLines(array $data): string
    {
        return Collection::make($data)->map(fn($item) => "$item;\n")->join('');
    }

    // Set the diagram type to the mermaid diagram type
    protected static function setDiagramType(?string $type): string
    {
        return ($type ?? "graph LR") . ";\n";

    }

    // Entry point for generating a diagram from a collection of models
    public static function generateDiagramFromCollection(Collection $models, ?string $label = null, ?string $type = null): string
    {
        $diagram = self::formatCollectionToLines($models, $label);
        $diagram = self::setDiagramType($type) . $diagram;
        
        return $diagram;
    }

    // Format the eloquent models into lines to match the mermaid data format
    protected static function formatCollectionToLines(Collection $models, ?string $label = null, $parentModel = null): string
    {
        
        $lines = [];
    
        foreach ($models as $model) {
            
            $className = class_basename($model);
            $key = $model->getKey();
            $modelLabel = $label ? $model->{$label} : $model->getNameAttribute() ?? $className.' '.$key;
    
            // Object node
            $lines[] = "{$className}{$key}[$modelLabel];\n";
    
            if ($parentModel !== null) {
                $parentKey = $parentModel->getKey();
                $parentClassName = class_basename($parentModel);
                // Relationship
                $lines[] = "{$parentClassName}{$parentKey} --> {$className}{$key};\n";
            }
    
            foreach ($model->getRelations() as $relationName => $relation) {
                // hasOne or belongsTo or morphOne
                if ($relation instanceof Model) {
                    $relatedKey = $relation->getKey();
                    $relatedLabel = $label ? $relation->{$label} : $relation->getNameAttribute() ?? $relation->getKey();
                    $relatedClassName = class_basename($relation);

                    $lines[] = "{$relatedClassName}{$relatedKey}[$relatedLabel];\n";
                    $lines[] = "{$className}{$key} --> {$relatedClassName}{$relatedKey};\n";
                } 
                // hasMany or belongsToMany or morphMany
                elseif ($relation instanceof Collection) {
                    $lines[] = self::formatCollectionToLines($relation, $label, $model);
                }
            }
        }
    
        return implode('', $lines);
    }
}