<?php

declare(strict_types=1);

namespace App\Shared\Type;

use Xtompie\CollectionTrait\All;
use Xtompie\CollectionTrait\Any;
use Xtompie\CollectionTrait\Construct;
use Xtompie\CollectionTrait\First;
use Xtompie\CollectionTrait\None;
use Xtompie\CollectionTrait\To;
use Xtompie\CollectionTrait\ToArray;

/**
 * @template T
 */
class TypedCollection
{
    /** @use All<T> */
    use All;
    use Any;
    /** @use Construct<T> */
    use Construct;
    /** @use First<T> */
    use First;
    use None;
    /** @use ToArray<T> */
    use ToArray;
    use To;
}
