<form action="<?php echo uri('/neatline-maps/servers/edit/' . $id); ?>" method="post" class="button-form fedora-inline-form-servers">
  <input type="submit" value="Edit" class="fedora-inline-button bagit-create-bag">
</form>

<form action="<?php echo uri('/neatline-maps/servers/delete/' . $id); ?>" method="post" class="button-form fedora-inline-form-servers">
  <input type="hidden" name="confirm" value="false" />
  <input type="submit" value="Delete" class="fedora-inline-button fedora-delete">
</form>
