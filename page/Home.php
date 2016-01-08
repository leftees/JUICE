<?php
	class Home extends Template {
		public function __construct() {
			parent::__construct();
			$this->data["content"] = $this->makePage();
			$this->data["menu"] = $this->makeMenu();
			$this->data["js"] = array_merge( $this->data["js"], array(
				"js/home.min.js"
			));
		}
		public function makePage() {
			$siti = ($this->returnSubFolder("projects"));
			jBlock();
			?>
			<div class="row" id="elencoSiti">
				<div class="col-lg-12">
					<?php
					$nPerCol = 6;
					$max = 12;
					$dim = $max / $nPerCol;
					$cont = 0;
					$percent;
					$dir = getcwd();
					foreach ( $siti as $j ) {
						$gitInfo = getGitLog($dir."/projects/".$j);
						$i = array();
						$i["author"] = " - ";
						$i["tag"] = "1.0.0";
						$i["date"] = " - ";
						$i["message"] = " - ";
						if(count($gitInfo)!=0)
							$i = array_shift($gitInfo);
						$cont++;
						if($cont % $nPerCol == 1)
							echo '<div class="row">';
						if(isset($i["tag"])) {
							$percent = explode(".",$i["tag"]);
							$percent = 100 * intval($percent[0]) + 10 * intval($percent[1]) + intval($percent[2]);
						}
					?>
						<a href="projects/<?=$j?>">
							<div class="col-lg-<?=$dim?>">
								<div class="row" style="margin:0px;">
									<div class="well well-sm col-xs-12">
										<div class="row">
											<div class="text col-xs-10">
												<div class="sito"><b>Site:</b> <?=$j?><br></div>
												<div class="autore"><b>Author:</b> <?=$i["author"]?><br></div>
												<div class="tag"><b>Tag:</b> <?php if(isset($i["tag"])) echo $i["tag"]?><br></div>
												<div class="data"><b>Date:</b> <?=$i["date"]?><br></div>
												<div class="messaggio"><b>Message:</b> <?=$i["message"]?><br></div>
											</div>
											<div class="buttons col-xs-2">
												<a href="projects/<?=$j?>/status.php">
													<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
												</a><br><br>
												<a href="javascript:void(0)" class="openFolder" folder="projects\\<?=$j?>">
													<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
												</a><br><br>
												<a href="javascript:void(0)" class="openConsole" folder="projects\\<?=$j?>">
													<span class="glyphicon glyphicon-console" aria-hidden="true"></span>
												</a>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<div class="progress">
												  <div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="<?=$percent?>"
												  aria-valuemin="0" aria-valuemax="100" style="width:<?=$percent%100?>%">
												    <?=$percent?>%
												  </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					<?php
					if(($cont % $nPerCol == 0) || (!next( $siti )))
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
				<li><a href="projects/phpmyadmin/"><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span></a></li>
			<?php
			$temp = jBlockEnd();
			return $temp;
		}
	}
?>
