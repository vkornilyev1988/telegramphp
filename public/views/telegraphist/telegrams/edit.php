<form class="row" method="post">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h1>{# Edit telegram #}</h1>
            </div>
            <div class="card-body">
                <div class="row text-right">
                    <span class="telegram-no col-12">№ <?=$telegram['number']?></span>
                    <span class="telegram-status col-12">{# <?=$statuses[$telegram['status']] ?> #}</span>
                </div>
                <div class="row custom-control custom-switch">
                    <input class="custom-control-input" id="is-urgent" type="checkbox" name="is-urgent" value="1" disabled <?=$telegram['is_urgent'] ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="is-urgent">{# Urgent #}</label>
                </div>
                <div class="row form-group">
                    <label for="points">{# Points #}</label>
                    <select id="points" class="select2 form-control" name="points[]" multiple="multiple">
                        <?php foreach($points as $point):?>
                            <option value="<?=$point['id']?>" <?=$point['selected'] ? 'selected' : '' ?>><?=$point['name']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <!--<div class="row form-group">
                    <label for="destination">{# Where, whom #}</label>
                    <input class="form-control" type="text" name="destination" value="<?=$telegram['destination'] ?>" id="destination" readonly>
                </div>-->
                <div class="row form-group">
                    <label for="message">{# Message #}</label>
                    <textarea id="message" name="message" class="form-control" readonly><?=$telegram['message'] ?></textarea>
                </div>
                <div class="row form-group">
                    <label class="col-2">{# EDS #}:</label>
                    <span class="eds-info col"></span>
                </div>
                <div class="row form-group">
                    <label class="col-2">{# Actor #}:</label>
                    <div class="col-4">
                        <span class="actor-name">
                            <?=$telegram['surname'] ?>
                            <?=$telegram['name'] ?>
                            <?=$telegram['patronym'] ?>
                        </span>
                    </div>
                    <div class="col-6">
                        <label>{# Phone #}:</label>
                        <span class="actor-phone"><?=$telegram['mobile'] ?>, <?=$telegram['work_phone'] ?></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label>{# Words count #}:</label>
                        <span class="word-count"><?=$telegram['wordcount'] ?></span>
                    </div>
                    <div class="col-6">
                        <label>{# Total cost #}:</label>
                        <span class="cost-value"><?=$telegram['cost'] ?></span>
                        <span class="cost-sign">₸</span>
                    </div>
                </div>

            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary copy-telegram" type="button">{# Copy #}</button>
                <button class="btn btn-primary" formaction="/telegram/<?=$telegram['id'] ?>/save">{# Save #}</button>
                <button class="btn btn-primary" formaction="/telegram/<?=$telegram['id'] ?>/send">{# Send #}</button>
                <a class="btn btn-secondary" href="/telegrams">{# Cancel #}</a>
            </div>
        </div>
    </div>
</form>
<script>
    let telegramCost = <?=$telegram['cost'] ?>;
    let points = document.querySelector('#points');

    document.querySelector('#points').onchange = () => {
        document.querySelector('.cost-value').innerHTML = (telegramCost * points.selectedOptions.length).toString();
    };

    document.querySelector('.copy-telegram').onclick = (ev) => {
        let copyArea = document.createElement('textarea');
        copyArea.id = '#copyArea';
        //Forming how the telegram looks like
        let pointsHtml = '';
        for (let i = 0; i < points.selectedOptions.length; i++)
            pointsHtml += points.selectedOptions[i].innerHTML + "\n";
        copyArea.innerHTML = pointsHtml + "\n" + document.querySelector('#message').value;
        let parent = ev.target.parentElement;
        parent.appendChild(copyArea);
        copyArea.select();
        document.execCommand('copy');
        copyArea.remove();
    }
</script>
