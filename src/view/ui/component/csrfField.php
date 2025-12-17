<?php
declare(strict_types=1);

use view\templates\WithForm;

/**
 * @var WithForm $this
 */
return <<<AAA
    <input type="hidden" name="csrf" value="{$this->csrfToken}">
AAA;