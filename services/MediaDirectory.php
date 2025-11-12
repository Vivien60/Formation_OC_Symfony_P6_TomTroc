<?php
declare(strict_types=1);

namespace services;

use model\AbstractEntity;

class MediaDirectory implements \Stringable
{
    public string $path;

    /**
     * @param AbstractEntity $linkedTo
     * @throws \Exception
     */
    public function __construct(public AbstractEntity $linkedTo)
    {
        $this->getDirectory();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getDirectory() : string
    {
        if(!empty($this->path))
            return $this->path;

        $type = get_class($this->linkedTo);
        switch($type) {
            case \model\BookCopy::class:
                $this->path =  __DIR__ . "/../assets/img/books/";
                break;
            case \model\User::class:
                $this->path =  __DIR__ . "/../assets/img/avatars/";
                break;
            default:
                throw new \Exception("No path found for entity of type $type.");
        }
        return $this->path;
    }

    public function __toString(): string
    {
        return $this->path;
    }
}