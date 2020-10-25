<div class="card">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="/" class="nav-link active">{# Telegrams #}</a>
        </li>
        <li class="nav-item">
            <a href="/telegram/add" class="nav-link">{# Create #}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="/reports">{# Reports #}</a>
        </li>

        <li class="nav-item ml-auto">
            <li class="nav-item">
                <button class="btn nav-link" formaction="/telegrams/delete" form="delete-form">{# Delete #}</button>
            </li>
        </li>
    </ul>

    <form method="post" id="delete-form">
        <table class="table w-100 telegram-table">
            <thead>
            <tr>
                <th><input class="all-ids-check" type="checkbox" ></th>
                <th>#</th>
                <th>{# Whom #}</th>
                <th></th>
                <th>{# Sign date #}</th>
                <!--<th>{# Number #}</th>-->
                <th>{# Register date #}</th>
                <th>{# Send date #}</th>

                <!--<th>{# Addresses number #}</th>-->
                <th>{# Sum #}</th>
                <th>{# Status #}</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($telegrams as $key => $telegram): ?>
                    <tr class="<?=$telegram['is_urgent'] ? 'urgent' : '' ?> telegram-row link-row" data-href="/telegram/<?=$telegram['id'] ?>">
                        <td class="id-check-td"><input class="id-check" type="checkbox" name="ids[]" value="<?=$telegram['id'] ?>"></td>
                        <!--<td><?=$key+1 ?></td>-->
                        <td><?=$telegram['id'] ?></td>
                        <td><?=$telegram['points'] ?></td>
                        <td><?=$telegram['destinations'] ?: 1 ?></td>
                        <td><?=explode('.',$telegram['sign_date'])[0] ?></td>
                        <td><?=explode('.',$telegram['register_date'])[0] ?></td>
                        <td><?=explode('.',$telegram['send_date'])[0] ?></td>
                        <td><?=$telegram['cost'] * ($telegram['destinations'] ?: 1) ?> ₸</td>
                        <td>{# <?=$statuses[$telegram['status']] ?> #}</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</div>
<script src="/public/scripts/telegrams.js"></script>
<script>
    let dataTable = new DataTable('.telegram-table', {

    });
    /*dataTable.on('datatable.init', () => {
        let searchableColumns = [2,4,9];
        let selectsCount = dataTable.labels.length;

        let selectsDom = [];
        let selectsOptions = [];

        //Gen selects
        for (let k in searchableColumns) {
            let i = searchableColumns[k];
        //for (let i = 0; i < selectsCount; i++) {
            let select = document.createElement('select');
            select.onchange = (ev) => {
                let val = ev.target.value;
                dataTable.search(val ? val : '', true, false);
            };
            selectsDom[i] = select;
            selectsOptions[i] = new Set();
        }
        console.log(selectsOptions);

        //Gen options for selects
        dataTable.data.forEach((row) => {
            row.querySelectorAll('td').forEach((col,num) => {
                if (selectsOptions[num])
                    selectsOptions[num].add(col.innerHTML);
            });
        });

        let selectsTr = document.createElement('tr');
        dataTable.head.insertAdjacentElement('afterbegin',selectsTr);
        for (let k in searchableColumns) {
            let i = searchableColumns[k];
        //for (let i = 0; i < selectsCount; i++) {
            let selectTd = document.createElement('td');
            selectsTr.appendChild(selectTd);
            selectTd.appendChild(selectsDom[i]);

            selectsOptions[i].forEach((option) => {
                let optionDom = document.createElement('option');
                optionDom.appendChild(document.createTextNode(option));
                optionDom.value = option;
                selectsDom[i].appendChild(optionDom);
            });
        }
    });*/
    /*$('.telegram-row').on('click',() => {
        console.log(this);
        window.location = this.dataset.href;
    });*/
</script>
