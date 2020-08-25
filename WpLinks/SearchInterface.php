<?php

namespace WPLinks;

interface SearchInterface
{
    /**
     * Поиск ссылок в контенте
     */
    public function findLinks();

    /**
     * Поиск ссылок в метаполях
     */
    public function findLinksInMeta(int $id);
}