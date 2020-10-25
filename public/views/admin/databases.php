<div class="card">
    <div class="card-header">
        <h2>{# Backups #}</h2>
        <div class="links">
            | <a href="/database/create">{# Create #}</a>
            | <div class="d-inline">
                <a href="#" data-toggle="dropdown">{# Upload #}</a>
                <form class="dropdown-menu" action="/database/upload" method="post" enctype="multipart/form-data">
                    <label for="backup-file">{# Choose file #}</label>
                    <input id="backup-file" type="file" name="backup" class="form-control">
                    <button type="submit" class="btn btn-primary">{# Upload #}</button>
                </form>
            </div>
            | <div class="d-inline">
                <a href="#" data-toggle="dropdown">{# Upload and restore #}</a>
                <form class="dropdown-menu" action="/database/upload" method="post" enctype="multipart/form-data">
                    <label for="backup-file">{# Choose file #}</label>
                    <input id="backup-file" type="file" name="backup" class="form-control">
                    <button type="submit" class="btn btn-primary">{# Restore #}</button>
                </form>
            </div> |
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{# Name #}</th>
                    <th>{# Date #}</th>
                    <th>{# Time #}</th>
                    <th>{# Action #}</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($backups as $num => $backup): ?>
                <tr>
                    <td><?=$num+1 ?></td>
                    <td><?=$backup['name'] ?></td>
                    <td><?=date("d/m/Y",$backup['time']) ?></td>
                    <td><?=date("H:i:s",$backup['time']) ?></td>
                    <td>
                        | <a href="#" class="delete">{# Delete #}</a>
                        <form class="d-none" action="/database/<?=$backup['name']?>/delete" method="get">

                        </form>
                        | <a href="/database/<?=$backup['name'] ?>/download">{# Download #}</a>
                        | <a href="/database/<?=$backup['name'] ?>/restore">{# Restore #}</a> |
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
