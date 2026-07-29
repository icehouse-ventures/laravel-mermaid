<?php

namespace IcehouseVentures\LaravelMermaid\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use JsonException;
use Stringable;

class Builder
{
    public function build(): self
    {
        return new self;
    }

    /**
     * Generate a diagram from a PHP array.
     *
     * @param  array<array-key, scalar|Stringable>  $data
     * @param  array<string, mixed>|null  $options
     */
    public static function generateDiagramFromArray(array $data, ?string $type = null, ?array $options = []): string
    {
        return (self::setTheme(options: $options ?? []) ?? '')
            .self::setDiagramType($type)
            .self::formatArrayToLines($data);
    }

    /**
     * @param  array<array-key, scalar|Stringable>  $data
     */
    protected static function formatArrayToLines(array $data): string
    {
        return Collection::make($data)
            ->map(static fn (bool|float|int|string|Stringable $item): string => rtrim((string) $item, ";\n\r\t ").";\n")
            ->join('');
    }

    protected static function setDiagramType(?string $type): string
    {
        return rtrim($type ?? 'flowchart LR', ";\n\r\t ").";\n";
    }

    /**
     * Build Mermaid v10.5+ frontmatter for a built-in or packaged theme.
     *
     * @param  array<string, mixed>  $options
     *
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    public static function setTheme(?string $theme = null, array $options = []): ?string
    {
        if ($theme === '') {
            $theme = null;
        }

        $theme ??= (string) config('mermaid.theme', 'default');

        if (in_array($theme, ['base', 'forest', 'dark', 'neutral', 'default'], true)) {
            $themeConfig = ['theme' => $theme];
        } else {
            $themeFile = $theme === 'custom'
                ? (string) config('mermaid.themeFile', 'tailwind')
                : $theme;

            $themeFileExtension = Str::endsWith($themeFile, '.json') ? $themeFile : $themeFile.'.json';
            $themeFilePath = __DIR__.'/../Themes/'.$themeFileExtension;

            if (! is_file($themeFilePath) || ! is_readable($themeFilePath)) {
                throw new InvalidArgumentException("Mermaid theme file [{$themeFileExtension}] does not exist.");
            }

            $contents = file_get_contents($themeFilePath);

            if ($contents === false) {
                throw new InvalidArgumentException("Mermaid theme file [{$themeFileExtension}] could not be read.");
            }

            /** @var array<string, mixed> $themeConfig */
            $themeConfig = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
            unset($themeConfig['themeName']);
        }

        $configuration = array_replace_recursive($themeConfig, $options);

        if ($configuration === []) {
            return null;
        }

        $json = json_encode(
            $configuration,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR
        );

        return "---\nconfig: {$json}\n---\n";
    }

    /**
     * Generate a diagram from an Eloquent model collection.
     *
     * @param  Collection<array-key, Model>  $models
     * @param  array<string, mixed>|null  $options
     */
    public static function generateDiagramFromCollection(
        Collection $models,
        ?string $label = null,
        ?string $type = null,
        ?array $options = []
    ): string {
        return (self::setTheme(options: $options ?? []) ?? '')
            .self::setDiagramType($type)
            .self::formatCollectionToLines($models, $label);
    }

    /**
     * Iterate over Eloquent models and convert them to Mermaid lines.
     *
     * @param  Collection<array-key, Model>  $models
     * @param  array<int, true>  $visited
     */
    protected static function formatCollectionToLines(
        Collection $models,
        ?string $label = null,
        ?Model $parentModel = null,
        array &$visited = []
    ): string {
        $lines = [];

        foreach ($models as $model) {
            $node = self::modelNode($model);
            $modelLabel = self::modelLabel($model, $label);

            $lines[] = "{$node}[\"{$modelLabel}\"];\n";

            if ($parentModel !== null) {
                $lines[] = self::modelNode($parentModel)." --> {$node};\n";
            }

            $objectId = spl_object_id($model);

            if (isset($visited[$objectId])) {
                continue;
            }

            $visited[$objectId] = true;

            foreach ($model->getRelations() as $relation) {
                if ($relation instanceof Model) {
                    $relatedNode = self::modelNode($relation);
                    $relatedLabel = self::modelLabel($relation, $label);

                    $lines[] = "{$relatedNode}[\"{$relatedLabel}\"];\n";
                    $lines[] = "{$node} --> {$relatedNode};\n";
                } elseif ($relation instanceof Collection) {
                    /** @var Collection<array-key, Model> $relation */
                    $lines[] = self::formatCollectionToLines($relation, $label, $model, $visited);
                }
            }
        }

        return implode('', $lines);
    }

    private static function modelNode(Model $model): string
    {
        $key = $model->getKey() ?? spl_object_id($model);
        $node = class_basename($model).$key;

        return preg_replace('/[^A-Za-z0-9_]/', '_', $node) ?: 'Model'.spl_object_id($model);
    }

    private static function modelLabel(Model $model, ?string $label): string
    {
        $value = $label === null
            ? ($model->getAttribute('name') ?? class_basename($model).' '.($model->getKey() ?? ''))
            : data_get($model, $label);

        return str_replace(
            ['\\', '"', "\r", "\n"],
            ['\\\\', '&quot;', ' ', ' '],
            (string) $value
        );
    }
}
