<script type="text/javascript">
    <?php if (Session::has('success')) { ?>
        Toast.fire({
                    icon: 'success',
                    title: '{{Session::get('success')}}'
                });
    <?php } else if (Session::has('error')) {  ?>
        Toast.fire({
                    icon: 'error',
                    title: '{{Session::get('error')}}'
                });
    <?php } else if (Session::has('warning')) {  ?>
        Toast.fire({
                    icon: 'warning',
                    title: '{{Session::get('warning')}}',
                });
    <?php } else if (Session::has('info') ) {  ?>
        Toast.fire({
                    icon: 'info',
                    title: '{{Session::get('info')}}'
                });
    <?php } ?>
</script>

