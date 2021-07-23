<?php
/*
 * Copyright (C) 2004-2021 Soner Tari
 *
 * This file is part of UTMFW.
 *
 * UTMFW is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * UTMFW is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with UTMFW.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once('include.php');

$LogConf = array(
	'sslproxy' => array(
		'Fields' => array(
			'Date' => _TITLE('Date'),
			'Time' => _TITLE('Time'),
			'Process' => _TITLE('Process'),
			'Prio' => _TITLE('Prio'),
			'Log' => _TITLE('Log'),
			),
		),
	);

class Sslproxy extends View
{
	public $Model= 'sslproxy';
	public $Layout= 'sslproxy';

	function __construct()
	{
		$this->Module= basename(dirname($_SERVER['PHP_SELF']));
		$this->Caption= _TITLE('SSL Proxy');

		$this->LogsHelpMsg= _HELPWINDOW('The SSL proxy takes 2 different kinds of logs: (1) STATS for periodic statistics per thread and (2) CRITICAL, ERROR, WARNING, and INFO messages.');
		
		$this->GraphHelpMsg= _HELPWINDOW('The SSL proxy is an event-driven multi-threaded program.');
		
		$this->ConfHelpMsg= _HELPWINDOW('You should restart the SSL proxy for the changes to take effect.');
	
		$this->Config = array(
			'CACert' => array(
				'title' => _TITLE2('CA Cert'),
				'info' => _HELPBOX2('Use CA cert (and key) to sign forged certs.'),
				),
			'CAKey' => array(
				'title' => _TITLE2('CA Key'),
				'info' => _HELPBOX2('Use CA key (and cert) to sign forged certs.'),
				),
			'DenyOCSP' => array(
				'title' => _TITLE2('Deny OCSP'),
				'info' => _HELPBOX2('Deny all OCSP requests on all proxyspecs.'),
				),
			'SSLCompression' => array(
				'title' => _TITLE2('SSL Compression'),
				'info' => _HELPBOX2('Enable/disable SSL/TLS compression on all connections.'),
				),
			'ForceSSLProto' => array(
				'title' => _TITLE2('Force SSL Protocol'),
				'info' => _HELPBOX2('Force SSL/TLS protocol version only.'),
				),
			'DisableSSLProto' => array(
				'title' => _TITLE2('Disable SSL Protocol'),
				'info' => _HELPBOX2('Disable SSL/TLS protocol version.'),
				),
			'MinSSLProto' => array(
				'title' => _TITLE2('Min SSL Protocol'),
				'info' => _HELPBOX2('Min SSL/TLS protocol version.'),
				),
			'MaxSSLProto' => array(
				'title' => _TITLE2('Max SSL Protocol'),
				'info' => _HELPBOX2('Max SSL/TLS protocol version.'),
				),
			'Ciphers' => array(
				'title' => _TITLE2('Ciphers'),
				'info' => _HELPBOX2('Cipher specification for both server and client SSL/TLS connections.'),
				),
			'CipherSuites' => array(
				'title' => _TITLE2('Cipher Suites'),
				'info' => _HELPBOX2('Cipher suite specification for both server and client SSL/TLS connections. This is for TLS 1.3 only.'),
				),
			'LeafKeyRSABits' => array(
				'title' => _TITLE2('Leaf Key RSA Bits'),
				'info' => _HELPBOX2('Leaf key RSA keysize in bits, use 1024|2048|3072|4096.'),
				),
			'ConnIdleTimeout' => array(
				'title' => _TITLE2('Connection Idle Timeout'),
				'info' => _HELPBOX2('Close connections after this many seconds of idle time.'),
				),
			'ExpiredConnCheckPeriod' => array(
				'title' => _TITLE2('Expired Connection Check Period'),
				'info' => _HELPBOX2('Check for expired connections every this many seconds.'),
				),
			'LogStats' => array(
				'title' => _TITLE2('Log Statistics'),
				'info' => _HELPBOX2('Log statistics to syslog.'),
				),
			'StatsPeriod' => array(
				'title' => _TITLE2('Statistics Period'),
				'info' => _HELPBOX2('Log statistics every this many ExpiredConnCheckPeriod periods.'),
				),
			'RemoveHTTPAcceptEncoding' => array(
				'title' => _TITLE2('Remove HTTP Accept-Encoding'),
				'info' => _HELPBOX2('Remove HTTP header line for Accept-Encoding. HTTP compression and encodings are disabled to make the content logs more readable. You can turn this off if content logging is disabled.'),
				),
			'RemoveHTTPReferer' => array(
				'title' => _TITLE2('Remove HTTP Referer'),
				'info' => _HELPBOX2('Remove HTTP header line for Referer. The OWASP considers it a vulnerabilty, and it may cause redirection errors with SSLproxy.'),
				),
			'VerifyPeer' => array(
				'title' => _TITLE2('Verify Peer'),
				'info' => _HELPBOX2('Verify peer using default certificates.'),
				),
			'AllowWrongHost' => array(
				'title' => _TITLE2('Allow Wrong Host'),
				'info' => _HELPBOX2('Allow wrong host names in certificates.'),
				),
			'UserAuth' => array(
				'title' => _TITLE2('Authenticate Users'),
				'info' => _HELPBOX2('Require authentication for users to use SSLproxy.'),
				),
			'UserTimeout' => array(
				'title' => _TITLE2('User Idle Timeout'),
				'info' => _HELPBOX2('Time users out after this many seconds of idle time.'),
				),
			'ValidateProto' => array(
				'title' => _TITLE2('Validate Protocols'),
				'info' => _HELPBOX2('Validate proxy spec protocols.'),
				),
			'OpenFilesLimit' => array(
				'title' => _TITLE2('Open Files Limit'),
				'info' => _HELPBOX2('Open files limit, use 50-10000.'),
				),
			);
	}

	function PrintStatsMaxValues($interval)
	{
		$key2Titles = array(
			'Load' => _TITLE2('Connections'),
			'UploadKB' => _TITLE2('Total Upload (KB)'),
			'DownloadKB' => _TITLE2('Total Download (KB)'),
			'CreateTime' => _TITLE2('Connection Duration'),
			'AccessTime' => _TITLE2('Connection Idle Time'),
			'Fd' => _TITLE2('File Descriptors'),
			);
		?>
		<table id="logline" style="width: 10%;">
			<tr>
				<th></th>
				<th nowrap><?php echo _('Max Values') ?></th>
			</tr>
		<?php
		$this->Controller($Output, 'GetMaxStats', $interval);
		$maxValues= json_decode($Output[0], TRUE);
		
		foreach ($key2Titles as $key => $title) {
			?>
			<tr>
				<th style="text-align: right;" nowrap>
					<?php echo $title ?>
				</th>
				<td style="text-align: center;">
					<?php echo $maxValues[$key] ?>
				</td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
	}

	static function DisplayDashboardExtras()
	{
		global $ServiceInfo;
		?>
		<tr>
			<td colspan="4">
				<a class="transparent" href="/sslproxy/connstats.php"><img src="/system/dashboard/sslproxy.png" name="sslproxy" alt="sslproxy" title="<?php echo _TITLE2('Connections handled by the SSL Proxy') ?>"></a>
			</td>
		</tr>
		<tr>
			<td id="dashboard" colspan="4" title="<?php echo _TITLE2('Number of connections handled by the SSL Proxy') ?>">
				<a class="transparent-white" href="/sslproxy/conns.php">
				<div id="count" style="background-color: #008194">
					<table>
						<tr class="count">
							<td class="count">
							<?php echo $ServiceInfo['sslproxy']['conns'] ?>
							</td>
						</tr>
						<tr>
							<td class="prio">
							<?php echo _TITLE('CONNECTIONS') ?>
							</td>
						</tr>
					</table>
				</div>
				</a>
			</td>
		</tr>
		<tr>
			<td id="dashboard" colspan="4" title="<?php echo _TITLE2('Number of file descriptors used by the SSL Proxy') ?>">
				<a class="transparent-white" href="/sslproxy/logs.php">
				<div id="count" style="background-color: #da5400">
					<table>
						<tr class="count">
							<td class="count">
							<?php echo $ServiceInfo['sslproxy']['fds'] ?>
							</td>
						</tr>
						<tr>
							<td class="prio">
							<?php echo _TITLE('FILE DESCRIPTORS') ?>
							</td>
						</tr>
					</table>
				</div>
				</a>
			</td>
		</tr>
		<?php
	}
}

$View= new Sslproxy();

/**
 * Prints proxy specifications and download CA cert form.
 */
