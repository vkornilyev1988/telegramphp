<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a href="/profile" class="nav-link">{# Profile #}</a>
    </li>
    <li class="nav-item">
        <a href="/users" class="nav-link">{# Users management #}</a>
    </li>
    <li class="nav-item">
        <a href="/departments" class="nav-link active">{# Groups management #}</a>
    </li>
</ul>
<div class="card">
    <div class="card-header">
        <h1>{# Departments of organization "$1" { <?=$company['name'] ?> } #}</h1>
        |
        <a class="" href="/departments/add">
            {# Add #}
        </a>
        <?php if ($me['is_head']): ?>
        |
        <a class="" href="/departments/balance/move">
            {# Move balance #}
        </a>
        <?php endif; ?>
        |
    </div>
    <div class="card-body">
        <div class="company">
            <div class="departments row">
                <?php foreach ($departments as $id=>$department): ?>
                    <div class="col-6">
                        <div class="department card">
                            <div class="department-header card-header">
                                <div class="department-name">
                                    <?=$department['name'] ?>
                                </div>
                                <div class="actions">
                                    |
                                    <a href="#" class="delete">
                                        {# Delete #}
                                    </a>
                                    <form class="d-none" action="/departments" method="post">
                                        <input type="hidden" name="id" value="<?=$id ?>">
                                        <input type="hidden" name="_method" value="delete">
                                    </form>
                                    |
                                    <a href="/departments/<?=$id ?>/edit">
                                        {# Edit #}
                                    </a>
                                    |
                                </div>
                            </div>
                            <div class="department-body card-body">
                                <?php foreach ($department['users'] as $user): ?>
                                    <div class="user">
                                        <div class="user-name">
                                            <?=$user['surname'] ?>
                                            <?=$user['name'] ?>
                                        </div>
                                        <!--<div class="actions">
                                            <a href="#" class="delete">
                                                {# Delete #}
                                            </a>
                                            <form class="d-none" action="/users" method="post">
                                                <input type="hidden" name="id" value="<?=$user['id'] ?>">
                                                <input type="hidden" name="_method" value="delete">
                                            </form>
                                            <a href="/users/<?=$user['id'] ?>/edit">
                                                {# Edit #}
                                            </a>
                                        </div>-->
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php foreach ($users as $user): ?>
                <div class="user">
                    <div class="user-name">
                        <?=$user['surname'] ?>
                        <?=$user['name'] ?>
                    </div>
                    <!--<div class="actions">
                        <a href="#" class="delete">
                            {# Delete #}
                        </a>
                        <form class="d-none" action="/users" method="post">
                            <input type="hidden" name="id" value="<?=$user['id'] ?>">
                            <input type="hidden" name="_method" value="delete">
                        </form>
                        <a href="/users/<?=$user['id'] ?>/edit">
                            {# Edit #}
                        </a>
                    </div>-->
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
