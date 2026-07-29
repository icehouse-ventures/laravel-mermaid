<?php

use IcehouseVentures\LaravelMermaid\Support\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

test('it generates array diagrams with Mermaid frontmatter', function () {
    $diagram = Builder::generateDiagramFromArray(
        ['A-->B', 'B-->C;'],
        'flowchart TD'
    );

    expect($diagram)
        ->toStartWith("---\nconfig: ")
        ->toContain('"theme":"base"')
        ->toContain("flowchart TD;\n")
        ->toContain("A-->B;\n")
        ->toContain("B-->C;\n")
        ->not->toContain('%%{init');
});

test('it merges diagram options over the selected theme', function () {
    $frontmatter = Builder::setTheme('forest', [
        'flowchart' => ['curve' => 'linear'],
    ]);

    expect($frontmatter)
        ->toContain('"theme":"forest"')
        ->toContain('"flowchart":{"curve":"linear"}');
});

test('it rejects missing custom theme files', function () {
    Builder::setTheme('missing-theme');
})->throws(InvalidArgumentException::class);

test('it generates diagrams from related Eloquent models without cycling', function () {
    $user = new MermaidTestModel;
    $user->setAttribute('id', 1);
    $user->setAttribute('name', 'Alice "Admin"');

    $post = new MermaidTestPost;
    $post->setAttribute('id', 'post-1');
    $post->setAttribute('name', 'Welcome');

    $user->setRelation('posts', new Collection([$post]));
    $post->setRelation('author', $user);

    $diagram = Builder::generateDiagramFromCollection(new Collection([$user]));

    expect($diagram)
        ->toContain('MermaidTestModel1["Alice &quot;Admin&quot;"]')
        ->toContain('MermaidTestPostpost_1["Welcome"]')
        ->toContain('MermaidTestModel1 --> MermaidTestPostpost_1');
});

class MermaidTestModel extends Model
{
    public $timestamps = false;
}

class MermaidTestPost extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;
}
