<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a href="/profile" class="nav-link">{# Profile #}</a>
    </li>
    <li class="nav-item">
        <a href="/users" class="nav-link active">{# Users management #}</a>
    </li>
    <li class="nav-item">
        <a href="/departments" class="nav-link">{# Groups management #}</a>
    </li>
</ul>
<div class="card">
    <div class="card-header">
        <h1>{# Users of organization "$1" { <?=$company['name'] ?> } #}</h1>
        |
        <a class="" href="/user/add">
            {# Add #}
        </a>
        |
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>{# Login #}</th>
                    <th>{# Name #}</th>
                    <th>{# Department #}</th>
                    <th>{# Action #}</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?=$user['login'] ?></td>
                            <td class="user-ajax" data-user="<?=$user['id'] ?>">
                                <?=$user['surname'] ?>
                                <?=$user['name'] ?>
                                <?=$user['patronym'] ?>
                            </td>
                            <td class="department-ajax" data-department="<?=$user['department'] ?>">
                                <?=$user['departmentName'] ?>
                            </td>
                            <td>
                                |
                                <a href="#" class="delete">
                                    {# Delete #}
                                </a>
                                |
                                <form class="d-none" action="/users" method="post">
                                    <input type="hidden" name="id" value="<?=$user['id'] ?>">
                                    <input type="hidden" name="_method" value="delete">
                                </form>
                                <a class="" href="/user/<?=$user['id'] ?>/edit">
                                    {# Edit #}
                                </a>
                                |
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
                    for(let param in data.user)
                        cloneUserData.innerHTML = cloneUserData.innerHTML.replace('{$ '+param+' $}',data.user[param] || '');
                });

            modal.querySelector('.modal-body').append(cloneUserData);
        };

    });
</script>
