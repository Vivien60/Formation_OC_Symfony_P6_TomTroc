<?php
declare(strict_types=1);

namespace services;

use model\AbstractEntity;

class MediaManager
{
    public ?MediaDirectory $path = null;
    public array $fileInfo = [];
    private string $filePath = '';

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
        $this->path = new MediaDirectory($linkedTo);
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
        $this->getFilepath();
        if(!$this->storeFile()) {
            throw new \Exception("Echec de sauvegarde du fichier");
        }
        return $this;
    }

    public function getFilepath() : string
    {
        if(empty($this->filePath)) {
            $this->filePath = $this->path . '/' . $this->fileInfo['name'];
        }
        return $this->filePath;
    }

    public function filename() : string
    {
        return basename($this->getFilepath());
    }

    private function storeFile() : bool
    {
        return move_uploaded_file($this->fileInfo['tmp_name'], $this->path . '/' . $this->fileInfo['name']);
    }

    private function performSecurityCheck()
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

}