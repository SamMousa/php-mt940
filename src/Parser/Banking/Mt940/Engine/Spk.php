<?php
namespace Kingsquare\Parser\Banking\Mt940\Engine;

use Kingsquare\Parser\Banking\Mt940\Engine;

/**
 * @package Kingsquare\Parser\Banking\Mt940\Engine
 * @author Timotheus Pokorra (timotheus.pokorra@solidcharity.com)
 * @license http://opensource.org/licenses/MIT MIT
 *
 * This is for german banks, for example Sparkasse
 *
 */
class Spk extends Engine
{
    /**
     * returns the name of the bank
     * @return string
     */
    protected function parseStatementBank()
    {
        return 'Spk';
    }


    /**
     * Overloaded: Sparkasse uses 60M and 60F
     * @inheritdoc
     */
    protected function parseStatementStartPrice()
    {
        $results = array();
        if (preg_match('/:60[FM]:.*EUR([\d,\.]+)*/', $this->getCurrentStatementData(), $results)
                && !empty($results[1])
        ) {
            return $this->sanitizePrice($results[1]);
        }

        return '';
    }

    /**
     * Overloaded: Sparkasse uses 60M and 60F
     * @inheritdoc
     */
    protected function parseStatementTimestamp()
    {
        $results = array();
        if (preg_match('/:60[FM]:[C|D](\d{6})*/', $this->getCurrentStatementData(), $results)
                && !empty($results[1])
        ) {
            return $this->sanitizeTimestamp($results[1], 'ymd');
        }

        return 0;
    }

    /**
     * Overloaded: Sparkasse uses 62M and 62F
     * @inheritdoc
     */
    protected function parseStatementEndPrice()
    {
        $results = array();
        if (preg_match('/:62[FM]:.*EUR([\d,\.]+)*/', $this->getCurrentStatementData(), $results)
                && !empty($results[1])
        ) {
            return $this->sanitizePrice($results[1]);
        }

        return '';
    }

    /**
     * Overloaded: Sparkasse does not have a header line
     * @inheritdoc
     */
    protected function parseStatementData()
    {
        return preg_split(
                '/(^:20:|^-X{,3}$|\Z)/sm',
                $this->getRawData(),
                -1,
                PREG_SPLIT_NO_EMPTY
        );
    }
}
