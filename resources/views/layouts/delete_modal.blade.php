<div class="container">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ $ApplicationSetting->item_name }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="my-0 font-weight-bold">{{ __('entire.are you sure you want to delete this data') }} ???</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('entire.close') }}</button>
                    <form class="btn-ok" action="" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('entire.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#myModal').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('action', $(e.relatedTarget).data('href'));
        });
    });
</script>
