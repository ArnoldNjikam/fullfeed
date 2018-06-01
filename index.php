<?php
require_once(dirname(__FILE__).'/config.php');
// check for custom index.php (custom_index.php)
if (!defined('_FF_FTR_INDEX')) {
	define('_FF_FTR_INDEX', true);
	if (file_exists(dirname(__FILE__).'/custom_index.php')) {
		include(dirname(__FILE__).'/custom_index.php');
		exit;
	}
}
?><!DOCTYPE html>
<html>
  <head>
    <title>Full Content RSS Feeds Generator</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<meta name="robots" content="noindex, nofollow" />
	<meta name="viewport" content="width=device-width">
	<meta name="description" content="Best full RSS feed generator to convert partial feed to full text rss feed" />
	<meta name="keywords" content="full rss, full feed, full content rss, full text rss, full rss generator" />
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="js/bootstrap-popover.js"></script>
	<script type="text/javascript" src="js/bootstrap-tab.js"></script>
	<script type="text/javascript">
	var baseUrl = 'http://'+window.location.host+window.location.pathname.replace(/(\/index\.php|\/)$/, '');
	$(document).ready(function() {
		// remove http scheme from urls before submitting
		$('#form').submit(function() {
			$('#url').val($('#url').val().replace(/^http:\/\//i, ''));
			return true;
		});

		// tooltips
		$('a[rel=tooltip]').tooltip();
	});
	</script>
	
	
  </head>
  <body>
	<div class="container">

	<center>
		<div style="padding-top: 5px;"><a href="index.php" title="Full Content RSS Feed"><img src="images/FullContentRSS.png" title="Full Content RSS Feed"></a></div>
		<p style="margin-bottom: 20px;"><em>Best full RSS feed generator to convert partial feed to full text rss feed</em></p>
	</center>

	
	<div style="padding:24px 30px 10px 30px; border-radius:5px; margin-top:30px; margin-bottom:30px; background:#e9eff1;">
	<form method="get" action="feed.php" id="form" class="form-horizontal">

	

		<div class="form-group">
			<label class="control-label col-sm-2" for="url">RSS FEED URL</label>
			<div class="col-sm-10"><input type="text" id="url" name="url" class="form-control input-lg" title="RSS Feed URL" />
			<div style=font-size:80%;><font color="grey">Enter a valid RSS feed here, e.g. http://en.blog.wordpress.com/feed/ </font></div></div>
		</div>


		
		
	<?php if (isset($options->api_keys) && !empty($options->api_keys)) { ?>
		<div class="form-group">
			<label class="control-label col-sm-2" for="key">Access key</label>
			<div class="col-sm-10">
			<input type="text" id="key" name="key" class="form-control input-lg" <?php if ($options->key_required) echo 'required'; ?> title="Access Key" data-content="<?php echo ($options->key_required) ? 'An access key is required to generate a feed' : 'If you have an access key, enter it here.'; ?>" />
			</div>
		</div>
	<?php } ?>
	
		<div class="form-group">
			<label class="control-label col-sm-2" for="max">Articles per feed</label>
			<div class="col-sm-10">
	<?php
	// echo '<select name="max" id="max" class="input-medium">'
	// for ($i = 1; $i <= $options->max_entries; $i++) {
	//	printf("<option value=\"%s\"%s>%s</option>\n", $i, ($i==$options->default_entries) ? ' selected="selected"' : '', $i);
	// } 
	// echo '</select>';
	if (!empty($options->api_keys)) {
		$msg = 'Limit: '.$options->max_entries.' (with key: '.$options->max_entries_with_key.')';
		$msg_more = 'If you need more items, change <tt>max_entries</tt> (and <tt>max_entries_with_key</tt>) in config.';
	} else {
		$msg = 'Limit: '.$options->max_entries;
		$msg_more = 'If you need more items, change <tt>max_entries</tt> in config.';
	}
	?>	
		<input type="text" name="max" id="max" class="form-control input-lg" value="<?php echo $options->default_entries; ?>" title="Feed item limit" data-content="Set the maximum number of feed items we should process. The smaller the number, the faster the new feed is produced.<br /><br />If your URL refers to a standard web page, this will have no effect: you will only get 1 item.<br /><br /> <?php echo $msg_more; ?>" />
			<span class="help-inline" style="color: #888;"><?php echo $msg; ?></span>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-sm-2" for="links">Links</label>
			<div class="col-sm-10">
				<select name="links" id="links" class="form-control input-lg" title="Link handling" data-content="By default, links within the content are preserved. Change this field if you'd like links removed, or included as footnotes.">
					<option value="preserve" selected="selected">preserve</option>
					<option value="footnotes">add to footnotes</option>
					<option value="remove">remove</option>
				</select>
			</div>
		</div>
	
	<?php if ($options->exclude_items_on_fail == 'user') { ?>
	
		<div class="form-group">
			<label class="control-label col-sm-2" for="exc">If extraction fails</label>
			<div class="col-sm-10">
				<select name="exc" id="exc" class="form-control input-lg" title="Item handling when extraction fails" data-content="If extraction fails, we can remove the item from the feed or keep it in.<br /><br />Keeping the item will keep the title, URL and original description (if any) found in the feed. In addition, we insert a message before the original description notifying you that extraction failed.">
					<option value="" selected="selected">keep item in feed</option>
					<option value="1">remove item from feed</option>
				</select>
			</div>
		</div>
	<?php } ?>
	
		<div class="form-group">
			<label class="control-label col-sm-2" for="json">JSON output</label>
			<div class="col-sm-10">
				<input type="checkbox" name="format" value="json" id="json" style="margin-top:10px;" />
			</div>
		</div>
	
		<div class="form-group" style="margin-top:20px;">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" id="submit" name="submit" value="Create Feed" class="btn btn-primary" />
			</div>
		</div>
		
	</form>
	</div>
	




<div>
<center><br />
<div><font color="grey"> The best of Full Content RSS by<a href="http://s-42.com">S-42</a>. | <a href="mailto:info@s-42.com" target="_top">Contact Us</a></font> 
</div>
<div>
	</div>
	</div>
  </body>
</html>