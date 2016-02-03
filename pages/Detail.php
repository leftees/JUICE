<?php
	class Detail extends Template {
		public function __construct() {
			parent::__construct();
			$this->data["content"] = $this->makePage();
			$this->data["menu"] = $this->makeMenu();
			$this->data["js"] = array_merge( $this->data["js"], array(
				"js/home.min.js"
			));
		}
		public function makePage() {
			$sites = ($this->returnSubFolder("projects"));
			jBlock();
			?>
			<div class="row" id="elencoSiti">
				<div class="col-lg-12">
					<?php
					$nPerCol = 6;
					$max = 12;
					$dim = $max / $nPerCol;
					$cont = 0;
					$percent = "";
					$precentCurrent = 0;
					$dir = getcwd();
					if(!isset($_GET["proj"])) $_GET["proj"] = "JATE";
					$project = $_GET["proj"];
					$logs = getGitLog("./projects/$project/");
					foreach ( $logs as $i ) {
						$cont++;
						if($cont % $nPerCol == 1)
							echo '<div class="row">';
						$percent = explode(".",$i["tag"]);
						$percent = 100 * intval($percent[0]) + 10 * intval($percent[1]) + intval($percent[2]);
						if( $percent >= 0 )
							$precentCurrent = $percent;
						?>
						<!-- <a href="projects/<?=$project?>"> -->
							<div class="col-lg-<?=$dim?>">
								<div class="row" style="margin:0px;">
									<div class="well well-sm col-xs-12">
										<div class="row">
											<div class="text col-xs-10">
												<div class="sito"><b>Site:</b> <?=$project?><br></div>
												<div class="autore"><b>Author:</b> <?=$i["author"]?><br></div>
												<div class="tag"><b>Tag:</b> <?php if(isset($i["tag"])) echo $i["tag"]?><br></div>
												<div class="data"><b>Date:</b> <?=$i["date"]?><br></div>
												<div class="messaggio"><b>Message:</b> <?=$i["message"]?><br></div>
											</div>
											<div class="buttons col-xs-2">
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="progress">
													<div class="progress-bar progress-bar-striped active <?=$this->getColor($precentCurrent)?>" role="progressbar" aria-valuenow="<?=$precentCurrent?>"
													aria-valuemin="0" aria-valuemax="100" style="text-shadow: 0px 0px 8px black; width:<?=$precentCurrent%100?>%">
														<b><?=$precentCurrent?>%</b>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!-- </a> -->
						<?php
						if(($cont % $nPerCol == 0) || ($i == end( $logs )))
							echo '</div>';
					} ?>
				</div>
			</div>
			<?php
			$temp = jBlockClose();
			return $temp;
		}
		private function returnSubFolder( $dir = "./" ) {
			$temp = array();
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						if( ($file !='.')&&($file !='..')&&($file !='phpmyadmin') ) {
							array_push($temp,$file);
						}
					}
					closedir($dh);
				}
			}
			return $temp;
		}
		private function makeMenu() {
			jBlock();
			?>
				<li><a href="#"><span class="glyphicon glyphicon-plus newSite" aria-hidden="true"></span></a></li>
				<li><a href="#" class="openConsole" folder=".\\"><span class="glyphicon glyphicon-console" aria-hidden="true"></span></a></li>
				<li><a href="projects/phpmyadmin/"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span></a></li>
			<?php
			$temp = jBlockEnd();
			return $temp;
		}
		private function getColor( $_percent ) {
			$temp = "red";
			if( $_percent / 100 < 1 ) $temp = "progress-bar-danger";
			else if( $_percent / 100 < 2 ) $temp = "progress-bar-warning";
			else if( $_percent / 100 < 3 ) $temp = "progress-bar-success";
			else if( $_percent / 100 < 4 ) $temp = "progress-bar-info";
			return $temp;
		}
	}
?>
