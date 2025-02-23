<link rel="stylesheet" type="text/css" href="/assets/datatables/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="/assets/datatables/datatables-custom.css" />
<script type="text/javascript" src="/assets/datatables/datatables.min.js"></script>
<div class="card no-bgcolor card-border">
    <div class="card-body">
        <h5 class="card-title"><?= $title ?? '' ?></h5>
        <h6 class="card-subtitle mb-2 text-muted"><?= $subtitle ?? '' ?></h6>
        <div class="row" id="result-messages"></div>
        <div class="row">
            <div class="col-12 table-action-wrapper">
                <?php if(!isset($showEditButton) || (isset($showEditButton) && $showEditButton != false)): ?>
                <button class="btn btn-sm btn-primary table-action-button dt-edit-btn" data-toggle="tooltip" data-placement="bottom" title="<?= lang('Form.formEdit') ?>" disabled><?= lang("Icons.iconEdit") ?></button>
                <?php endif; ?>
                <?php if(!isset($showDeleteButton) || (isset($showDeleteButton) && $showDeleteButton != false)): ?>
                <button class="btn btn-sm btn-danger table-action-button dt-delete-confirm-btn" id="dt-delete-popover" disabled><?= lang("Icons.iconDelete") ?></button>
                <?= view_cell('\App\Controllers\BaseController::showDatatableDeletePopover') ?>
                <?php endif; ?>
            </div>
            <div class="col-12 table-wrapper table-responsive">
                <?= $table ?? '' ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        var table = $('#datatable-table').DataTable({
            dom: 'lBfrtip',
            responsive: true,
            lengthMenu: [[5, 10, 25, 50, 100, -1], [5,10, 25, 50, 100, "All"]],
            pageLength: 10,
            selectable: true,
            columnDefs: [
                { orderable: false, targets: 0, className: 'select-checkbox text-center', width: '20px' },
                { orderable: false, targets: 1, visible: false, searchable: false },
            ],
            order: [[ <?= $tableOrderColumn ?? 0 ?>, "<?= $tableOrderDirection ?? 'desc' ?>" ]],
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
            language: {
                url: '/assets/datatables/lang/hu.json'
            }
        });

        $('#datatable-table').on( 'click', 'tr', function () {
            if($(this).find('th').length === 0){
                $(this).toggleClass('selected');
            }

            dtEditButton(table);
            dtDeleteButton(table);
        });

        $('.dt-edit-btn').on('click', function(){
            let data = table.rows('.selected').data();
            if(data.length > 0){
                window.location = '<?= $editUrl ?>'+data[0][1];
            }
        });

        $(document).on('click', '.dt-delete-btn', function(){
            let data = table.rows('.selected').data();
            if(data.length > 0){
                let ids = [];
                for (let i = 0; i < data.length; i++) {
                    const element = data[i];
                    ids.push(element[1]);
                }
                postTableData('<?= $deleteUrl ?>', ids, '#dt-delete-btn', table);
            }
        });

        $('#dt-delete-popover').popover({
            html : true,
            trigger: 'focus',
            title:function(){
                return $('#popover_title').html();
            },
            content:function(){
                return $('#popover_content_wrapper').html();
            }
       
        });
        $(document).on('click','.dt-close-popover',function(){
            $('#dt-delete-popover').popover('hide');
        });
    });
</script>