<?php
use services\Utils;
/**
 * @var \view\templates\ReadProfile $this
 */
?>
<div>
    Look my profile page !
</div>
<p><?= $this->user?->username ?></p>
<p><?= $dateCrea ?></p>
<table class="libraryTable">
<?php
if(!empty($this->library))
    foreach ($this->library as $book) :
    ?>
        <tr class="libraryTable__book">
            <td class="libraryTable__bookField"><img class="libraryTable__img" alt="image de <?= $book->title ?>" src="img/<?= $book->image?:"assets/books/default.png" ?>" /></td>
            <td class="libraryTable__bookField"><?= $book->title ?></td>
            <td class="libraryTable__bookField" ><?= $book->auteur ?></td>
            <td class="libraryTable__bookField" ><?= $book->description ?></td>
            <td class="libraryTable__bookField" ><?= $book->availabilityLibelle ?></td>
        </tr>
    <?php
    endforeach;
?>
</table>