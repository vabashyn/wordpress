<div class="wrap" id="of_container">
	<div id="of-popup-save" class="of-save-popup">
		<div class="of-save-save">Дополнительные настройки обновлены!</div>
	</div>

	<div id="of-popup-reset" class="of-save-popup">
		<div class="of-save-reset">Настройки сброшены!</div>
	</div>

	<div id="of-popup-fail" class="of-save-popup">
		<div class="of-save-fail">Ошибка!</div>
	</div>

	<span style="display: none;" id="hooks"><?php echo json_encode(sap_get_header_classes_array()); ?></span>
	<input type="hidden" id="reset" value="<?php if(isset($_REQUEST['reset'])) echo $_REQUEST['reset']; ?>" />
	<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('sap_ajax_nonce'); ?>" />

	<form id="of_form" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ) ?>" enctype="multipart/form-data" >
		<div id="header">
			<div class="logo">
				<h2>Дополнительные настройки темы (<?php echo SAP_THEMENAME; ?>)</h2>
				<span><?php echo ('v'. SAP_VERSION); ?></span>
			</div>
			<div id="js-warning">Внимание - эта страница не функционирует без поддержки JavaSctipt!</div>
			<div class="icon-option"></div>
			<div class="clear"></div>
    	</div>
		<div id="info_bar">
			<a>
				<div id="expand_options" class="expand">Expand</div>
			</a>
			<img style="display:none" src="<?php echo SAP_URI; ?>static/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
			<button id="of_save" type="button" class="button-primary">
				<?php echo 'Сохранить'; ?>
			</button>
		</div><!--.info_bar-->
		<div id="main">
			<div id="of-nav">
				<ul>
				  <?php echo $options_machine->Menu ?>
				</ul>
			</div>
			<div id="content">
		  		<?php echo $options_machine->Inputs /* Settings */ ?>
		  	</div>
			<div class="clear"></div>
		</div>
		<div class="save_bar">
			<img style="display:none" src="<?php echo SAP_URI; ?>static/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
			<button id ="of_save" type="button" class="button-primary"><?php echo 'Сохранить'; ?></button>
			<button id ="of_reset" type="button" class="button submit-button reset-button" ><?php echo 'Сбросить настройки'; ?></button>
			<img style="display:none" src="<?php echo SAP_URI; ?>static/images/loading-bottom.gif" class="ajax-reset-loading-img ajax-loading-img-bottom" alt="Working..." />
		</div><!--.save_bar-->
	</form>
	<div style="clear:both;"></div>
</div><!--wrap-->
