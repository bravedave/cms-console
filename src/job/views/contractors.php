<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace cms\console;

use strings;  ?>

<div class="table-responsive">
  <table class="table table-sm" id="<?= $tblID = strings::rand() ?>">
    <tbody>
    <?php while ( $dto = $this->data->res->dto()) { ?>
      <tr
        data-id="<?= $dto->id ?>">
        <td line-number></td>
        <td><?= $dto->trading_name ?></td>
        <td><?= $dto->services ?></td>

      </tr>

    <?php } ?>
    </tbody>

  </table>

</div>
<script>
( _ => $(document).ready( () => {
  $('#<?= $tblID ?>')
  .on('update-line-numbers', function( e) {
    $('> tbody > tr:not(.d-none) >td[line-number]', this).each( ( i, e) => {
      $(e).data('line', i+1).html( i+1);
    });
  })
  .trigger('update-line-numbers');

}))( _brayworth_);
</script>