<?php
echo '
	<div>StringID:<br />
		<div>get stringid depuis stringid:<br />
			<form id="formStringID1" method="GET">
				stringid:&nbsp;<input id="idStringID1" type="text" name="stringid" value="sTrInGID01"/><br />
				<input type="submit" id="submitStringID1" name="submit" value="OK!"/>
			</form>
		</div>
		<div>get stringid depuis album id:<br />
			<form id="formStringID2" method="GET">
				albumid:&nbsp;<input id="idStringID2" type="text" name="albumid" value="2"/><br />
				<input type="submit" id="submitStringID2" name="submit" value="OK!"/>
			</form>
		</div>
	</div>
'
?>