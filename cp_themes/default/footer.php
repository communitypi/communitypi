		</div>
		<div id="footer">
				<div id="copyright">&copy; <?php echo $communitypi->getSetting('site_name'); ?> <?php echo date("Y"); ?><br />Powered by CommunityPi</div>
				<div id="nav"><?php
				$mtime = microtime(); 
				$mtime = explode(" ",$mtime); 
				$mtime = $mtime[1] + $mtime[0]; 
				$endtime = $mtime; 
				$totaltime = ($endtime - $starttime); 
				echo "This page was generated in ". round($totaltime, 4) ." seconds"; 
;?></div>
				<div id="credit"></div>
		</div>
		<div id="page_dim"></div>

	</body>
</html>