<form action="<?php echo uri('/neatline-maps/servers/edit/' . $id); ?>" class="button-form neatline-inline-form-servers">
  <input type="submit" value="Edit" class="neatline-inline-button">
</form>

<form action="<?php echo uri('/neatline-maps/servers/delete/' . $id); ?>" class="button-form neatline-inline-form-servers">
  <input type="hidden" name="confirm" value="false" />
  <input type="submit" value="Delete" class="neatline-inline-button neatline-delete">
</form>
