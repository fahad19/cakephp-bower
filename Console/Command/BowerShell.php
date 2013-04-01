<?php

App::uses('AppShell', 'Console/Command');

/**
 * Bower Shell
 *
 * @category Shell
 * @package  Bower
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     https://github.com/fahad19/cakephp-bower
 */
class BowerShell extends AppShell {

	public function initialize() {
		$this->_createRc();
		$this->_createDirectory();
	}

	public function main() {
		$this->out('################################');
		$this->out('CakePHP plugin for Twitter Bower');
		$this->out('################################');
		$this->out(' ');

		$this->out('Usage:');
		$this->out('------');
		$this->out('');
		
		$this->out('Download dependencies for app and all active plugins');
		$this->out('  $ ./Console/cake Bower.bower fetch .');
		
		$this->out('');
		$this->out('Download dependencies for the main app only');
		$this->out('  $ ./Console/cake Bower.bower fetch app');
		
		$this->out('');
		$this->out('Download dependencies for a particular Plugin only');
		$this->out('  $ ./Console/cake Bower.bower fetch PluginName');
		$this->out('');
	}

	public function fetch() {
		$pluginName = '.';
		if (isset($this->args['0'])) {
			$pluginName = $this->args['0'];
		}
		$this->out('Installing packages for: ' . $pluginName);
	
		$dependencies = array();
		if ($pluginName == '.') {
			// install ALL
			$dependencies = $this->_getDependencies(APP);
			foreach (CakePlugin::loaded() as $plugin) {
				$dependencies = $this->_getDependencies(App::pluginPath($plugin), $dependencies);
			}
		} elseif (strtolower($pluginName) == 'app') {
			// install App only
			$path = APP;
			$dependencies = $this->_getDependencies($path);
		} else {
			// install Plugin only
			$path = App::pluginPath($pluginName);
			$dependencies = $this->_getDependencies($path);
		}

		if (count($dependencies) > 0) {
			$cmd = 'install';
			foreach ($dependencies as $name => $semVer) {
				$cmd .= ' ' . $name . '#' . $semVer;
			}
			$this->out($this->_runCmd($cmd));
		} else {
			$this->out('No packages info found to be installed.');
		}
	}

	public function search() {
		$this->_run(__FUNCTION__);
	}

	public function install() {
		$this->_run(__FUNCTION__);
	}

	public function uninstall() {
		$this->_run(__FUNCTION__);
	}

	protected function _run($command) {
		$q = implode(' ' , $this->args);
		$this->out($this->_runCmd($command . ' ' . $q));
	}

	protected function _runCmd($cmd) {
		$full = 'cd ' . APP . ' && bower ' . $cmd;
		return shell_exec($full);
	}

	protected function _getDependencies($path, $dependencies = array()) {
		if (substr($path, -1) != '/') {
			$path .= '/';
		}

		$jsonPath = $path . 'component.json';
		$rc = $this->_readRc();
		if (isset($rc['json'])) {
			$jsonPath = $path . $rc['json'];
		}

		if (file_exists($jsonPath)) {
			$json = json_decode(file_get_contents($jsonPath), true);
			if (isset($json['dependencies']) && is_array($json['dependencies'])) {
				foreach ($json['dependencies'] as $name => $semVer) {
					if (isset($dependencies[$name])) {
						if ($semVer > $dependencies[$name]) {
							$ver = $semVer;
						} else {
							$ver = $dependencies[$name];
						}
						$dependencies[$name] = $ver;
					} else {
						$dependencies[$name] = $semVer;
					}
				}
			}
		}

		return $dependencies;
	}

	protected function _createRc() {
		$rcPath = APP . '.bowerrc';
		if (!file_exists($rcPath)) {
			$copy = App::pluginPath('Bower') . '.bowerrc.default';
			copy($copy, $rcPath);
			$this->out('File created at: ' . $rcPath);
		} else {
			// $this->out('File already exists at: ' . $rcPath);
		}
	}

	protected function _readRc() {
		return json_decode(file_get_contents(APP . '.bowerrc'), true);
	}

	protected function _createDirectory() {
		$bowerrc = json_decode(file_get_contents(APP . '.bowerrc'), true);
		$componentsPath = $bowerrc['directory'];
		$absoluteComponentsPath = APP . $componentsPath;
		if (!is_dir($absoluteComponentsPath)) {
			mkdir($absoluteComponentsPath, 0, true);
			$this->out('Directory created at: ' . $absoluteComponentsPath);
		} else {
			// $this->out('Directory already exists at: ' . $absoluteComponentsPath);
		}
	}

}