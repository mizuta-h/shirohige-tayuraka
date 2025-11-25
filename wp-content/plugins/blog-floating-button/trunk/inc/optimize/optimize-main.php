<?php

$filter_arg = array(
	'action' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
	'optimize_step' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
	'optimize_id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
);
$postData = filter_input_array(INPUT_POST,$filter_arg);

$filter_arg = array(
	'action' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
	'optimize_id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
);
$getData = filter_input_array(INPUT_GET,$filter_arg);

//戻る処理の場合
if( isset($postData['action']) && $postData['action'] == 'back' ){
	if( isset($postData['optimize_step']) ){
		if( !$this->is_validate($postData['optimize_step'],'w') ){
			return false;
		}
		$this->return_optStep($postData['optimize_step'],'prev');
	}
}

//保存データを読み込み
if( isset($postData['optimize_id']) ){
	//新しいテストを開始

	if( !$this->is_validate($postData['optimize_id'],'opt_id') ){
		return false;
	}
	$this->optimize_id = $postData['optimize_id'];

	$opt_data = $this->read_optimize($this->optimize_id);

}elseif( isset($getData['optimize_id']) && $this->optimize_step == 'opt_list' ){
	//編集時

	if( !$this->is_validate($getData['optimize_id'],'opt_id') ){
		return false;
	}
	$this->optimize_id = $getData['optimize_id'];

	if( isset($getData['action']) ){

		if( !$this->is_validate($getData['action'],'w') ){
			return false;
		}

		if( $getData['action'] == 'edit' ){
			$opt_data = $this->read_optimize($this->optimize_id);
			$this->return_optStep('opt_list','next');  //初期設定画面へ
		}elseif( $getData['action'] == 'delete' ){
			$this->delete_data($this->optimize_id); //データ削除
		}elseif( $getData['action'] == 'opt_start' ){
			$this->update_optimizemeta($this->optimize_id,'status','1'); //テストスタート
		}elseif( $getData['action'] == 'opt_stop' ){
			$this->update_optimizemeta($this->optimize_id,'status','0'); //テスト停止
		}elseif( $getData['action'] == 'opt_finish' ){
			$this->update_optimizemeta($this->optimize_id,'status','2'); //テスト停止
		}elseif( $getData['action'] == 'copy' ){
			$this->copy_optimizemeta($this->optimize_id); //コピー
		}
	}
}

//POSTデータ取得
if( !empty($this->optimize_step) ){
	foreach( $this->{'optimizeItems_'.$this->optimizeStep[$this->optimize_step]} as $item => $validates ){

		$postData = filter_input(INPUT_POST,$item);

		if(isset($postData)){
			if( $this->check_validation($postData,$validates) ){
				$this->{$item} = $postData;
			}else{
				$this->{$item} = '';
			}
		}else{
			if( !isset($this->{$item}) ){
				//保存データがなければ
				$this->{$item} = '';
			}
		}
	}
	if( $this->optimize_step == 'opt_mainBtn' || $this->optimize_step == 'opt_subBtn' ){
		foreach( $this->btnItems as $item => $validates ){

			$postData = filter_input(INPUT_POST,$item);
			
			if( $this->optimize_step == 'opt_mainBtn' && !isset($btnType) ){
				$btnType = $this->mainBtnDesign;
			}elseif( $this->optimize_step == 'opt_subBtn' && !isset($btnType) ){
				$btnType = $this->subBtnDesign;
			}
			
			if(isset($postData)){
				if( $this->check_validation($postData,$validates) ){
					$this->{'bfb_'.$btnType.'_'.$item.'_'.$this->optimize_step} = $postData;
				}
			}else{
				if( !isset($this->{'bfb_'.$btnType.'_'.$item.'_'.$this->device.'_'.$this->optimize_step}) ){
					//保存データがなければ
					$this->{'bfb_'.$btnType.'_'.$item.'_'.$this->device.'_'.$this->optimize_step} = '';
				}
			}
		}
	}
}

?>

<script type="text/javascript">
jQuery(function($){
	$('a.alert').on('click',function(){
		if( $(this).attr('action') == 'delete' ){
			return alert_msg("設定データと最適化結果を削除します。一度削除したデータは復元できません。データを削除してよろしいですか？");
		}
		if( $(this).attr('action') == 'opt_start' ){
			return alert_msg("テストを開始していいですか？");
		}
		if( $(this).attr('action') == 'opt_stop' ){
			return alert_msg("テストを停止していいですか？停止後に再開することもできます。");
		}
		if( $(this).attr('action') == 'opt_finish' ){
			return alert_msg("テストを完了していいですか？完了後はテスト再開できません。");
		}
	});

});
function alert_msg(msg,ok_msg=null,cancel_msg=null){

	if(!window.confirm(msg)){
		if( cancel_msg ){ window.alert(cancel_msg); }
	  	return false;
	}else{
		if( ok_msg ){ window.alert(ok_msg); }
	}

	return true;

}
</script>

<?php if( $this->optimize_step == 'opt_list' ): ?>

	<?php include_once( dirname(__FILE__) . '/optimize-list.php' ); ?>

<?php elseif( $this->optimize_step == 'opt_init' ): ?>

	<?php include_once( dirname(__FILE__) . '/optimize-init.php' ); ?>
	<?php $this->date_picker_script(); ?>

<?php elseif( $this->optimize_step == 'opt_mainBtn' ): ?>

	<?php include( dirname(__FILE__) . '/optimize-mainBtn.php' ); ?>

<?php elseif( $this->optimize_step == 'opt_subBtn' ): ?>

	<?php include( dirname(__FILE__) . '/optimize-subBtn.php' ); ?>

<?php else: ?>

<?php

if( isset($this->optimize_step) ){
	include( dirname(__FILE__) . '/optimize-create.php' );
}

?>

<?php endif; ?>