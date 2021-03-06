<?php
	function getGitLog( $_dir = "./") {
		if(!file_exists($_dir)) return [];
		$currentDir = getcwd();
		chdir($_dir);
		$git_history = [];
		$git_logs	= [];
		$gitPath	= str_replace('\\', '/', exec("git rev-parse --show-toplevel"));
		$rootPath	= str_replace('\\', '/', getcwd ());
		if( $gitPath != $rootPath ) {
			chdir($currentDir);
			return [];
		}

		exec("git log --decorate=full --tags", $git_logs);
		$last_hash = null;
		foreach ($git_logs as $line) {
			$line = trim($line);
			if (!empty($line)) {
				// Commit
				if (strpos($line, 'commit') !== false) {
					$hash = explode(' ', $line);
					$hash = trim(end($hash));
					$git_history[$hash] = [
						'tag' => '-1.0.0',
						'author' => '',
						'date' => '',
						'message' => ''
					];
					$last_hash = $hash;
				 if (strpos($line, 'tag') !== false) {
					$tag = explode(':', $line);
					$tag = explode('/', $tag[1]);
					$tag = explode(',', $tag[2]);
					$tag = explode(')', $tag[0]);
					$tag = trim($tag[0]);
					$git_history[$last_hash]['tag'] = $tag;
					}
				}
				else if (strpos($line, 'Author') !== false) {
					$author = explode(':', $line);
					$author = trim(end($author));
					$git_history[$last_hash]['author'] = $author;
				}
				else if (strpos($line, 'Date') !== false) {
					$date = explode(':', $line, 2);
					$date = trim(end($date));
					$git_history[$last_hash]['date'] = date('d/m/Y H:i:s A', strtotime($date));
				}
				else
					$git_history[$last_hash]['message'] .= $line ." ";
			}
		}
		chdir($currentDir);
		return $git_history;
	}
?>
