<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use QL\MCP\Common\Time\Clock;
use QL\MCP\Common\Time\TimePoint;

trait TimeCreatedTrait
{
    /**
     * @var Clock
     */
    private static $createdTimeGenerator;

    /**
     * @return TimePoint
     */
    public function generateCreatedTime()
    {
        if (!self::$createdTimeGenerator) {
            self::$createdTimeGenerator = new Clock;
        }

        return self::$createdTimeGenerator->read();
    }
}
