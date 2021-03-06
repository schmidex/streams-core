<?php

namespace Streams\Core\Criteria\Format;

use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;
use Filebase\Format\FormatInterface;

/**
 * Class Template
 *
 * @link    http://pyrocms.com/
 * @author  PyroCMS, Inc. <support@pyrocms.com>
 * @author  Ryan Thompson <ryan@pyrocms.com>
 */
class Template implements FormatInterface
{

    /**
     * Get the format's file extension.
     * 
     * @return string
     */
    public static function getFileExtension()
    {
        return 'tpl';
    }

    /**
     * Encode the data for storage.
     * 
     * @param array $data
     * @param bool $pretty
     * @return string
     */
    public static function encode($data, $pretty)
    {
        $data = (array) $data;

        $template = Arr::pull($data['data'], 'template');

        $encoded = $data ? Yaml::dump(Arr::pull($data, 'data')) : null;

        return "---\n{$encoded}---\n{$template}";
    }

    /**
     * Decode the data from storage.
     * 
     * @param $data
     * @return mixed
     */
    public static function decode($data)
    {
        if (is_array($data) && isset($data['template'])) {
            $data = $data['template'];
        }

        $pattern = '/^[\s\r\n]?---[\s\r\n]?$/sm';

        $parts = preg_split($pattern, PHP_EOL . ltrim($data));

        if (count($parts) < 3) {
            return [];
        }

        if (!$matter = json_decode(trim($parts[1]), true)) {
            $matter = Yaml::parse(trim($parts[1]));
        }

        $template = implode(PHP_EOL . '---' . PHP_EOL, array_slice($parts, 2));

        return [
            'data' => array_merge(Arr::get($matter, 'data', $matter), ['template' => $template])
        ];
    }
}
