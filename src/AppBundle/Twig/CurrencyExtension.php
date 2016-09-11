<?php

namespace AppBundle\Twig;

use Symfony\Component\Translation\Translator;

/**
 * Class CurrencyExtension
 */
class CurrencyExtension extends \Twig_Extension
{
    private $translator;

    /**
     * CurrencyExtension constructor.
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            'currency_symbol' => new \Twig_Filter_Method($this, 'currencySymbolFilter'),
            'to_words' => new \Twig_Filter_Method($this, 'numberToWordsFilter'),
            'percent' => new \Twig_Filter_Method($this, 'percentFilter'),
        ];
    }

    /**
     * @param string $currencyCode
     * @param string $locale
     * @return bool|string
     */
    public function currencySymbolFilter($currencyCode, $locale = null)
    {
        if ($locale === null) {
            $locale = \Locale::getDefault();
        }
        $formatter = new \NumberFormatter($locale.'@currency='.$currencyCode, \NumberFormatter::CURRENCY);

        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }

    /**
     * @param string|float $sum
     * @return string
     */
    public function numberToWordsFilter($sum)
    {
        $intPart = floor($sum);
        $decimalPart = round(($sum - $intPart) * 100);
        $result = [];
        $var = $intPart;
        for ($i = 1; $var > 0; $i++) {
            $result[] = $var % 1000;
            $var = floor($var / 1000);
        }

        $strResult = '';
        for ($i = count($result) - 1; $i >= 0; $i--) {
            $strResult .= $this->toWords($result[$i], $i === 1);
            if ($i !== 0) {
                $strResult .= ' '.$this->translator->transChoice('number.unit.'.pow(1000, $i), $result[$i], [], 'numbers');
            }
        }
        if ($intPart > 0) {
            $strResult .= ' '.$this->translator->transChoice('number.unit.currency', $intPart, [], 'numbers');
        }
        if ($decimalPart > 0) {
            $strResult .= ' '.$this->toWords($decimalPart, true).' '.$this->translator->transChoice('number.unit.small_currency', $decimalPart, [], 'numbers');
        }

        return $strResult;
    }

    /**
     * @param string $value
     * @param string $default
     * @return bool|string
     */
    public function percentFilter($value, $default = '-')
    {
        if (is_numeric($value)) {
            $value *= 100;
            $value .= ' %';
        } else {
            $value = $default;
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'currency_extension';
    }

    /**
     * @param      $number
     * @param bool $isOther
     * @return string
     */
    private function toWords($number, $isOther = false)
    {
        $result = '';
        if ($number != 0) {
            $transId = "number.{$number}";
            if ($isOther && $number < 3) {
                $transId .= '_other';
            }
            $word = $this->translator->trans($transId, [], 'numbers');
            if ($transId == $word) {
                $subNum = substr($number, 1);
                $result .= ' '.$this->toWords($number - $subNum);
                $result .= ' '.$this->toWords($subNum, $isOther);
            } else {
                $result .= $word;
            }
        }

        return $result;
    }
}
