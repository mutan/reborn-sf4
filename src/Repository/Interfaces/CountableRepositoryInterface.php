<?php

namespace App\Repository\Interfaces;

interface CountableRepositoryInterface
{
    public function getCount(): int;
}