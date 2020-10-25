<?php if ($user['is_head']): ?>
<ul class="nav nav-tabs mb-2">
	<li class="nav-item">
		<a href="/profile" class="nav-link active">{# Profile #}</a>
	</li>
	<li class="nav-item">
		<a href="/users" class="nav-link">{# Users management #}</a>
	</li>
	<li class="nav-item">
		<a href="/departments" class="nav-link">{# Groups management #}</a>
	</li>
</ul>
<?php endif; ?>
<div class="row mb-3 align-items-stretch">
	<div class="col">
		<!-- Me myself -->
		<div class="card h-100">
			<div class="card-header">
				<h4><?=$user['surname'] ?> <?=$user['name'] ?></h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Login #}</span>
						<br>
						<span class="profile-value"><?=$user['login'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Mobile phone #}</span>
						<br>
						<span class="profile-value"><?=$user['mobile'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Work phone #}</span>
						<br>
						<span class="profile-value"><?=$user['work_phone'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# IIN #}</span>
						<br>
						<span class="profile-value"><?=$user['iin'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Position #}</span>
						<br>
						<span class="profile-value"><?=$user['position'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Password #}</span>
						<br>
						<span class="profile-value">
							<a href="/user/change-password" class="">{# Change password #}</a>
						</span>
					</div>
                    <div class="col-6 profile-item">
                        <span class="profile-key h5">{# Telegrams sent #}</span>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <span>{# Last week #}:</span>
                                <span><?=$telegrams['week'] ?></span>
                            </div>
                            <div class="col-12">
                                <span>{# Last month #}:</span>
                                <span><?=$telegrams['month'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 profile-item">
                        <span class="profile-key h5">{# Words count #}</span>
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <span>{# Last week #}:</span>
                                <span><?=$wordsCount['week'] ?></span>
                            </div>
                            <div class="col-12">
                                <span>{# Last month #}:</span>
                                <span><?=$wordsCount['month'] ?></span>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="col">
		<!-- Company -->
		<div class="card">
			<div class="card-header">
				<h4>{# Company #}: <?=$company['name'] ?></h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Company #}</span>
						<br>
						<span class="profile-value"><?=$company['name'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Director #}</span>
						<br>
						<span class="profile-value"><?=$company['dir_surname']?>
							<?=$company['dir_name']?>
							<?=$company['dir_patronym']?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Address #}</span>
						<br>
						<span class="profile-value"><?=$company['address'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# Site #}</span>
						<br>
						<span class="profile-value"><?=$company['site'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# BIN #}</span>
						<br>
						<span class="profile-value"><?=$company['bin'] ?></span>
					</div>
					<div class="col-6 profile-item">
						<span class="profile-key h5">{# IBAN #}</span>
						<br>
						<span class="profile-value"><?=$company['iban'] ?></span>
					</div>
					<?php if ($user['is_head']): ?>
						<div class="col-6 profile-item">
							<span class="profile-key h5">{# Accountant E-mail #}</span>
							<br>
							<span class="profile-value"><?=$company['accountant_email'] ?></span>
						</div>
						<div class="col-6 profile-item">
							<span class="profile-key h5">{# Balance #}</span>
							<br>
							<span class="profile-value"><?=$balance ?> ₸</span>
							<span class="w-25 d-inline-block"></span>
							<a href="/balance/add" class="text-success">{# Recharge #}</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="card">
			<div class="card-header">
				<h3>{# Your invoices #}</h3>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered text-center datatable" id="invoices-table">
						<thead>
						<tr>
							<th class="text-center">{# invoice # #}</th>
							<th class="text-center">{# Date #}</th>
							<th class="text-center">{# Amount #}</th>
							<th class="text-center">{# Status #}</th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<td colspan="4"><a href="/profile/invoices">{# Go to invoices page #}</a></td>
						</tr>
						</tfoot>
						<tbody>
						<?php foreach($transactions as $invoice):?>
							<tr class="link-row" data-href="/profile/invoice/<?=$invoice['id']?>">
								<td><a href="/profile/invoice/<?=$invoice['id']?>" class="text-bold"><?=$invoice['id']?></a></td>
								<td><?=date("d.m.Y",$invoice['create_date'])?></td>
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
								</td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="/public/scripts/telegrams.js"></script>
<script>
    let datatable = new DataTable('.datatable', {layout: {top: ""}, footer: true});
</script>