<div class="card">
    <div class="card-header">
        <h1>{# Invoices #}</h1>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th class="text-center">{# invoice # #}</th>
                        <th class="text-center">{# Date #}</th>
                        <!--th class="text-center">Срок оплаты</th-->
                        <th class="text-center">{# Amount #}</th>
                        <th class="text-center">{# Status #}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transactions as $invoice):?>
                    <tr>
                        <td><a href="/profile/invoice/<?=$invoice['id']?>" class="text-bold"><?=$invoice['id']?></a></td>
                        <td><?=date("d.m.Y",$invoice['create_date'])?></td>
                        <!--td>2019-05-13</td-->
                        <td class="text-bold"><?=$invoice['sum']?> ₸</td>
                        <td>
                            <?php if($invoice['status']==0):?>
                            <span class="badge badge-warning">{# Not paid #}</span>
                            <?php endif;?>
                            <?php if($invoice['status']==1):?>
                            <span class="badge badge-success">{# Paid #}</span>
                            <?php endif;?>
                            <?php if($invoice['status']==2):?>
                            <span class="badge badge-danger">{# Rejected #}</span>
                            <?php endif;?>
                            <br>
                            <a href="/profile/invoice/<?=$invoice['id']?>">{# View invoice #}</a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog" id="user-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="spinner" style="display: none;">
                    <div class="spinner-border"></div>
                </div>
                <div class="user-data"></div>
            </div>
        </div>
    </div>
</div>
<div class="templates d-none">
    <div class="user-data">
        <div class="row">
            <span class="col-3">{# Login #}:</span>
            <span class="col">{$ login $}</span>
        </div>
        <div class="row">
            <span class="col-3">{# Name #}:</span>
            <span class="col">{$ surname $} {$ name $} {$ patronym $}</span>
        </div>
        <div class="row">
            <span class="col-3">{# IIN #}:</span>
            <span class="col">{$ iin $}</span>
        </div>
        <div class="row">
            <span class="col-3">{# Position #}:</span>
            <span class="col">{$ position $}</span>
        </div>
        <div class="row">
            <span class="col-3">{# Mobile phone #}:</span>
            <span class="col">{$ mobile $}</span>
        </div>
        <div class="row">
            <span class="col-3">{# Work phone #}:</span>
            <span class="col">{$ work_phone $}</span>
        </div>
    </div>
</div>
<script>
    $('.department-ajax').tooltip({
        html:true,
        title: getDepartment
    });

    function getDepartment() {
        let department = this.dataset.department || null;
        let tooltipText = '';
        if (department) {
            let url = '/ajax/department/' + department;
            //Async = false, because else tooltip thinks that title is empty and doesn't show self
            $.ajax(url, {async: false})
                .done(function (data) {
                    tooltipText = data;
                });
        }
        return tooltipText;
    }

    document.querySelectorAll('.user-ajax').forEach((el) => {
        el.onclick = (ev) => {
            let target = ev.target.closest('.user-ajax');
            let modal = document.getElementById('user-modal');
            let cloneUserData = document.querySelector('.templates .user-data').cloneNode(true);
            modal.querySelector('.user-data').remove();
            $('#user-modal').modal('show');
            modal.querySelector('.spinner').style.display = 'block';

            $.ajax('/ajax/user/' + target.dataset.user, {dataType:'json'})
                .done((data) => {
                    modal.querySelector('.spinner').style.display = 'none';
                    for(param in data.user)
                        cloneUserData.innerHTML = cloneUserData.innerHTML.replace('{$ '+param+' $}',data.user[param] || '');
                });

            modal.querySelector('.modal-body').append(cloneUserData);
        };

    });
</script>
