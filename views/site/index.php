<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';


?>
<div class="site-index">

    <div class="jumbotron">
        <h1>WebChat</h1>
        <h3>
            Welcome dear <?=$username?>
        </h3>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 bg-info">
                <h2>History</h2>
                <div class="col-lg-12" id="mess_list">
                    <?foreach($messages as $message):?>
                            <div class="other_user bg-primary panel-body">
                                <b><?=$message->user_name?>: </b><?=$message->text?>
                            </div>
                        <div class="clearfix"></div>
                        <br>
                    <?endforeach;?>
                </div>
                <div class="clearfix"></div>
                <br>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <?if(!Yii::$app->user->isGuest):?>
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6 bg-info">

					<?php $form = ActiveForm::begin(); ?>

					<?= $form->field($model, 'user_name')->hiddenInput(['value'=>$username,'id'=>'user_name'])->label(false) ?>
					<?= $form->field($model, 'text')->textarea(['rows' => 6,'id'=>'mess_text'])->label('Write message:') ?>

                    <div class="form-group">
						<?= Html::submitButton('Send', ['class' => 'btn btn-success pull-right']) ?>
                    </div>

					<?php ActiveForm::end(); ?>
                </div>
                <div class="col-lg-3"></div>
            </div>
        <?else:?>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6  bg-info">
		        <p class="panel-body bg-danger">If you want send message - <a href="<?=Url::to(['site/login'])?>">sign in</a> please</p>
            </div>
            <div class="col-lg-3"></div>
        </div>
        <?endif;?>
    </div>
</div>

<?php
$js = <<< JS
    $(function() {
        var conn = new WebSocket('ws://localhost:8080');
        conn.onmessage = function(e) {
            
            $('#response').text('');

            var response = JSON.parse(e.data);
            console.log(response);
            if (response.type && response.type == 'chat') {
                $('#mess_list').append('' +
                 '<div class="other_user bg-primary panel-body">'+
                    '<b>' + response.from + ': </b>' + response.message + 
                '</div><div class="clearfix"></div><br>');
            } else if (response.message) {
                $('#response').text(response.message);
            }
        };
        conn.onopen = function(e) {
            conn.send( JSON.stringify({'action' : 'setName', 'name' : $('#user_name').val()}) );
        };
                
        $('button[type="submit"]').on('click',function (e) {
            e.preventDefault();
            if ($('#mess_text').val()) {
                conn.send( JSON.stringify({'action' : 'chat', 'message' : $('#mess_text').val()}) );
            } else {
                alert('Enter the message')
            }
        });
    })
JS;

$this->registerJs( $js, $position = yii\web\View::POS_READY, $key = null );
?>