function PrintProxySpecsDownloadCACertForm()
{
	global $View, $Class, $Row;
	?>
	<tr class="<?php echo $Class ?>">
		<td class="title">
			<?php echo _TITLE2('Proxy Specifications').':' ?>
		</td>
		<td>
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				<input style="display:none;" type="submit" name="Add" value="<?php echo _CONTROL('Add') ?>"/>
				<select name="Specs[]" multiple style="width: 300px; height: 100px;">
					<?php
					if ($View->Controller($output, 'GetSpecs')) {
						foreach ($output as $mirror) {
							?>
							<option value="<?php echo $mirror ?>"><?php echo $mirror ?></option>
							<?php
						}
					}
					?>
				</select>
				<input type="submit" name="DeleteSpec" value="<?php echo _CONTROL('Delete') ?>"/><br />
				<input type="text" name="SpecToAdd" style="width: 300px;" maxlength="200"/>
				<input type="submit" name="AddSpec" value="<?php echo _CONTROL('Add') ?>"/>
			</form>
		</td>
		<td class="none">
			<?php
			PrintHelpBox(_HELPBOX2("The proxy specification format is type listenaddr+port up:utmport."));
			?>
		</td>
	</tr>
	<?php $Class= $Row++ % 2 == 0 ? 'evenline' : 'oddline'; ?>
	<tr class="<?php echo $Class ?>">
		<td class="title">
			<?php echo _TITLE2('Passthrough Sites').':' ?>
		</td>
		<td>
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				<input style="display:none;" type="submit" name="Add" value="<?php echo _CONTROL('Add') ?>"/>
				<select name="Sites[]" multiple style="width: 300px; height: 100px;">
					<?php
					if ($View->Controller($output, 'GetPassSites')) {
						foreach ($output as $mirror) {
							?>
							<option value="<?php echo $mirror ?>"><?php echo $mirror ?></option>
							<?php
						}
					}
					?>
				</select>
				<input type="submit" name="DeleteSite" value="<?php echo _CONTROL('Delete') ?>"/><br />
				<input type="text" name="SiteToAdd" style="width: 300px;" maxlength="300"/>
				<input type="submit" name="AddSite" value="<?php echo _CONTROL('Add') ?>"/>
			</form>
		</td>
		<td class="none">
			<?php
			PrintHelpBox(_HELPBOX2("Passthrough sites. The format is site [(clientaddr|(user|*) [description keyword])]. If the site matches SNI or common names in the SSL certificate, the connection is passed through the proxy."));
			?>
		</td>
	</tr>
	<?php
	$View->Controller($output, 'GetCACertFileName');
	$certFile= $output[0];
	$Class= $Row++ % 2 == 0 ? 'evenline' : 'oddline';
	?>
	<tr class="<?php echo $Class ?>">
		<td class="title">
			<?php echo _TITLE2('Download CA Cert').':' ?>
		</td>
		<td>
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
				<input type="submit" name="Download" value="<?php echo _CONTROL('Download') ?>"/>
				<input type="hidden" name="LogFile" value="<?php echo $certFile ?>" />
			</form>
		</td>
		<td class="none">
			<?php
			PrintHelpBox(_HELPBOX2("Download the CA cert file to install on client web browsers and mail programs."));
			?>
		</td>
	</tr>
	<?php
}
?>
