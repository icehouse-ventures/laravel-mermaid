<?php

namespace IcehouseVentures\LaravelMermaid\Support;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Builder
{

    // Entry point for generating a diagram from an array
    public static function generateDiagram(array $data, ?string $type = null): string
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
    public static function generateDiagramFromCollection(Collection $models, ?string $type = null): string
    {
        $diagram = self::formatCollectionToLines($models);
        $diagram = self::setDiagramType($type) . $diagram;
        
        return $diagram;
    }

    // Format the eloquent models into lines to match the mermaid data format
    protected static function formatCollectionToLines(Collection $models, $parentModel = null): string
    {
        $lines = [];
    
        foreach ($models as $model) {
            $key = $model->getKey();
            $label = $model->getNameAttribute();
            $className = class_basename($model);
    
            //Parent model object
            $lines[] = "{$className}{$key}[$label];\n";
    
            if ($parentModel !== null) {
                $parentKey = $parentModel->getKey();
                $parentClassName = class_basename($parentModel);
                $lines[] = "{$parentClassName}{$parentKey} --> {$className}{$key};\n";
            }
    
            foreach ($model->getRelations() as $relationName => $relation) {
                // hasOne or belongsTo or morphOne
                if ($relation instanceof Model) {
                    $relatedKey = $relation->getKey();
                    $relatedLabel = $relation->getNameAttribute();
                    $relatedClassName = class_basename($relation);
    
                    $lines[] = "{$relatedClassName}{$relatedKey}[$relatedLabel];\n";
                    $lines[] = "{$className}{$key} --> {$relatedClassName}{$relatedKey};\n";
                } 
                // hasMany or belongsToMany or morphMany
                elseif ($relation instanceof Collection) {
                    $lines[] = self::formatModelsToLines($relation, $model);
                }
            }
        }
    
        return implode('', $lines);
    }
}