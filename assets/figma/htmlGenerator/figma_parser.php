<?php
declare(strict_types=1);

// Fonction récursive pour filtrer et ne garder que name et children
function filterFrames(array $items): array
{
    $result = [];

    foreach ($items as $item) {
        if ($item['type'] === 'FRAME') {
            $filtered = ['name' => $item['name']];

            if (!empty($item['children'])) {
                $filtered['children'] = filterFrames($item['children']);
            }
            //On trie de haut en bas, de gauche vers la droite. comme le plus haut est 0, ça fonctionne.
            $keyForOrdering = $item['absoluteBoundingBox']['y'].$item['absoluteBoundingBox']['x'].$item['id'];
            $result[$keyForOrdering] = $filtered;
        }   ksort($result, SORT_NUMERIC);
    }

    return $result;
}

$jsonContent = file_get_contents(dirname(__DIR__) . '/figma_files.json');
$data = json_decode($jsonContent, true);

// Trouver la page "Decoupage"
$decoupagePage = null;
foreach ($data['document']['children'] as $page) {
    if ($page['name'] === 'Decoupage') {
        $decoupagePage = $page;
        break;
    }
}

// Extraire les frames de premier niveau
$frames = filterFrames($decoupagePage['children']);

// Écrire dans le fichier
file_put_contents(
    __DIR__ . '/figma_decoupage_extract.json',
    json_encode($frames, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);
