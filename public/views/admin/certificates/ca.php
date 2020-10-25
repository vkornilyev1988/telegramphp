<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" href="/certificates">{# Certificate authority #}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/certificates/clients">{# Users certificates #}</a>
    </li>
</ul>
<div class="card">
    <div class="card-header">
        <h1>{# Certificate authority #}</h1>
        |
        <a href="/certificate/add">{# Add #}</a>
        |
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>{# Name #}</th>
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
                    <td><?=$certificate['name'] ?></td>
                    <td><?=$certificate['start_date'] ?></td>
                    <td><?=$certificate['end_date'] ?></td>
                    <td>{# <?=$certificate['status'] ? 'Active' : 'Recalled' ?> #}</td>
                    <td>
                        |
                        <a href="#" class="delete">{# Recall #}</a>
                        <form class="d-none" action="/certificate/<?=$certificate['id'] ?>/recall" method="post">
                        </form>
                        |
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>