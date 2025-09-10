<?php

if (!function_exists('strapiVar')) {
    function strapiVar(array $structure, array $sources, array $context = []) {
        return strapiResolveJson($structure, $sources, $context);
    }
}

if (!function_exists('printStrapiSection')) {
    function printStrapiSection( $blocks, $name, $strapiVars ){
        $output = strapiSection( $blocks, $name, $strapiVars );
        echo $output;
    }
}

if (!function_exists('printStrapi')) {
    function printStrapi( $comp, $strapiVars ){
        $output = strapiComp( $comp, $strapiVars );
        echo $output;
    }
}

if (!function_exists('strapiSection')) {
    function strapiSection( $blocks, $name, $strapiVars ){
        // Filtrar headers
        $section = array_filter($blocks, function ($item) use ($name) {
            return isset($item['section']) && $item['section'] === $name;
        });
         // Ordenar por 'sort'
        usort($section, function ($a, $b) {
            return $a['sort'] <=> $b['sort'];
        });

        $output = '';
        foreach ($section as $h) {
            switch($h['type']){
                case 'html':
                    $content = $h['html'];
                    break;
                case 'markdown':
                    $content = markdown_to_html($h['body']);
                    break;
                case 'text':
                    $content = nl2br(htmlspecialchars($h['body']));
                    break;
                case 'media-image':
                    $content = '<div style="text-align:center; margin-top:20px;">
                                    <img src="https://strapi.grupobd.mx'. $h['file']['url'] .'" alt="'.$h['file']['alternativeText'].'" 
                                        style="max-width:100%; height:auto; display:inline-block;">
                                </div>';
                    break;
                default:
                    $content = '';
            }
            // if (!empty($h['html'])) {
            //     $content = $h['html'];
            // } elseif (!empty($h['body'])) {
            //     $content = markdown_to_html($h['body']);
            // } else {
            //     $content = '';
            // }

            // asegurar que cada bloque quede separado
            $output .= $content . "\n";
        }

        // Reemplazar variables usando $strapiVars
        $output = preg_replace_callback('/{{(.*?)}}/', function($matches) use ($strapiVars) {
            $key = trim($matches[1]);
            // Busca en $strapiVars; si no existe, deja el {{variable}} tal cual
            return $strapiVars[$key] ?? $matches[0];
        }, $output);

        return $output;
    }
}

if (!function_exists('strapiComp')) {
    function strapiComp( $comp, $strapiVars, $type = 'md' ){
        
        $output = '';

        $content = $type != 'md' ? $comp : markdown_to_html($comp);

        // asegurar que cada bloque quede separado
        $output .= $content . "\n";


        // Reemplazar variables usando $strapiVars
        $output = preg_replace_callback('/{{(.*?)}}/', function($matches) use ($strapiVars) {
            $key = trim($matches[1]);
            // Busca en $strapiVars; si no existe, deja el {{variable}} tal cual
            return $strapiVars[$key] ?? $matches[0];
        }, $output);

        return $output;
    }
}

function strapiGetValueFromPath(array $array, string $path) {
    $keys = explode('.', $path);
    foreach ($keys as $key) {
        if (!isset($array[$key])) {
            return null;
        }
        $array = $array[$key];
    }
    return $array;
}

function strapiApplyTransforms($value, array $transforms, array $context = []) {
    foreach ($transforms as $t) {
        if (is_string($t) && function_exists($t)) {
            $value = $t($value);
        } elseif (is_array($t)) {
            foreach ($t as $fn => $param) {
                if ($fn === 'date') {
                    // Si el parámetro corresponde a una clave en $context, úsalo
                    if (isset($context[$param])) {
                        $format = $context[$param];
                    } else {
                        $format = $param;
                    }

                    try {
                        $dt = new DateTime($value);
                        $value = $dt->format($format);
                    } catch (Exception $e) {
                        $value = null; // deja que caiga al "default"
                    }
                }
            }
        }
    }
    return $value;
}

function strapiResolveJson(array $structure, array $sources, array $context = []) {
    foreach ($structure as $key => &$val) {
        if (is_array($val) && isset($val['source'])) {
            $source = $val['source'];
            $path   = $val['path'] ?? null;
            $value  = $path && isset($sources[$source]) 
                        ? strapiGetValueFromPath($sources[$source], $path) 
                        : null;

            if (!empty($val['transform'])) {
                $value = strapiApplyTransforms($value, $val['transform'], $context);
            }

            $val = $value ?? ($val['default'] ?? '');
        } elseif (is_array($val)) {
            $val = strapiResolveJson($val, $sources, $context);
        }
    }
    return $structure;
}