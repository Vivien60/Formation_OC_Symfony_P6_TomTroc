<?php
declare(strict_types=1);

namespace services;

use model\AbstractEntity;

class MediaPath
{
    public string $dir;
    public string $name;

    /**
     * @param AbstractEntity $linkedTo
     * @throws \Exception
     */
    public function __construct(public AbstractEntity $linkedTo)
    {
        $this->getPathParts();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPathParts() : string
    {
        if(!empty($this->dir))
            return $this->dir;

        $type = get_class($this->linkedTo);
        switch($type) {
            case \model\BookCopy::class:
                $this->dir =  __DIR__ . "/../assets/img/books/";
                $this->name = 'book'.sprintf('%03d', $this->linkedTo->id);
                break;
            case \model\User::class:
                $this->dir =  __DIR__ . "/../assets/img/avatars/";
                $this->name = 'user'.sprintf('%03d', $this->linkedTo->id);
                break;
            default:
                throw new \Exception("No path found for entity of type $type.");
        }
        return $this->dir;
    }
}