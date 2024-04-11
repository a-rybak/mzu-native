<?php

enum Measure: string
{
    case Liquid500 = 'Розчин 500 мг/мл';
    case Liquid1000 = 'Розчин 1000 мг/мл';
    case Pills = 'Таблетки 0.5г';

    public static function getById($id)
    {
        return self::cases()[$id];
    }

    public static function getValueById($id)
    {
        return self::getById($id)->value;
    }

    public static function getNameById($id)
    {
        return self::getById($id)->name;
    }

    public static function getValueByName($name)
    {
        $index = array_search($name, array_column(self::cases(), 'name'));
        return self::getValueById($index);
    }
}