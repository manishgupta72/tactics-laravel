<script>
    $(function() {
        $('#ell-table_filter input').unbind();

        $('#ell-table_filter input').on('keyup', function(e) {
            if (e.keyCode == 13) { // If the Enter key is pressed
                dataTable.search(this.value).draw();
            }
        });
    });
</script>