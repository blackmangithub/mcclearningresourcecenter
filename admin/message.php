<script>
<?php 
if(isset($_SESSION['message_success']))
{
  ?>
alertify.set('notifier', 'position', 'top-center');
alertify.success('<?= $_SESSION['message_success']; ?>');

// alertify.notify('', 'custom', 3, function() {
//      console.log('dismissed');
// });
<?php
unset($_SESSION['message_success']);
}


if(isset($_SESSION['message_error']))
{
  ?>
alertify.set('notifier', 'position', 'top-center');
alertify.error('<?= $_SESSION['message_error']; ?>');

// alertify.notify('', 'custom', 3, function() {
//      console.log('dismissed');
// });
<?php
unset($_SESSION['message_error']);
}
?>
</script>