<?php
declare(strict_types=1);

namespace services;

use model\AbstractEntity;

class MediaManager
{
    protected array $fileInfo = [];
    protected ?MediaPath $pathParts = null;
    public string $filePath;
    private string $extension;

    /**
     * Initialize the class with the media filename and the linked entity.
     *
     * @param string $filename The name of the file to be processed.
     * @param AbstractEntity $linkedTo The entity to which the file is linked.
     * @return void
     * @throws \Exception
     */
    public function __construct(public string $filename, public AbstractEntity $linkedTo)
    {
        $this->fileInfo = $_FILES[$filename];
        $this->filePath = $_FILES[$filename]['tmp_name'];
        $this->pathParts = new MediaPath($linkedTo);
        $this->extension = pathinfo($this->fileInfo['name'], PATHINFO_EXTENSION);
    }

    /**
     * Handles the file processing by retrieving its path and attempting to store it.
     *
     * @return static Returns the current instance after successful file handling.
     * @throws \Exception Thrown if the file cannot be stored successfully.
     */
    public function handleFile() : static
    {
        $this->performSecurityCheck();
        if(!$this->storeFile()) {
            throw new \Exception("Echec de sauvegarde du fichier");
        }
        return $this;
    }

    protected function performSecurityCheck()
    {
        $extension = pathinfo($this->fileInfo['name'], PATHINFO_EXTENSION);
        switch($this->fileInfo['type']) {
            case 'image/jpeg':
                $ok = in_array($extension, ['jpg', 'jpeg', 'png']);
                break;
            default:
                $ok = false;
        }
        if(!$ok) {
            throw new \Exception('File has not a valid type');
        }
        return $ok;
    }

    protected function makeFilepath() : void
    {
        $this->filePath = $this->pathParts->dir.DIRECTORY_SEPARATOR.$this->pathParts->name.'.'.$this->extension;
    }

    protected function storeFile() : bool
    {
        $this->makeFilepath();
        return $this->moveToFinalDest();
    }

    /**
     * @return bool
     */
    protected function moveToFinalDest(): bool
    {
        return move_uploaded_file($this->fileInfo['tmp_name'], $this->filePath);
    }

    public function filename() : string
    {
        return basename($this->filePath);
    }
}