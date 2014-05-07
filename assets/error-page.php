<div style="position: fixed; background: lightgrey; border: 1px solid red; padding: 10px 30px 10px 20px; color: black; width: 800px; margin-left: -400px; top: 10px; left: 50%; border-radius: 4px; z-index: 10000; font-size: 18px; line-height: 25px; ">
	<div style="font-size:12px; padding-bottom:5px;"><?php echo $url ?></div>
	<span><?php echo $title ?></span>
	<div
		style="position:absolute; top: 5px; right: 5px; background: darkgray; color:white; width: 20px; height: 20px; line-height: 16px; text-align:center; font-weight:bold; cursor:pointer; border-radius: 4px;"
		onclick="javascript: this.parentNode.parentNode.removeChild(this.parentNode); return false;">
		&times;
	</div>

<?php if ($javascript_errors): ?>
	<div style="margin-top:10px;">
		Javascript Errors:
		<ul style="font-family:_monospace; font-size: 12px; background-color:white; padding: 10px;">
<?php foreach ($javascript_errors as $error): ?>
		<li style="margin-bottom:5px;">
			<div style="color:red;"><?php echo $error['errorMessage'] ?></div>
			<div style="font-size:11px">in <?php echo $error['sourceName'] ?> line <?php echo $error['lineNumber'] ?></div>
		</li>
<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>

<?php if ($javascript_messages): ?>
	<div style="margin-top:10px;">
		Javascript Console Messages:
		<div style="font-size: 12px; background-color:white; padding: 10px;">
<?php foreach ($javascript_messages as $message): ?>
			<pre style="margin:0 0 5px 0;"><?php echo $message ?></pre>
<?php endforeach ?>
		</div>
	</div>
<?php endif ?>
</div>
