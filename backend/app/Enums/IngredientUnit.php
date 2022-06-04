<?php

namespace App\Enums;

enum IngredientUnit: string
{
    use EnumValues;

    case Kilograms = 'кг';
    case Grams = 'г';
    case Liters = 'л';
    case Milliliters = 'мл';
    case Pieces = 'шт';
}
