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
use Psr\Container\ContainerExceptionInterface;

/**
 * PSR-11 typed generic container exception, thrown by the bridge container when
 * an entry cannot be resolved for a reason other than being absent.
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{
}
