<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Bridge\Psr11\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * PSR-11 typed "no entry found" exception, thrown by the bridge container when
 * an entry is not present.
 */
class NotFound extends Exception implements NotFoundExceptionInterface
{
}
