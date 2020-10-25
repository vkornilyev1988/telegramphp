<?php
namespace App\Modules;


use Nekrida\Database\Query;

class DestinationGroup extends Query
{
    public const TABLE_NAME = 'destinations_groups';

    public static function buildTree($elements, $parent = NULL) {

        $branch = [];
        foreach ($elements as $element ) {
            if ($element['parent'] === $parent) {
                $children = static::buildTree($elements,$element['id']);
                if ($children)
                    $element['children'] = $children;
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
