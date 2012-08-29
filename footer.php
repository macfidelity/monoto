<!--  FOOTER -->
<div id="footer" class="clear" style="text-align:center;">
	<?php 
		include 'conf/config.php';

		echo "<b>".$m_name."</b> - milestone: ".$m_milestone."  '<i>".$m_milestone_title."</i>' - build: ".$m_build." - status: "; 
		if($m_stable == false)
		{
			echo "unstable";
		}
		else
		{
			echo "stable";
		}
		echo " - developed by <a href='https://github.com/macfidelity' title='main developer'>Florian Poeck</a> since 2012.<br>"; 
		echo "Please visit the ".$m_name." <a href='http://macfidelity.github.com/monoto/' title='visit the monoto project page at github'>project page</a> and the <a href='https://github.com/macfidelity/monoto/wiki' title='visit the monoto wiki at github'>Wiki</a> for more details. If you want to participate in development consider visiting the <a href='https://github.com/macfidelity/monoto/issues' title='visit the monoto issue tracker at github'>Issue tracker</a>.<br>";
		echo "Page was generated at: ".date('Ymd H:i:s').".<br>"; 
		echo "<a href='http://opensource.org/'><img src='images/icons/OSI-logo-100x117-bkgd-dark.png' alt='open-source initiative logo' title='visit the open source initiative page' width='50'></a>";
	?>
</div>