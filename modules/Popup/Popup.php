<?php
	class Popup extends Module {
		public $name;
		public $data;
		public function __construct() {
			$this->name = get_class($this);
			$this->data = [
				"title"		=> [],
				"text"		=> [],
				"buttons"	=> []
			];
			$this->dipendence = [
				"modules/Popup/Popup.min.js",
				"modules/Button/Button.min.css"
			];
		}
		public function init( $_title="", $_text="", $_buttons="" ) {
			$this->data["title"] 		= $_title;
			$this->data["text"] 		= $_text;
			$this->data["buttons"]	= $_buttons;
		}
		public function draw() {
			$temp = "";
			jBlock();
			?>
			<div id="modalInfo" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><?=$this->data["title"]; ?></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="modal-data alert">
										<?=$this->data["text"]; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<?php
								foreach ($this->data["buttons"] as $i)
									echo $i;
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			$temp = jBlockEnd();
			return $temp;
		}
		public function exitButton( $_message ) {
			return '<button type="button" class="btn btn-default" style="vertical-align:bottom;" data-dismiss="modal">'.$_message.'</button>';
		}
	}
?>
