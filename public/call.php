<?php
/* calling tool */
session_start();
if (isset($_SESSION['user_id'])) {
	
	require "../config.php";
	require "../common.php";
	
	/* updating contact record */
	if (isset($_POST['submit']) || isset($_POST['exit'])) {
		
		/* remove 'submit' and 'exit' from _POST array so we don't send extra info to the database */
		unset($_POST['submit']);
		
		if(isset($_POST['exit'])) {
			unset($_POST['exit']);
			$also_exit = true;
		}
		
		/* also remove 'id' and save it as another variable */
		$contact_id = escape($_POST['contact_id']);
		unset($_POST['contact_id']);
	
		/* save _POST as object */
		$the_update = json_decode(json_encode($_POST, JSON_FORCE_OBJECT));
		
		/* save updated contact in database */
		
		/* note that unchecked boxes won't be changed since 0 values on checkboxes aren't sent in _POST,
			this may be a problem down the road, but in most cases, we won't be un-checking boxes, since
			we won't be contacting people more than once... */
		try {
			$connection = new PDO($dsn, $username, $password, $options);
			$the_count = 0;
			$the_fields = '';
			foreach($the_update as $col => $val) {
				if ($the_count++ != 0) $the_fields .= ', ';
				$col = escape($col);
				$val = escape($val);
				$the_fields .= "`$col` = '$val'";
			}
			$sql = "UPDATE `contacts` SET " . $the_fields . " WHERE `id` = ". $contact_id;
			$statement = $connection->prepare($sql)->execute();
		} catch(PDOException $error) {
			echo $sql . "<br>" . $error->getMessage();
		}
		
		if($also_exit == true) {
			/* also exit */
			header("Location: " . $web_root);
		}
	} else if (isset($_POST['skip'])) {
		/* skip contact - do nothing - should pull a fresh record */
	}
	
	/* attempting to fetch campaign by ID */
	if (isset($_GET['campaign'])) {
		$campaign_id = $_GET['campaign'];
		try {
		    $connection = new PDO($dsn, $username, $password, $options);
		    $sql = "SELECT * FROM campaigns WHERE id = " . $campaign_id;
		    $statement = $connection->prepare($sql);
		    $statement->execute();
		    $result = $statement->fetchAll();
		} catch(PDOException $error) {
		    echo $sql . "<br>" . $error->getMessage();
		}
	}
	
	?>
	<?php require "templates/header.php"; ?>
	        
	<?php if ($result && $statement->rowCount() == 1) {
		$campaign_name = escape($result[0]["name"]);
		$campaign_script = escape($result[0]["script"]);
		?>
	        <h2>Currently Calling: <?php echo $campaign_name ?></h2>
	
			<form method="post">
			
			<?php $contact = $connection->query("SELECT * FROM contacts WHERE campaign = " . $campaign_id . " AND last_accessed < " . strtotime("- 30 seconds") . " AND contacted = 0 AND do_not_call = 0 ORDER BY RAND() LIMIT 1");
			if($contact->rowCount() > 0) {
				while ($contact_result = $contact->fetch()) {
					
					$the_contact = (object) [
						'id' => escape($contact_result["id"]),
						'name' => escape($contact_result["firstname"] . " " . $contact_result["lastname"]),
						'dob' => escape($contact_result["birthday"]),
						'phone' => escape($contact_result["phone"]),
						'ld' => escape($contact_result["ld"]),
						'cd' => escape($contact_result["cd"]),
						'party' => escape($contact_result["likely_party"]),
						'notes' => escape($contact_result["notes"]),
						'last_accessed' => escape($contact_result["last_accessed"]),
						'contacted' => escape($contact_result["contacted"]),
						'do_not_call' => escape($contact_result["do_not_call"]),
						'will_volunteer' => escape($contact_result["will_volunteer"]),
						'does_support' => escape($contact_result["does_support"]),
						'call_back_later' => escape($contact_result["call_back_later"]),
						'follow_up' => escape($contact_result["follow_up"])
						];
	
			/* load lock.php in hidden iframe to "lock" the contact in 30 second intervals to prevent other callers from getting the same record */
			?>
			<iframe width="1" height="1" src="lock?c=<?php echo $the_contact->id; ?>"?></iframe>
	
	        <fieldset>
		        <legend>Contact</legend>
		        <p><strong>Name: </strong> <?php echo $the_contact->name; ?></p>
		        <p><strong>Phone: </strong> <a href="tel:<?php echo $the_contact->phone; ?>"><?php echo format_phone_number($the_contact->phone); ?></a></p>
		        <p><strong>Likely party: </strong> <?php echo $the_contact->party; ?></p>
		        <p><strong>Age: </strong> <?php echo get_age($the_contact->dob); ?></p>
		        <p><strong>LD: </strong> <?php echo $the_contact->ld; ?>, <strong>CD: </strong> <?php echo $the_contact->cd; ?></p>
	        </fieldset>
	        
	        &nbsp;
	        
			<fieldset>
				<legend>Script</legend>
				<p><tt class="script"><?php echo $campaign_script ?></tt></p>
			</fieldset>
			
			&nbsp;
			
			<fieldset>
				<legend>Notes</legend>
				    
				    <p><input type="checkbox" name="contacted" id="contacted" value="1" class="inline"<?php if($the_contact->contacted > 0) { echo " checked readonly"; } ?>><label for="contacted">Contacted</label></p>
				    <p><input type="checkbox" name="do_not_call" id="donotcall" value="1" class="inline"<?php if($the_contact->do_not_call > 0) { echo " checked readonly"; } ?>><label for="donotcall">Do not call</label></p>
				    <p><input type="checkbox" name="does_support" id="supportive" value="1" class="inline"<?php if($the_contact->does_support > 0) { echo " checked readonly"; } ?>><label for="supportive">Supportive</label></p>
				    <p><input type="checkbox" name="will_volunteer" id="willvolunteer" value="1" class="inline"<?php if($the_contact->will_volunteer > 0) { echo " checked readonly"; } ?>><label for="willvolunteer">Wants to volunteer</label></p>
				    <p><input type="checkbox" name="follow_up" id="followup" value="1" class="inline"<?php if($the_contact->follow_up > 0) { echo " checked readonly"; } ?>><label for="followup">Follow up</label></p>
				    <p><input type="checkbox" name="call_back_later" id="callbacklater" value="1" class="inline"<?php if($the_contact->call_back_later > 0) { echo " checked readonly"; } ?>><label for="callbacklater">Call back later</label></p>
				    <p><textarea name="notes" id="notes" placeholder="Specific notes go here" rows="4" cols="10"><?php if($the_contact->notes != null) { echo $the_contact->notes; } ?></textarea></p>
				
	
			</fieldset>
	
			<input type="hidden" name="contact_id" value="<?php echo $the_contact->id; ?>">
			<p><button type="submit" name="submit">Save and continue</button> <button type="submit" name="skip">Skip</button></p>
			<p><button type="submit" class="small grey" name="exit">Save and exit</button></p>
			
			<?php
				// end sub-query for contact
				}
			} else {
				?><p><em>No contacts located, or all contacts already called or locked.<br>Try reloading in a few minutes if you're sure there are more contacts.</em></p>
				<p><a href="/">Return to main menu</a>.</p><?php
			}
			?>
			
			</form>
	    <?php } else { ?>
	    
	    	<h2>Campaign not found.</h2>
	    	<p>Contact your campaign staff.</p>
	    	
	    <?php } ?>
	
	
	
	<?php require "templates/footer.php"; ?>
	
<?php } else {
	header("Location: ". $web_root . "/login");
}
?>