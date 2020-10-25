<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="/certificates">{# Certificate authority #}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="/certificates/clients">{# Users certificates #}</a>
    </li>
</ul>
<div class="card">
    <div class="card-header">
        <h1>{# Users certificates #}</h1>
        |
        <a href="/certificates/client/add" data-toggle="dropdown">{# Add #}</a>
        <form class="dropdown-menu" action="/certificates/client/add" method="post">
            <label for="new-client">{# User #}</label>
            <select id="new-client" class="form-control" name="user">
                <?php foreach ($users as $user): ?>
                    <option value="<?=$user['id'] ?>" <?=$user['iin'] ? '' : 'disabled' ?>><?=$user['surname'] ?> <?=$user['name'] ?> (<?=$user['company'] ?>)</option>
                <?php endforeach; ?>
            </select>
            <label for="new-client-days">{# Days #}</label>
            <input class="form-control" type="number" id="new-client-days" name="days" value="365">
            <button class="btn btn-primary">{# Add #}</button>
        </form>
        |
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>{# Name #}</th>
                <th>{# Company #}</th>
                <th>{# Start date #}</th>
                <th>{# End date #}</th>
                <th>{# Status #}</th>
                <th>{# Actions #}</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($certificates as $key => $certificate): ?>
                <tr>
                    <td><?=$key +1 ?></td>
                    <td>
                        <?=$certificate['name'] ?>
                        <?=$certificate['surname'] ?>
                    </td>
                    <td><?=$certificate['company'] ?></td>
                    <td><?=$certificate['start_date'] ?></td>
                    <td><?=$certificate['end_date'] ?></td>
                    <td>{# <?=$certificate['status'] ? 'Active' : 'Recalled' ?> #}</td>
                    <td>
                        |
                        <a href="#" class="delete">{# Recall #}</a>
                        <form class="d-none" action="/certificates/client/<?=$certificate['id'] ?>/recall" method="post">
                        </form>
                        |
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
