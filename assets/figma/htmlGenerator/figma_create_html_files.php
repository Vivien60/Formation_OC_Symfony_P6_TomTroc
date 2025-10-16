<?php
declare(strict_types=1);

// Fonction récursive pour filtrer et ne garder que name et children
function canvasToHtmlPages(array $items): array
{
    $pages = [];

    foreach ($items as $item) {
        $pages[] = [
            'name' => $item['name'],
            'html' => arrayToHtml($item['children'] ?? [])
        ];
    }

    return $pages;
}

function arrayToHtml(mixed $children) : string
{
    $html = '';
    foreach ($children as $child) {
        if(!empty($child['children'])) {
            $htmlContent = arrayToHtml($child['children']);
        } else {
            $htmlContent = null;
        }
        $tag = getHtmlTag($child['name']);
        $html .= convertToHtml($tag, $htmlContent);
    }
    return $html;
}

function convertToHtml(array $tag, ?string $htmlInside) : string
{
    if(empty($tag['close'])) {
        $htmlMask = '<%1$s>';
    } else {
        $htmlMask = '<%1$s>%3$s</%2$s>';
        $htmlInside ??= "text for ".$tag['open'];
    }

    return sprintf($htmlMask, $tag['open'], $tag['close'], $htmlInside);
}

function getHtmlTag($name): array
{
    $tagName = str_replace(['<', '>'], '', $name);
    $tagWithoutClosingTag = [ 'input', 'br', 'hr', 'img', ];
    if(!in_array($tagName, $tagWithoutClosingTag)) {
        $tag['close'] = "$tagName";
    } else {
        $tag['close'] = null;
    }
    $tag['open'] = "$tagName";
    return $tag;
}

$decoupageCanvas = json_decode(file_get_contents(__DIR__ . '/figma_decoupage_extract.json'), true);

// Extraire les frames de premier niveau
$htmlPages = canvasToHtmlPages($decoupageCanvas);

// Écrire dans le fichier
yaml_emit_file(dirname(__DIR__) . '/export/figma_decoupage_html_pages.yaml', $htmlPages, YAML_UTF16BE_ENCODING);
