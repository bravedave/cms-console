<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

  // sys::dump( $this->data->creditors, null, false);

?>
<div class="table-responsive">
  <table class="table table-sm" id="<?= $tblID = strings::rand() ?>">
    <thead class="small">
      <tr>
        <td rowspan="2" class="align-bottom">#</td>
        <td rowspan="2" class="align-bottom">Reference</td>
        <td rowspan="2" class="align-bottom">FileAs</td>
        <td rowspan="2" class="align-bottom">Name</td>
        <td rowspan="2" class="align-bottom">Mobile</td>
        <td rowspan="2" class="align-bottom">Email</td>
        <td rowspan="2" class="align-bottom">ABN</td>
        <td rowspan="2" class="align-bottom">BPay</td>
        <td class="text-center" colspan="2">Dissection</td>

      </tr>
        <td>Description</td>
        <td>Refer</td>

      <tr>

      </tr>

    </thead>

    <tbody>
    <?php while ( $dto = $this->data->creditors->dto()) {
      $name = [];
      if ( $dto->First) $a[] = $dto->First;
      if ( $dto->Middle) $a[] = $dto->Middle;
      if ( $dto->Last) $a[] = $dto->Last;

      ?>

      <tr>
        <td line-number></td>
        <td><?= $dto->Reference ?></td>
        <td><?= $dto->FileAs ?></td>
        <td><?= $name ? implode( ' ', $name) : $dto->Salutation ?></td>
        <td><?= $dto->Mobile ?></td>
        <td><?= $dto->Email ?></td>
        <td><?= $dto->ABN ?></td>
        <td><?= $dto->BPAYBillerCode ?></td>
        <td><?= $dto->Dissection_FileAs ?></td>
        <td><?= $dto->Dissection_Refer ?></td>

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
