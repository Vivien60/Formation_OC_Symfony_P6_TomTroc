<?php
declare(strict_types=1);
namespace model;

use model\enum\BookAvailabilityStatus;

class BookCopy extends AbstractEntity
{

    //TODO Vivien : delete this when AbstractEntity will be refactored
    protected static string $selectSql = "select id, title, author, availability_status, image, description, created_at, user_id from book_copy";
    public ?User $owner = null {
        set {
            $this->owner = $value;
            $this->userId = $value->id;
        }
        get {
            if(empty($this->owner) && $this->userId > 0) {
                $this->owner = User::fromId($this->userId);
            }
            return $this->owner;
        }
    }
    public string $author = '';
    public string $title = '';
    public string $description = '';
    public BookAvailabilityStatus|int $availabilityStatus = -1 {
        set {
            if(is_int($value)) {
                $status = BookAvailabilityStatus::from($value);
            } else {
                $status = $value;
            }
            $this->availabilityStatus = $status->value;
            $this->availabilityStatusLabel = $status->label();
        }
    }
    public string $availabilityStatusLabel = '';
    public string $image = '';
    public int $userId = -1;

    protected function __construct(array $fieldVals)
    {
        parent::__construct($fieldVals);
    }

    public function modify(array $fieldVals) : void
    {
        $this->hydrate($fieldVals);
    }

    protected function hydrate(array $fieldVals) : void
    {
        parent::hydrate($fieldVals);
        //WORKAROUND FOR IMAGE SAVED WITH PATH
        $this->image = basename($this->image)?:'default.png';
    }

    public static function blank() : static
    {
        return new static([
            'author' => '',
            'title' => '',
            'description' => '',
            'image' => '',
            'ownerId' => -1,
        ]);
    }

    public function validate(): bool
    {
        if (empty($this->title) || strlen($this->title) > 255) {
            throw new \Exception('Le titre est obligatoire et ne doit pas dépasser 255 caractères');
        }

        if (empty($this->author) || strlen($this->author) > 255) {
            throw new \Exception('L\'auteur est obligatoire et ne doit pas dépasser 255 caractères');
        }

        if (strlen($this->description) > 1000) {
            throw new \Exception('La description ne doit pas dépasser 1000 caractères');
        }

        return true;
    }
}