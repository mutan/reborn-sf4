<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace App\Entity\Traits;

use App\Exceptions\EmptyManyToManyRelationException;

/*
 * !!! Don't forget to add into entity class notation: @ORM\HasLifecycleCallbacks
 */
trait CheckEmptyCardsTrait
{
    /**
     * @ORM\PreRemove()
     * @throws EmptyManyToManyRelationException
     */
    public function preRemove() {
        if (!$this->cards->isEmpty()) {
            throw new EmptyManyToManyRelationException();
        }
    }
}