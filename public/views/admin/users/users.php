<div class="card">
    <div class="card-header">
        <h2>{# Users #}</h2>
        <div class="links">
            | <a href="/user/add">{# Add #}</a> |
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>{# Login #}</th>
                    <th>{# Surname #}
                    {# Name #}
                    {# Patronymic #}</th>
                    <th>{# Role #}</th>
                    <th>{# Company #}</th>
                    <th>{# IIN #}</th>
                    <th>{# Status #}</th>
                    <th>{# Certificate #}</th>
                    <th>{# Action #}</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $num => $user): ?>
                <tr>
                    <td><?=$user['login'] ?></td>
                    <td><?=$user['surname'] ?>
                    <?=$user['name'] ?>
                    <?=$user['patronym'] ?></td>
                    <td>{# <?=$user['roleV'] ?> #}</td>
                    <td><?=$user['company'] ?></td>
                    <td><?=$user['iin'] ?></td>
                    <td><?php if($user["active"]){?>{# Active #}<?php }else{ ?>{# Blocked #}<?php } ?></td>
                    <td>{# <?=$user['cert_status'] === false ? 'Recalled' : ($user['cert_status'] == 1 ? 'Active' : '') ?> #}</td>

                    <!-- Action -->
                    <td class="text-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {# Actions #}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<?php if ($user['active']): ?>
                                    <a class="dropdown-item" href="/user/<?=$user['id'] ?>/block">
                                        {# Block #}
                                    </a>
								<?php else: ?>
                                    <a class="dropdown-item" href="/user/<?=$user['id'] ?>/activate">
                                        {# Activate #}
                                    </a>
								<?php endif; ?>
                                <a href="#" class="delete dropdown-item">{# Delete #}</a>
                                <form class="d-none" method="post">
                                    <input type="hidden" name="id" value="<?=$user['id'] ?>">
                                    <input type="hidden" name="_method" value="delete">
                                </form>

                                <a class="dropdown-item" href="/user/<?=$user['id'] ?>/edit">{# Edit #}</a>
                            </div>
                        </div>
<!--
                        |
                        <?php if ($user['active']): ?>
                            <a href="/user/<?=$user['id'] ?>/block">{# Block #}</a>
                        <?php else: ?>
                            <a href="/user/<?=$user['id'] ?>/activate">{# Activate #}</a>
                        <?php endif; ?>
                        |
                        <a href="#" class="delete">{# Delete #}</a>
                        <form class="d-none" method="post">
                            <input type="hidden" name="id" value="<?=$user['id'] ?>">
                            <input type="hidden" name="_method" value="delete">
                        </form>
                        |
                        <a href="/user/<?=$user['id'] ?>/edit">{# Edit #}</a>
                        |
-->
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
