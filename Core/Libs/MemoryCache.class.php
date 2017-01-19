<?php
namespace Core\Libs;
interface MemoryCache
{
    public function setCache(string $sql,array $data);
    public function getCache(string $sql);
}