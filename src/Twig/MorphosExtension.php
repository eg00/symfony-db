<?php

namespace App\Twig;

use morphos\Cases;
use morphos\Gender;
use morphos\Russian\CardinalNumeralGenerator;
use morphos\Russian\MoneySpeller;
use morphos\Russian\NounPluralization;
use morphos\Russian\OrdinalNumeralGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use function morphos\Russian\inflectName;
use function morphos\Russian\pluralize;

class MorphosExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('plural', [$this, 'pluralFilter']),
            new TwigFilter('money', [$this, 'moneyFilter']),
            new TwigFilter('numeral', [$this, 'numeralFilter']),
            new TwigFilter('ordinal', [$this, 'ordinalFilter']),
            new TwigFilter('name', [$this, 'nameFilter']),
        ];
    }

    public function pluralFilter($word, $count)
    {
        return pluralize($count, $word);
    }

    public function moneyFilter($value, $currency)
    {
        return MoneySpeller::spell($value, $currency, MoneySpeller::SHORT_FORMAT);
    }

    public function numeralFilter($word, $count = null, $gender = Gender::MALE)
    {
        if ($count === null) {
            return CardinalNumeralGenerator::getCase($word, Cases::NOMINATIVE);
        }

        if (in_array($count, ['m', 'f', 'n'])) {
            return CardinalNumeralGenerator::getCase($word, Cases::NOMINATIVE, $count);
        }

        return CardinalNumeralGenerator::getCase($count, Cases::NOMINATIVE, $gender) . ' ' . NounPluralization::pluralize($word, $count);
    }

    public function ordinalFilter($number, $gender = Gender::MALE)
    {
        return OrdinalNumeralGenerator::getCase($number, Cases::NOMINATIVE, $gender);
    }

    public function nameFilter($name, $gender = null, $case = null)
    {
        if ($case === null) {
            return inflectName($name, $gender);
        }

        return inflectName($name, $case, $gender);
    }
}
