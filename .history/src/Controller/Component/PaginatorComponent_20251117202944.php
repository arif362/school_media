<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component\PaginatorComponent as CakePaginatorComponent;

/**
 * Proxy component that extends CakePHP's core paginator.
 *
 * This satisfies the application namespace lookup while delegating
 * all functionality to the framework component.
 */
class PaginatorComponent extends CakePaginatorComponent
{
}

